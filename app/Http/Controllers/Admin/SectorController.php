<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        $sectors = Sector::where('group_id',$group->id)->orderby('name','asc')->get();
        return view('admin.sector.index',compact('sectors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        $code = rand(1000,9999);
        return view('admin.sector.create',compact('code','group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

		$rules=[
			'name' => 'required',
			'group_id' => 'required',
		];

		$validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        $sector = new Sector();
		$sector->create($data);
		
        return redirect()->route('admin.sector.index')->with('success', 'Sector Successfully Added');
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
        try
		{
			$sector = Sector::findOrFail($id);
			return view('admin.sector.edit',compact('sector'));
		}
		catch (\Exception $e)
		{
			return Redirect::route('admin.sector.index')->with("error","There is no record");
		}
        return view('admin.sector.edit');
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
        $sector = Sector::findOrFail($id);
        $sector->update($request->all());

        return redirect()->route('admin.sector.index')->with('success', 'Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sector = Sector::findOrFail($id);
        $sector->delete();

        return redirect()->route('admin.sector.index')->with('success', 'Successfully Deleted');
    }
}
