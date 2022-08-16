<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequst;
use App\Models\Category;
use App\Models\Imagesproduct;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        $products = Product::select('id','name', 'price_buy', 'price_sell', 'status')->where('group_id',$group->id)->paginate(5);
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        $categories = Category::select('id','name', 'status')->where('status', '1')->get();
        $units = Unit::select('id', 'name', 'status')->where('status', '1')->get();
        
        return view('admin.product.create', compact('categories','units','group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequst $request)
    {
        $products = Product::create([
            'name' => $request->name,
            'price_sell' => $request->price_sell,
            'price_buy' => $request->price_buy,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'group_id' => $request->group_id
        ]);

        if($request->hasFile('image')){
            $file = $request->image;
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $fileName . '_' . time() . '.' . $file->extension();

            $file->storeAs('public/images/product', $fileName);

            $photo = $fileName;
            Imagesproduct::create([
                'product_id' => $products->id,
                'image' => $photo
            ]);
        }
        return redirect()->route('admin.product.index')->with('success', 'Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('unit', 'category', 'supplier')->findOrFail($id);
        $images = Imagesproduct::select('id', 'images', 'product_id')->where('product_id', $id);

        return view('admin.product.show', compact('product', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::select('id','name', 'status')->where('status', '1')->get();
        $suppliers = Contact::select('id','supplier', 'name')->where('supplier', true)->get();
        $units = Unit::select('id', 'name', 'status')->where('status', '1')->get();

        $product = Product::findOrFail($id);
        $images = Imagesproduct::select('id', 'image', 'product_id')->where('product_id', $id)->get();

        return view('admin.product.edit', compact('product', 'images', 'categories', 'suppliers', 'units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequst $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('admin.product.index')->with('success', 'Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Successfully Deleted');
    }

    public function getProduct(Request $request)
    {
        $search = $request->search;
        $group = Group::where('author_id', Auth::user()->id)->first();
        $products = Product::select('id', 'unit_id', 'name', 'price_sell', 'status')
            ->with('unit')
            ->where('group_id', $group->id)
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

    public function getProductBuy(Request $request){

        $search = $request->search;
        $group = Group::where('author_id', Auth::user()->id)->first();
        $products = Product::select('id', 'unit_id', 'name', 'price_buy', 'status')
            ->with('unit:id,name,status')
            ->where('group_id', $group->id)
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
}
