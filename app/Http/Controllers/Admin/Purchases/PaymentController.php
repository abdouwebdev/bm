<?php

namespace App\Http\Controllers\Admin\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Division;
use App\Models\Purchase\Invoice;
use App\Models\Purchase\PaymentReceive;
use App\Models\Purchase\PaymentReceiveDetail;
use App\Models\Purchase\AccountReceive;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};

class PaymentController extends Controller
{
    protected $code;

    public function __construct()
    {
        $number = PaymentReceive::count();
        if ($number > 0) {
            $number = PaymentReceive::max('code');
            $strnum = substr($number, 2, 3);
            $strnum = $strnum + 1;
            if (strlen($strnum) == 3) {
                $code = 'CN' . $strnum;
            } else if (strlen($strnum) == 2) {
                $code = 'CN' . "0" . $strnum;
            } else if (strlen($strnum) == 1) {
                $code = 'CN' . "00" . $strnum;
            }
        } else {
            $code = 'CN' . "001";
        }
        $this->code = $code;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments =  PaymentReceive::select('id', 'date', 'code', 'supplier_id', 'total')->with('supplier')->paginate(10);
        return view('admin.purchase.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.purchase.payment.create', [
            'code' => $this->code
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'supplier_id' => 'required|exists:contacts,id',
            'account_id' => 'exists:accounts,id',
            'date' => 'required|date|date_format:Y-m-d',
            'payments.*.invoice_id' => 'required|exists:invoices,id',
            'payments.*.amount' => 'required',
            'payments.*.pay' => 'required',
            'total' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        try {
            DB::transaction(function () use ($request) {
                $payments = PaymentReceive::create(
                    array_merge(
                        $request->except('payments', 'total', 'amount'),
                        [
                            'total' => preg_replace('/[^\d.]/', '', $request->total)
                        ]
                    )
                );

                $amt = (int)preg_replace('/[^\d.]/', '', $request->total);
                $account = Account::find($request->account_id);
                $credit = $account->credit + $amt;
                $account->update([
                    'credit' => $credit,
                    'ending_balance' => $account->beginning_balance + ($account->debit - $credit)
                ]);

                $account_debt = Account::findByCode(20100);
                $debit = $account_debt->debit + $amt;
                $account_debt->update([
                    'debit' => $debit,
                    'ending_balance' => $account_debt->beginning_balance + ($debit - $account_debt->credit)
                ]);

                $accounts = [
                    [
                        'id' => $account->id,
                        'debit' => $amt,
                        'credit' => 0,
                    ],
                    [
                        'id' => $account_debt->id,
                        'debit' => 0,
                        'credit' => $amt,
                    ]
                ];

                foreach ($request->payments as $payment) {
                    PaymentReceiveDetail::create([
                        'payment_receive_id' => $payments->id,
                        'invoice_id' => $payment['invoice_id'],
                        'pay' => preg_replace('/[^\d.]/', '', $payment['pay']),
                    ]);

                    $receive = AccountReceive::where('invoice_id', $payment['invoice_id'])->first();

                    $receive->update([
                        'paid_off' => preg_replace('/[^\d.]/', '', $payment['pay']),
                        'remainder' => $receive->total_debt - preg_replace('/[^\d.]/', '', $payment['pay']),
                        'status' => $payment->total_debt == preg_replace('/[^\d.]/', '', $payment['pay']) ? '1' : '0'
                    ]);

                    // $account = Account::findOrFail($request->account_id);
                    // $account->update([
                    //     'debit' => preg_replace('/[^\d.]/', '', $payment['pay'])
                    // ]);

                    if ($receive->status == 1) {
                        $invoice = Invoice::findOrFail($payment['invoice_id']);
                        $invoice->update([
                            'status' => '1'
                        ]);
                    }
                }

                // END Create a JOURNAL ===============================
            });

            return redirect()->route('admin.purchase.payment.index')->with('success', 'Payment Successfully Saved');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
