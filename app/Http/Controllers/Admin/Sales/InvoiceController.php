<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Purchase\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Validator};
use App\Models\Sale\{InvoiceSale, InvoiceSaleDetail, AccountReceiveSale};
use App\Models\Transaction;
use App\Models\Contact;
use App\Models\Division;
use App\Models\Group;

class InvoiceController extends Controller
{
    protected $code;

    public function __construct()
    {
        $number = InvoiceSale::count();
        if ($number > 0) {
            $number = InvoiceSale::max('code');
            $strnum = substr($number, 2, 3);
            $strnum = $strnum + 1;
            if (strlen($strnum) == 3) {
                $code = 'IN' . $strnum;
            } else if (strlen($strnum) == 2) {
                $code = 'IN' . "0" . $strnum;
            } else if (strlen($strnum) == 1) {
                $code = 'IN' . "00" . $strnum;
            }
        } else {
            $code = 'IN' . "001";
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
        $invoices = InvoiceSale::select('id', 'date', 'code', 'total', 'status')
            ->where('group_id', $group->id)
            ->paginate(10);

        return view('admin.sales.invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        return view('admin.sales.invoice.create', [
            'code' => $this->code,
            'group' => $group
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
            'order_id' => 'exists:order_sales,id',
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
                $invoices = InvoiceSale::create(array_merge(
                    $request->except('invoices', 'status', 'total'),
                    [
                        'status' => !empty($request->status) ? '1' : '0',
                        'total' => preg_replace('/[^\d.]/', '', $request->total),
                    ]
                ));

                foreach ($request->invoices as $invoice) {
                    InvoiceSaleDetail::create([
                        'invoice_id' => $invoices->id,
                        'product_id' => $invoice['product_id'],
                        'unit' => $invoice['unit'],
                        'price' => preg_replace('/[^\d.]/', '', $invoice['price']),
                        'amount' => $invoice['amount'],
                        'total' => preg_replace('/[^\d.]/', '', $invoice['total']),
                    ]);
                }

            });

            return redirect()->route('admin.sales.invoice.index')->with('success', 'Invoice saved successfully');
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
        $invoice = InvoiceSale::with('invoice_details.product')->findOrFail($id);

        return view('admin.sales.invoice.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = InvoiceSale::with('order:id,code', 'invoice_details')
            ->find($id);

        if (empty($invoice)) {
            return redirect()->route('admin.sales.invoice.index')->with('error', 'Invoice not found.');
        }

        return view('admin.sales.invoice.edit', compact('invoice'));
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
        $req = $request->except('_token', '_method');

        $validate = Validator::make($req, [
            'invoices.*.product_id' => 'required|exists:products,id',
            'invoices.*.amount' => 'required|numeric',
            'invoices.*.unit' => 'required',
            'invoices.*.price' => 'required',
            'invoices.*.total' => 'required',
            'total' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        $invoice = InvoiceSale::find($id);

        if (empty($invoice)) {
            return redirect()->route('admin.sales.invoice.index')->with('error', 'No invoice.');
        }

        try {
            DB::transaction(function () use ($id, $req, $invoice) {
                $detail_id = [];

                foreach ($req['invoices'] as $value) {
                    $detail_id[] = $value['id'];
                }

                $detail_id = array_filter($detail_id, fn ($value) => !is_null($value) && $value !== '');

                InvoiceSaleDetail::where('invoice_id', $id)
                    ->whereNotIn('id', $detail_id)
                    ->delete();

                foreach ($req['invoices'] as $item) {
                    if ($item['id'] != null) {
                        InvoiceSaleDetail::where('id', $item['id'])->update([
                            'product_id' => $item['product_id'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'amount' => $item['amount'],
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    } else {
                        InvoiceSaleDetail::create([
                            'invoice_id' => $id,
                            'product_id' => $item['product_id'],
                            'amount' => $item['amount'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    }
                }

                $invoice->update([
                    'total' => preg_replace('/[^\d.]/', '', $req['total'])
                ]);
            });

            return redirect()->route('admin.sales.invoice.index')->with('success', 'Invoice edit successfully');
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
        $invoices = InvoiceSale::findOrFail($id);
        $invoices->delete();

        return redirect()->back()->with('success', 'Invoice Deleted Successfully');
    }
}
