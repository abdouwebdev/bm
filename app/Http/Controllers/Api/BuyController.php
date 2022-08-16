<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Purchase\Invoice;
use App\Models\Purchase\InvoiceDetail;
use App\Models\Purchase\Offer;
use App\Models\Purchase\OfferDetail;
use App\Models\Purchase\DeliveryDetail;
use App\Models\Purchase\Order;
use App\Models\Purchase\OrderDetail;
use Illuminate\Http\Request;

class BuyController extends Controller
{
    public function getProduct(Request $request)
    {
        $search = $request->search;

        $products = Product::select('id', 'unit_id', 'name', 'price_buy', 'status')
            ->with('unit:id,name,status')
            ->where('status', '1')
            ->orWhere('name', 'like', "%{$search}%")
            ->get()
            ->take(5);

        $result = [];

        foreach ($products as $product) {
            $result[] = [
                "id" => $product->id,
                "text" => $product->name,
                "unit" => $product->unit->name,
                "price_buy" => $product->price_buy,
            ];
        }

        return $result;
    }

    public function selectedProduct(Product $product)
    {
        return response()->json([
            "id" => $product->id,
            "text" => $product->name,
            "unit" => $product->unit->name,
            "price_buy" => $product->price_buy,
        ]);
    }
    public function getSupplier(Request $request)
    {
        $search = $request->search;
        $contacts = Contact::select('id', 'name', 'email', 'nik', 'phone', 'supplier')
            ->where('supplier', 1)
            ->where(function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })->orderBy('name')->get()->take(20);

        $result = [];

        foreach ($contacts as $contact) {
            $result[] = [
                "id" => $contact->id,
                "text" => $contact->name,
                "name" => $contact->name,
                "email" => $contact->email,
                "phone" => $contact->phone
            ];
        }

        return $result;
    }

    public function supplierSelected(Contact $contact)
    {
        return [
            "id" => $contact->id,
            "text" => $contact->name,
            "name" => $contact->name,
            "email" => $contact->email,
            "phone" => $contact->phone
        ];
    }

    public function getOffer(Request $request)
    {
        $search = $request->search;

        $offers = Offer::select('id', 'code', 'supplier_id', 'total')
            ->with('supplier:id,name')
            ->where('status', '1')
            ->orWhere('code', 'like', "%{$search}%")
            ->get()
            ->take(5);

        $result = [];

        foreach ($offers as $offer) {
            $detail = OfferDetail::select('id', 'offer_id', 'product_id', 'amount', 'price', 'unit', 'total')
                ->where('offer_id', $offer->id)
                ->get();
            $result[] = [
                "id" => $offer->id,
                "text" => $offer->code,
                "total" => $offer->total,
                "supplier" => $offer->supplier->name,
                "detail" => $detail,
            ];
        }

        return response()->json($result);
    }

    public function getOrder(Request $request)
    {
        $search = $request->search;

        $orders = Order::select('id', 'code', 'supplier_id')
            ->with('supplier:id,name')
            ->where('status', '1')
            ->orWhere('code', 'like', "%{$search}%")
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
                "supplier" => $order->supplier->name,
                "detail" => $detail->toArray(),
            ];
        }
        return $result;
    }

    public function getInvoice(Request $request)
    {
        $search = $request->search;
        $invoices = Invoice::select('id', 'code', 'total')
            ->where('status', '0')
            ->where('code', 'like', "%{$search}%")
            ->get()->take(20);

        $result = [];

        foreach ($invoices as $invoice) {
            $result[] = [
                "id" => $invoice->id,
                "text" => $invoice->code,
                "total" => $invoice->total,
                "code" => $invoice->code,
            ];
        }

        return $result;
    }


    public function getOfferDetails($offer_id)
    {
        $details = OfferDetail::select('id', 'offer_id', 'product_id', 'unit', 'price', 'amount', 'total')
            ->where('offer_id', $offer_id)->get();

        if ($details->count() == 0) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Success get offer_details data',
            'data' => $details,
            'length' => $details->count()
        ]);
    }

    public function getOrderDetails($order_id)
    {
        $details = OrderDetail::select('id', 'order_id', 'product_id', 'amount', 'unit', 'price', 'total')
            ->where('order_id', $order_id)->with('product')->get();

        if ($details->count() == 0) {
            return response()->json([
                'message' => 'detail not found',
                'data' => [],
                'length' => 0
            ], 404);
        }

        return response()->json([
            'message' => "Success get order_details data",
            'data' => $details,
            'length' => $details->count()
        ]);
    }

    public function getInvoiceDetails($invoice_id)
    {
        $details = InvoiceDetail::select('id', 'invoice_id', 'product_id', 'amount', 'unit', 'price', 'total')
            ->where('invoice_id', $invoice_id)->get();

        if ($details->count() == 0) {
            return response()->json([
                'message' => 'detail not found',
                'data' => [],
                'length' => 0
            ], 404);
        }

        return response()->json([
            'message' => "Success get invoice_details data",
            'data' => $details,
            'length' => $details->count()
        ]);
    }

    public function getAccountSupply(Request $request)
    {
        $search = $request->search;
        $page = $request->page;
        $result_count = 10;
        $offset = ($page - 1) * $result_count;

        $accounts = Account::active()->select('id', 'name', 'code')
            ->where('status','=', '1') // NGIDE
            ->where(function ($q) use ($search) {
                return $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('name', 'ASC')->skip($offset)->take($result_count)->get();

        $endCount = $offset + $result_count;
        $morePages = Account::active()->count() > $endCount;
        $data = [];

        foreach ($accounts as $account) {
            $data[] = [
                "id" => $account->id,
                "text" => $account->name,
                "name" => $account->name,
                "code" => $account->ode,
            ];
        }

        $result = [
            'results' => $data,
            'pagination' => [
                'more' => $morePages
            ]
        ];

        return $result;
    }
}
