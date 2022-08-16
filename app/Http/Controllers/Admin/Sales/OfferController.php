<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Product;
use App\Models\Sale\OfferSale;
use App\Models\Sale\OfferSaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    private $code;

    public function __construct()
    {
        $number = OfferSale::count();
        if ($number > 0) {
            $number = OfferSale::max('code');
            $strnum = (int)substr($number, 2, 3);
            $strnum = $strnum + 1;
            if (strlen($strnum) == 3) {
                $code = 'OF' . $strnum;
            } else if (strlen($strnum) == 2) {
                $code = 'OF' . "0" . $strnum;
            } else if (strlen($strnum) == 1) {
                $code = 'OF' . "00" . $strnum;
            }
        } else {
            $code = 'OF' . "001";
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
        $offers = OfferSale::select('id', 'date', 'code', 'total', 'status')
        ->where('group_id', $group->id);
        return view('admin.sales.offer.index', [
            'offers' => $offers->paginate(5),
            'countOffers' => $offers->count(),
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
        $products = Product::select('id','name')->where('group_id',$group->id)->orderby('name','asc')->pluck('name', 'id');
        return view('admin.sales.offer.create', [
            'code' => $this->code,
            'group' => $group,
            'products' => $products
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
            'offers.*.product_id' => 'required|exists:products,id',
            'offers.*.amount' => 'required|numeric',
            'offers.*.unit' => 'required',
            'offers.*.price' => 'required',
            'offers.*.total' => 'required',
            'total' => 'required'
        ]);

        if ($error->fails()) {
            return redirect()->back()->withErrors($error);
        }

        try {
            DB::transaction(function () use ($request) {
                $offer  = OfferSale::create(array_merge($request->except('offers', 'total'), [
                    'total' => preg_replace('/[^\d.]/', '', $request->total),
                ]));

                foreach ($request->offers as $detail) {
                    unset($detail['id']); // Hapus elemen gak kepake

                    OfferSaleDetail::create([
                        'offer_id' => $offer->id,
                        'product_id' => $detail['product_id'],
                        'unit' => $detail['unit'],
                        'price' => preg_replace('/[^\d.]/', '', $detail['price']),
                        'amount' => $detail['amount'],
                        'total' => preg_replace('/[^\d.]/', '', $detail['total']),
                    ]);
                }
            });

            return redirect()->route('admin.sales.offer.index')->with('success', 'L\'offre a ete bien enregistre');
        } catch (\Exception $e) {
            return back()->with('error', 'Fail to record!' . $e->getMessage());
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
        $offer = OfferSale::with('offer_details.product')->findOrFail($id);

        return view('admin.sales.offer.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OfferSale $offer)
    {
        $offer_details = OfferSaleDetail::where('offer_id', $offer->id)->get();
        return view('admin.sales.offer.edit', compact('offer', 'offer_details'));
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
            'date' => 'required|date|date_format:Y-m-d',
            'offers.*.product_id' => 'required|exists:products,id',
            'offers.*.amount' => 'required|numeric',
            'offers.*.unit' => 'required',
            'offers.*.price' => 'required',
            'offers.*.total' => 'required',
            'total' => 'required'
        ]);

        if ($error->fails()) {
            return redirect()->back()->withErrors($error);
        }

        try {
            DB::transaction(function () use ($request, $req, $id) {
                $offer = OfferSale::find($id);

                if (empty($offer)) {
                    return redirect()->route('admin.sales.offer.index')
                        ->with('error', "Offer not found");
                }

                $offer->update(
                    array_merge($request->except('_token', '_method', 'offers', 'total'), [
                        'total' => preg_replace('/[^\d.]/', '', $request->total),
                    ])
                );

                $product_id = [];

                foreach ($req['offers'] as $value) {
                    $product_id[] = $value['id'];
                }

                $offers = array_values(array_filter($product_id, fn ($value) => is_null($value) && $value == ''));
                $product_id = array_filter($product_id, fn ($value) => !is_null($value) && $value !== '');

                OfferSaleDetail::exclude(['created_at', 'updated_at'])
                    ->where('offer_id', $id)
                    ->whereNotIn('id', $product_id)
                    ->delete();

                foreach ($req['offers'] as $item) {
                    if ($item['id'] != null) {
                        OfferSaleDetail::where('id', $item['id'])->update([
                            'product_id' => $item['product_id'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'amount' => $item['amount'],
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    } else {
                        OfferSaleDetail::create([
                            'offer_id' => $id,
                            'product_id' => $item['product_id'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'amount' => $item['amount'],
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    }
                }
            });

            return redirect()->route('admin.sales.offer.index')->with('success', 'Offer update successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Offer fail to update" . $e->getMessage());
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
        $offers = OfferSale::findOrFail($id);
        $offers->delete();

        return redirect()->back()->with('success', 'Destroy Successfully');
    }
}
