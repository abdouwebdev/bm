<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Group;
use App\Models\Jackpot;
use App\Models\JackpotDetails;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class JackpotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $group = Group::where('author_id', Auth::user()->id)->first();
        $members = Member::select('id','firstName')->orderby('firstName','asc')->pluck('firstName', 'id');
		$jackpots = Jackpot::where('group_id', $group->id)->get();
        $code = rand(1000,9999);
        return view ('admin.jackpot.index',compact('members','jackpots','code','group'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $group = Group::where('author_id', Auth::user()->id)->first();
        $members = Member::select('id','firstName')->orderby('firstName','asc')->pluck('firstName', 'id');
        $code = rand(1000,9999);
		return view('admin.jackpot.create',compact('code','members','group'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
        $data = $request->all();

		$rules = [
			'amount' => 'required',
			'name' => 'required',
            'group_id' => 'required'
		];

		$validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        if($data['member_id'] == NULL){
            $data['member_id'] = Auth::user()->id;
        }

        $jackpot = new Jackpot();
        $jackpot->create($data);
		return Redirect::route('admin.jackpot.index')->with("success","Account Succefully Added");
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
            $group = Group::where('author_id', Auth::user()->id)->first();
            $departments = Department::select('id','name')->where('group_id', $group->id)->orderby('name','asc')->pluck('name', 'id');
            $members = Member::select('id','firstName','lastName')->orderby('firstName','asc')->pluck('firstName', 'lastName');
            $sessions = Member::select('session','session')->distinct()->pluck('session','session');
			$jackpot = Jackpot::findOrFail($id);
			return view('admin.jackpot.edit',compact('jackpot','group','members','departments','sessions'));
		}
		catch (\Exception $e)
		{
			return Redirect::route('admin.jackpot.edit')->with("error",$e->getMessage());
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
        $jackpot = Jackpot::findOrFail($id);
        $group = Group::where('author_id', Auth::user()->id)->first();
        $jds = JackpotDetails::select('member_id','group_id','amount','date')->where('group_id',$group->id)->get();
        return view('admin.jackpot.show',compact('jackpot','jds'));
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
        $data = $request->all();

		$rules = [
			'amount' => 'required',
			'name' => 'required',
            'group_id' => 'required',
            'member_id' => 'required'
		];

		$validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
		else {
			try {
				$jackpot = Jackpot::findOrFail($id);
                JackpotDetails::create([
                    'member_id' => $data['member_id'],
                    'date' => Carbon::now(),
                    'amount' => $data['solde'],
                    'group_id' => $data['group_id'],
                ]);
				$jackpot->fill($data)->save();
				return Redirect::route('admin.jackpot.index')->with("success","Successfully Updated");
			}
			catch (\Exception $e)
			{
				return Redirect::route('admin.jackpot.index')->with("error",$e->getMessage());
			}
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
        $jackpot = Jackpot::findOrFail($id);
        $pa = JackpotDetails::where('member_id',$jackpot->member->id)->get(); 
        foreach($pa as $item){
            $item->delete();
        }   
		$jackpot->delete();
		return Redirect::route('admin.jackpot.index')->with("success","Account Successfully Deleted");
    }
}
