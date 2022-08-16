<?php

namespace App\Http\Controllers\Admin\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Purchase\Offer;
use App\Models\Purchase\OfferDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class OfferController extends Controller
{
    private $code;

    public function __construct()
    {
        $number = Offer::count();
        if ($number > 0) {
            $number = Offer::max('code');
            $strnum = substr($number, 2, 3);
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
        $offers = Offer::select('id', 'date', 'code', 'total', 'status')
        ->where('group_id', $group->id);
        return view('admin.purchase.offer.index', [
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
        $code = $this->code;
        return view('admin.purchase.offer.create', [
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
                $offer = Offer::create(array_merge($request->except('offers', 'total'), [
                    'total' => preg_replace('/[^\d.]/', '', $request->total),
                ]));

                foreach ($request->offers as $detail) {
                    unset($detail['id']); // Remove useless elements

                    OfferDetail::create([
                        'offer_id' => $offer->id,
                        'product_id' => $detail['product_id'],
                        'unit' => $detail['unit'],
                        'price' => preg_replace('/[^\d.]/', '', $detail['price']),
                        'amount' => $detail['amount'],
                        'total' => preg_replace('/[^\d.]/', '', $detail['total']),
                    ]);
                }
            });

            return redirect()->route('admin.purchase.offer.index')->with('success', 'Successful Offer Saved');
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
        $offer = Offer::with('offer_details.product')->findOrFail($id);

        return view('admin.purchase.offer.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $offer = Offer::find($id);

        if (empty($offer)) {
            return redirect()->route('admin.purchase.offer.index')
                ->with('error', 'Offer not found');
        }

        return view('admin.purchase.offer.edit', compact('offer'));
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
        $validate = Validator::make($request->all(), [
            'offers.*.product_id' => 'required|exists:products,id',
            'offers.*.amount' => 'required|numeric',
            'offers.*.unit' => 'required',
            'offers.*.price' => 'required',
            'offers.*.total' => 'required',
            'total' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        $detail_id = [];

        foreach ($request->offers as $value) {
            $detail_id[] = $value['id'];
        }

        $detail_id = array_filter($detail_id, fn ($value) => !is_null($value) && $value !== '');

        try {
            DB::transaction(function () use ($request, $id, $detail_id) {
                $offer = Offer::find($id);

                if (empty($offer)) {
                    return redirect()->route('admin.purchase.offer.index')
                        ->with('error', 'Offer not found.');
                }

               OfferDetail::where('offer_id', $id)
                    ->whereNotIn('id', $detail_id)
                    ->delete();

                foreach ($request->offers as $item) {
                    if ($item['id'] != null) {
                        OfferDetail::where('id', $item['id'])->update([
                            'product_id' => $item['product_id'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'amount' => $item['amount'],
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    } else {
                        OfferDetail::create([
                            'offer_id' => $id,
                            'product_id' => $item['product_id'],
                            'unit' => $item['unit'],
                            'price' => preg_replace('/[^\d.]/', '', $item['price']),
                            'amount' => $item['amount'],
                            'total' => preg_replace('/[^\d.]/', '', $item['total']),
                        ]);
                    }
                }

                $offer->update(array_merge($request->except('offers', 'total'), [
                    'total' => preg_replace('/[^\d.]/', '', $request->total)
                ]));
            });

            return redirect()->route('admin.purchase.offer.index')
                ->with('success', 'Offer edited successfully.');
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
        $offers = OfferDetail::findOrFail($id);
        $offers->delete();

        return redirect()->back()->with('success', 'Offer successfully Removed');
    }

    //get offer
    public function getOfferBuy(Request $request){

        $search = $request->search;
        $group = Group::where('author_id', Auth::user()->id)->first();
        $offers = Offer::select('id', 'code', 'total')
            ->where('status', '1')
            ->where('group_id', $group->id)
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
                "detail" => $detail,
            ];
        }

        return response()->json($result);
    }
}
