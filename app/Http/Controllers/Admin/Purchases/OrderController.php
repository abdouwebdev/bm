<?php

namespace App\Http\Controllers\Admin\Purchases;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Product;
use App\Models\Purchase\Order;
use App\Models\Purchase\Offer;
use App\Models\Purchase\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $code;

    public function __construct()
    {
        $number = Order::count();
        if ($number > 0) {
            $number = Order::max('code');
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
        $orders = Order::select('id', 'date', 'code', 'total', 'status')
        ->where('group_id', $group->id);
        return view('admin.purchase.order.index', [
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
        $offer = Offer::count() >= 1 ? true : false;
        return view('admin.purchase.order.create', [
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
            'offer_id' => 'required|exists:offers,id',
            'code' => 'required',
            'date' => 'required|date|date_format:Y-m-d',
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
                $order = Order::create(array_merge($request->except('orders', 'total'), [
                    'total' => preg_replace('/[^\d.]/', '', $request->total),
                ]));
                $offer = Offer::findOrFail($request->offer_id);
                $offer->update([
                    'status' => '0'
                ]);

                foreach ($request->orders as $detail) {
                    unset($detail['id']); // Remove useless elements

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $detail['product_id'],
                        'unit' => $detail['unit'],
                        'price' => preg_replace('/[^\d.]/', '', $detail['price']),
                        'amount' => $detail['amount'],
                        'total' => preg_replace('/[^\d.]/', '', $detail['total']),
                    ]);
                }
            });

            return redirect()->route('admin.purchase.order.index')->with('success', 'Order Successfully Saved');
        } catch (\Exception $e) {
            return back()->with('error', 'Unsaved Offers!' . $e->getMessage());
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
        $order = Order::with('order_details.product')->findOrFail($id);
        return view('admin.purchase.order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::with('offer:id,code')
            ->find($id);

        if (empty($order)) {
            return redirect()->route('admin.purchase.order.index')
                ->with('error', 'Order not found');
        }

        return view('admin.purchase.order.edit', compact('order'));
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
        $validation = Validator::make($request->all(), [
            'orders.*.product_id' => 'required|exists:products,id',
            'orders.*.amount' => 'required|numeric',
            'orders.*.unit' => 'required',
            'orders.*.price' => 'required',
            'orders.*.total' => 'required',
            'total' => 'required'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        }

        $order = Order::find($id);
        if (empty($order)) {
            return redirect()->route('admin.purchase.order.index')
                ->with('error', 'Order not found');
        }

        try {
            DB::transaction(function () use ($request, $id, $order) {
                $detail_id = [];

                foreach ($request->orders as $value) {
                    $detail_id[] = $value['id'];
                }

                $detail_id = array_filter($detail_id, fn ($value) => !is_null($value) && $value !== '');
                OrderDetail::where('order_id', $id)
                    ->whereNotIn('id', $detail_id)
                    ->delete();

                foreach ($request->orders as $item) {
                    if ($item['id'] != null) {
                        OrderDetail::where('id', $item['id'])->update([
                            'product_id' => $item['product_id'],
                            'amount' => $item['amount'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    } else {
                        OrderDetail::create([
                            'order_id' => $id,
                            'product_id' => $item['product_id'],
                            'amount' => $item['amount'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    }
                }

                $order->update(array_merge($request->except('orders', 'total'), [
                    'total' => preg_replace('/[^\d.]/', '', $request->total)
                ]));
            });

            return redirect()->route('admin.purchase.order.index')
                ->with('success', 'Order Succesfully Edited');
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
        $orders = Order::findOrFail($id);
        $orders->delete();

        return redirect()->back()->with('success', 'Order Succesfully Deleted');
    }

    //get order buy
    public function getOrderBuy(Request $request){

        $search = $request->search;
        $group = Group::where('author_id', Auth::user()->id)->first();
        $orders = Order::select('id', 'code')
            ->where('status', '1')
            ->where('group_id', $group->id)
            ->get()
            ->take(5);

        $result = [];

        foreach ($orders as $order) {
            $detail = OrderDetail::select('id', 'order_id', 'product_id', 'unit', 'price', 'amount', 'total')
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
