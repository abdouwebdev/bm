<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Sale\OfferSale;
use App\Models\Sale\OrderSale;
use App\Models\Sale\OrderSaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private $code;

    public function __construct()
    {
        $number = OrderSale::count();
        if ($number > 0) {
            $number = OrderSale::max('code');
            $strnum = (int)substr($number, 2, 3);
            $strnum = $strnum + 1;
            if (strlen($strnum) == 3) {
                $code = 'OR' . $strnum;
            } else if (strlen($strnum) == 2) {
                $code = 'OR' . "0" . $strnum;
            } else if (strlen($strnum) == 1) {
                $code = 'OR' . "00" . $strnum;
            }
        } else {
            $code = 'OR' . "001";
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
        $orders = OrderSale::select('id', 'date', 'code', 'total', 'status')
        ->where('group_id', $group->id);

        return view('admin.sales.order.index', [
            'orders' => $orders->paginate(5),
            'countOrder' => $orders->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        $offer = OfferSale::count() >= 1 ? true : false;
        return view('admin.sales.order.create', [
            'code' => $this->code,
            'offer' => $offer,
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
        $error = Validator::make($request->all(), [
            'code' => 'required',
            'date' => 'required|date|date_format:Y-m-d',
            'offer_id' => 'required|exists:offer_sales,id',
            'orders.*.product_id' => 'required|exists:products,id',
            'orders.*.amount' => 'required|numeric',
            'orders.*.unit' => 'required',
            'orders.*.price' => 'required',
            'orders.*.total' => 'required',
            'total' => 'required'
        ]);

        if ($error->fails()) {
            return redirect()->back()->withErrors($error);
        }

        try {
            DB::transaction(function () use ($request) {
                $orders = OrderSale::create(array_merge($request->except('orders', 'total'), [
                    'total' => preg_replace('/[^\d.]/', '', $request->total),
                ]));
                $offer = OfferSale::findOrFail($request->offer_id);
                $offer->update([
                    'status' => '1'
                ]);

                foreach ($request->orders as $order) {
                    OrderSaleDetail::create([
                        'order_id' => $orders->id,
                        'product_id' => $order['product_id'],
                        'unit' => $order['unit'],
                        'price' => preg_replace('/[^\d.]/', '', $order['price']),
                        'amount' => $order['amount'],
                        'total' => preg_replace('/[^\d.]/', '', $order['total']),
                    ]);
                }
            });

            return redirect()->route('admin.sales.order.index')->with('success', 'Order Successfully Saved');
        } catch (\Exception $e) {
            return back()->with('error', 'Order not Saved!' . $e->getMessage());
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
        $order = OrderSale::with('order_details.product')->findOrFail($id);

        return view('admin.sales.order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = OrderSale::with('offer:id,code', 'order_details')
            ->findOrFail($id);

        if (empty($order)) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        return view('admin.sales.order.edit', compact('order'));
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

        $error = Validator::make($req, [
            'orders.*.product_id' => 'required|exists:products,id',
            'orders.*.amount' => 'required|numeric',
            'orders.*.unit' => 'required',
            'orders.*.price' => 'required',
            'orders.*.total' => 'required',
            'total' => 'required'
        ]);

        if ($error->fails()) {
            return redirect()->back()->withErrors($error);
        }

        $order = OrderSale::with('offer:id,code', 'order_details')
            ->findOrFail($id);

        if (empty($order)) {
            return redirect()->route('admin.sales.order.index')->with('error', 'No order.');
        }

        try {
            DB::transaction(function () use ($id, $req, $order) {
                $product_id = [];

                foreach ($req['orders'] as $value) {
                    $product_id[] = $value['id'];
                }

                $product_id = array_filter($product_id, fn ($value) => !is_null($value) && $value !== '');

                OrderSaleDetail::where('order_id', $id)
                    ->whereNotIn('id', $product_id)
                    ->delete();

                foreach ($req['orders'] as $item) {
                    if ($item['id'] != null) {
                        OrderSaleDetail::where('id', $item['id'])->update([
                            'product_id' => $item['product_id'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'amount' => $item['amount'],
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    } else {
                        OrderSaleDetail::create([
                            'order_id' => $id,
                            'product_id' => $item['product_id'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'amount' => $item['amount'],
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    }
                }

                $order->update([
                    'total' => preg_replace('/[^\d.]/', '', $req['total'])
                ]);
            });

            return redirect()->route('admin.sales.order.index')->with('success', 'Order successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
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
        $orders = OrderSale::findOrFail($id);
        $orders->delete();

        return redirect()->back()->with('success', 'Order successfully deleted');
    }

    //get sale order
    public function getOrder(){
        
        $group = Group::where('author_id', Auth::user()->id)->first();
        $orders = OrderSale::select('id', 'code')
            ->where('status', '1')
            ->where('group_id', $group->id)
            ->get()
            ->take(5);

        $result = [];

        foreach ($orders as $order) {
            $detail = OrderSaleDetail::select('id', 'order_id', 'product_id', 'unit', 'price', 'amount', 'total')
                ->where('order_id', $order->id)
                ->get();

            $result[] = [
                "id" => $order->id,
                "text" => $order->code,
                "detail" => $detail->toArray(),
            ];
        }
        return $result;
    }
}
