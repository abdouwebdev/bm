<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Product;
use App\Models\Sale\InvoiceSale;
use App\Models\Sale\InvoiceSaleDetail;
use App\Models\Sale\OfferSale;
use App\Models\Sale\OfferSaleDetail;
use App\Models\Sale\OrderSale;
use App\Models\Sale\OrderSaleDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function getCustomer(Request $request)
    {
        $search = $request->search;

        $contacts = Contact::select('id', 'name', 'email', 'nik', 'phone', 'customer','group_id')
            ->where('customer', 1)
            ->where('group_id', 1)
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

    public function customerSelected(Contact $contact)
    {
        return [
            "id" => $contact->id,
            "text" => $contact->name,
            "name" => $contact->name,
            "email" => $contact->email,
            "phone" => $contact->phone
        ];
    }

    public function getProduct(Request $request)
    {
        $search = $request->search;
        $group = Group::where('author_id', Auth::user()->id)->first();
        $products = Product::select('id', 'unit_id', 'name', 'price_sell', 'status')
            ->with('unit')
            //->where('group_id', $group->id)
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
                "price_sell" => $product->price_sell,
            ];
        }

        return $result;
    }

    public function getOffer(Request $request)
    {
        $search = $request->search;

        $offers = OfferSale::select('id', 'code', 'total')
            ->where('status', '1')
            ->orWhere('code', 'like', "%{$search}%")
            ->get()
            ->take(5);

        $result = [];

        foreach ($offers as $offer) {
            $detail = OfferSaleDetail::select('id', 'offer_id', 'product_id', 'amount', 'unit', 'price', 'total')
                ->where('offer_id', $offer->id)
                ->get();
            $result[] = [
                "id" => $offer->id,
                "text" => $offer->code,
                "total" => $offer->total,
                "detail" => $detail->toArray(),
            ];
        }

        return $result;
    }

    public function getOrder(Request $request)
    {
        $search = $request->search;

        $orders = OrderSale::select('id', 'code', 'customer_id')
            ->with('customer:id,name')
            ->where('status', '1')
            ->orWhere('code', 'like', "%{$search}%")
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
                "customer" => $order->customer->name,
                "detail" => $detail->toArray(),
            ];
        }
        return $result;
    }

    public function getInvoiceDetails($invoice_id)
    {
        $details = InvoiceSaleDetail::select('id', 'invoice_id', 'product_id', 'unit', 'price', 'amount', 'total')
            ->where('invoice_id', $invoice_id)->get();

        if ($details->count() == 0) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Success get invoice_details data',
            'data' => $details,
            'length' => $details->count()
        ]);
    }

    public function getAccount(Request $request)
    {
        $search = $request->search;
        $accounts = Account::select('id', 'name', 'kode')
            ->where('name', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%")
            ->orderBy('name', 'ASC')->get()->take(20);

        $result = [];

        foreach ($accounts as $account) {
            $result[] = [
                "id" => $account->id,
                "text" => $account->name,
                "name" => $account->name,
                "code" => $account->code,
            ];
        }

        return $result;
    }

    public function getAccountSale(Request $request)
    {
        $search = $request->search;
        $page = $request->page;
        $result_count = 10;
        $offset = ($page - 1) * $result_count;

        $accounts = Account::active()->select('id', 'name', 'code')
            ->where('level', 'Income')
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
                "code" => $account->code,
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

    public function getInvoice(Request $request)
    {
        $search = $request->search;
        $invoices = InvoiceSale::select('id', 'code', 'total')
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

    /**
     * Selected data
     */

    public function selectedProduct(Product $product)
    {
        return response()->json([
            "id" => $product->id,
            "text" => $product->name,
            "unit" => $product->unit->name,
            "price_sell" => $product->price_sell,
        ]);
    }
}
