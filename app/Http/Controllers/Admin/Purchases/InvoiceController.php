<?php

namespace App\Http\Controllers\Admin\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Division;
use App\Models\Group;
use App\Models\Purchase\Invoice;
use App\Models\Purchase\InvoiceDetail;
use App\Models\Purchase\AccountReceive;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Validator};

class InvoiceController extends Controller
{
    protected $code;

    public function __construct()
    {
        $number = Invoice::count();
        if ($number > 0) {
            $number = Invoice::max('code');
            $strnum = substr($number, 2, 3);
            $strnum = $strnum + 1;
            if (strlen($strnum) == 3) {
                $code = 'PI' . $strnum;
            } else if (strlen($strnum) == 2) {
                $code = 'PI' . "0" . $strnum;
            } else if (strlen($strnum) == 1) {
                $code = 'PI' . "00" . $strnum;
            }
        } else {
            $code = 'PI' . "001";
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
        $group = Group::where('author_id', Auth::user()->id)->first();
        $invoices = Invoice::select('id', 'date', 'code', 'total', 'status')
            ->where('group_id', $group->id)
            ->paginate(10);

        return view('admin.purchase.invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        $contacts = Contact::select('id', 'name')
        ->where('customer', 1)
        ->where('group_id', $group->id)
        ->orderBy('name')->pluck('name', 'id')->take(20);
        return view('admin.purchase.invoice.create', [
            'code' => $this->code,
            'group' => $group,
            'contacts' => $contacts
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
            'order_id' => 'exists:orders,id',
            'date' => 'required|date|date_format:Y-m-d',
            'status' => 'sometimes',
            'invoices.*.product_id' => 'required|exists:products,id',
            'invoices.*.amount' => 'required|numeric',
            'invoices.*.unit' => 'required',
            'invoices.*.price' => 'required',
            'invoices.*.total' => 'required',
            'total' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        try {
            DB::transaction(function () use ($request) {
                $invoices = Invoice::create(array_merge(
                    $request->except('invoices', 'status', 'total'),
                    [
                        'status' => !empty($request->status) ? '1' : '0',
                        'total' => preg_replace('/[^\d.]/', '', $request->total),
                    ]
                ));

                foreach ($request->invoices as $invoice) {
                    InvoiceDetail::create([
                        'invoice_id' => $invoices->id,
                        'product_id' => $invoice['product_id'],
                        'unit' => $invoice['unit'],
                        'price' => preg_replace('/[^\d.]/', '', $invoice['price']),
                        'amount' => $invoice['amount'],
                        'total' => preg_replace('/[^\d.]/', '', $invoice['total']),
                    ]);
                }
            });

            return redirect()->route('admin.purchase.invoice.index')->with('success', 'Invoice saved successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Invoice not Saved!' . $e->getMessage());
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
        $invoice = Invoice::with('invoice_details.product')->findOrFail($id);
        return view('admin.purchase.invoice.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::with('order:id,code')
            ->find($id);

        if (empty($invoice)) {
            return redirect()->route('admin.purchase.invoice.index')
                ->with('error', 'Invoice not found');
        }

        return view('admin.purchase.invoice.edit', compact('invoice'));
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
        $rules = [
            'invoices.*.product_id' => 'required|exists:products,id',
            'invoices.*.amount' => 'required|numeric',
            'invoices.*.unit' => 'required',
            'invoices.*.price' => 'required',
            'invoices.*.total' => 'required',
            'total' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        $invoice = Invoice::find($id);

        if (empty($invoice)) {
            return redirect()->route('admin.purchase.invoice.index')->with('error', 'Invoice not found.');
        }

        try {
            DB::transaction(function () use ($request, $id, $invoice) {
                $detail_id = [];

                foreach ($request->invoices as $value) {
                    $detail_id[] = $value['id'];
                }

                $detail_id = array_filter($detail_id, fn ($value) => !is_null($value) && $value !== '');
                InvoiceDetail::where('invoice_id', $id)
                    ->whereNotIn('id', $detail_id)
                    ->delete();

                foreach ($request->invoices as $item) {
                    if ($item['id'] != null) {
                        InvoiceDetail::where('id', $item['id'])->update([
                            'product_id' => $item['product_id'],
                            'amount' => $item['amount'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    } else {
                        InvoiceDetail::create([
                            'invoice_id' => $id,
                            'product_id' => $item['product_id'],
                            'amount' => $item['amount'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    }
                }

                $invoice->update(array_merge($request->except('invoices', 'total'), [
                    'total' => preg_replace('/[^\d.]/', '', $request->total)
                ]));
            });

            return redirect()->route('admin.purchase.invoice.index')
                ->with('success', 'Invoice edit successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoices = Invoice::findOrFail($id);
        $invoices->delete();

        return redirect()->back()->with('success', 'Invoice Deleted Successfully');
    }
}
