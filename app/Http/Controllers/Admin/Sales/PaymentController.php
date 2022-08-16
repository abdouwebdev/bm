<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Division;
use App\Models\Sale\InvoiceSale;
use App\Models\Sale\PaymentReceiveSaleDetail;
use App\Models\Sale\PaymentReceiveSale;
use App\Models\Sale\AccountReceiveSale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};

class PaymentController extends Controller
{
    protected $code;

    public function __construct()
    {
        $number = PaymentReceiveSale::count();
        if ($number > 0) {
            $number = PaymentReceiveSale::max('code');
            $strnum = substr($number, 2, 3);
            $strnum = $strnum + 1;
            if (strlen($strnum) == 3) {
                $code = 'PR' . $strnum;
            } else if (strlen($strnum) == 2) {
                $code = 'PR' . "0" . $strnum;
            } else if (strlen($strnum) == 1) {
                $code = 'PR' . "00" . $strnum;
            }
        } else {
            $code = 'PR' . "001";
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
        $payments =  PaymentReceiveSale::select('id', 'date', 'code', 'customer_id', 'total')->with('customer')->paginate(10);
        return view('admin.sales.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sales.payment.create', [
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
            'customer_id' => 'required|exists:contacts,id',
            'account_id' => 'exists:accounts,id',
            'date' => 'required|date|date_format:Y-m-d',
            'payments.*.invoice_id' => 'required|exists:invoice_sales,id',
            'ppayments.*.amount' => 'required',
            'payments.*.pay' => 'required',
            'total' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        try {
            DB::transaction(function () use ($request) {
                $payments = PaymentReceiveSale::create(
                    array_merge(
                        $request->except('payments', 'total', 'amount'),
                        [
                            'total' => preg_replace('/[^\d.]/', '', $request->total)
                        ]
                    )
                );

                $jml = (int)preg_replace('/[^\d.]/', '', $request->total);
                $account = Account::find($request->account_id);
                $debit = $account->debit + $jml;
                $account->update([
                    'debit' => $debit,
                    'ending_balance' => $account->beginning_balance + ($debit - $account->credit)
                ]);

                $account_receive = Account::findByCode(11000);
                $credit = $account_receive->credit + $jml;
                $account_receive->update([
                    'credit' => $credit,
                    'ending_balance' => $account_receive->beginning_balance + ($account_receive->debit - $credit)
                ]);

                $accounts = [
                    [
                        'id' => $account->id,
                        'debit' => $jml,
                        'credit' => 0,
                    ],
                    [
                        'id' => $account_receive->id,
                        'debit' => 0,
                        'credit' => $jml,
                    ]
                ];

                foreach ($request->payments as $payment) {
                    PaymentReceiveSaleDetail::create([
                        'payment_receive_sale_id' => $payments->id,
                        'invoice_id' => $payment['invoice_id'],
                        'pay' => preg_replace('/[^\d.]/', '', $payment['pay']),
                    ]);

                    $receive = AccountReceiveSale::where('invoice_id', $payment['invoice_id'])->first();

                    $receive->update([
                        'paid_off' => preg_replace('/[^\d.]/', '', $payment['pay']),
                        'remainder' => $receive->total_debt - preg_replace('/[^\d.]/', '', $payment['pay']),
                        'status' => $receive->total_debt == preg_replace('/[^\d.]/', '', $payment['pay']) ? '1' : '0'
                    ]);

                    // $account = Account::findOrFail($request->account_id);
                    // $account->update([
                    //     'debit' => preg_replace('/[^\d.]/', '', $payment['pay'])
                    // ]);

                    if ($receive->status == 1) {
                        $invoice = InvoiceSale::findOrFail($payment['invoice_id']);
                        $invoice->update([
                            'status' => '1'
                        ]);
                    }
                }
                // END Create a JOURNAL ===============================
            });

            return redirect()->route('admin.sales.payment.index')->with('success', 'Payment Successfully Saved');
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
