<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Account;
use App\Models\Group;
use App\Models\Member;
use App\Models\PersonalAccount;
use App\Models\Sector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccountController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        $departments = Department::select('id','name')->where('group_id',$group->id)->orderby('name','asc')->pluck('name', 'id');
        $sectors = Sector::select('id','name')->where('group_id',$group->id)->orderby('name','asc')->pluck('name', 'id');
        $members = Member::select('id','firstName')->orderby('firstName','asc')->pluck('firstName', 'id');
		$sessions = Member::select('session','session')->distinct()->pluck('session','session');
		$accounts = Account::where('group_id', $group->id)->get();
        $accountList = count($accounts);
        $code = rand(1000,9999);
        return view('admin.account.index',compact('departments','sectors','accountList','accounts','members','sessions','code','group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        $departments = Department::select('id','name')->where('group_id',$group->id)->orderby('name','asc')->pluck('name', 'id');
        $sectors = Sector::select('id','name')->where('group_id',$group->id)->orderby('name','asc')->pluck('name', 'id');
        $members = Member::select('id','firstName')->orderby('firstName','asc')->pluck('firstName', 'id');
		$sessions = Member::select('session','session')->distinct()->pluck('session','session');
		return view('admin.account.create',compact('departments','sectors','members','sessions','group'));
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

		$rules = [
			'amount' => 'required',
			'department_id' => 'required',
            'sector_id' => 'required'
		];

		$validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
	
		$data['author_id'] = Auth::user()->id;
		$account = new Account();
        PersonalAccount::create([
            'member_id' => $data['member_id'],
            'date' => $data['date'],
            'amount' => $data['beginning_balance'],
            'author_id' => auth()->user()->id,
            'group_id' => $data['group_id'],
        ]);
		$account->create($data);
		return Redirect::route('admin.account.index')->with("success","Account Succefully Added");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Account::findOrFail($id);
        $pa = PersonalAccount::where('member_id',$account->member->id)->get();
        return view('admin.account.show',compact('account','pa'));
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
            $sectors = Sector::select('id','name')->where('group_id',$group->id)->orderby('name','asc')->pluck('name', 'id');
            $members = Member::select('id','firstName')->orderby('firstName','asc')->pluck('firstName', 'id');
			$account = Account::findOrFail($id);
            $accounts = Account::all();
			return view('admin.account.edit',compact('departments', 'group','sectors','account','accounts','members'));
		}
		catch (\Exception $e)
		{
			return Redirect::route('admin.account.edit')->with("error",$e->getMessage());
		}
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
			'department_id' => 'required',
            'sector_id' => 'required'
		];

		$validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
		else {
			try {
				$account = Account::findOrFail($id);
                PersonalAccount::create([
                    'member_id' => $data['member_id'],
                    'date' => $data['date'],
                    'amount' => $data['beginning_balance'],
                    'author_id' => auth()->user()->id,
                    'group_id' => $data['group_id'],
                ]);
				$account->fill($data)->save();
				return Redirect::route('admin.account.index')->with("success","Successfully Updated");
			}
			catch (\Exception $e)
			{
				return Redirect::route('admin.account.index')->with("error",$e->getMessage());
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
        $account = Account::findOrFail($id);

        $pa = PersonalAccount::where('member_id',$account->member->id)->get(); 
        foreach($pa as $item){
            $item->delete();
        }       
		$account->delete();
		return Redirect::route('admin.account.index')->with("success","Account Successfully Deleted");
    }
}
