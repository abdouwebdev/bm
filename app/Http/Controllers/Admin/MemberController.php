<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Sector;
use App\Models\Department;
use App\Models\Group;
use Carbon\Carbon;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Transformers\StudentTransformer;

class MemberController extends Controller
{
    protected $member;

	public function __construct(Member $member)
	{
		
		$this->member = $member;
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::has('deptId'))
		{
			$group = Group::where('author_id', Auth::user()->id)->first();
			$departments = Department::select('id','name')->where('group_id',$group->id)->orderby('name','asc')->pluck('name', 'id');
			$selectDep = Session::get('deptId');
			$members = Member::where('department_id',$selectDep)->where('author_id',Auth::user()->id)->get();
			return view('admin.member.index',compact('members','departments','selectDep'));
		}
        
		$group = Group::where('author_id', Auth::user()->id)->first();
		$departments = Department::select('id','name')->where('group_id',$group->id)->orderby('name','asc')->pluck('name', 'id');
		$selectDep = "";
		$members = array();
		return view('admin.member.index',compact('members','departments','selectDep'));
    }

    public function department(Request $request){

        Session::put('deptId', $request->department_id);
		$group = Group::where('author_id', Auth::user()->id)->first();
		$departments = Department::select('id','name')->where('group_id',$group->id)->orderby('name','asc')->pluck('name', 'id');
		$selectDep = $request->department_id;
		$members = Member::where('department_id', $selectDep)->get();

		return view('admin.member.index',compact('members','departments','selectDep'));
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
		return view('admin.member.create',compact('departments','sectors','group'));
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
			'idNo' => 'unique:members',
			'session' => 'required',
			'department_id' => 'required',
			'sector_id' => 'required',
			'firstName' => 'required',
			'lastName' => 'required',
			'gender' => 'required',
			'photo' => 'mimes:jpeg,jpg,png',
		];

		$validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        
        if($request->hasFile('photo')){
            $directory = public_path() . "/assets/images/members/";
            $fextention = $data['photo']->getClientOriginalExtension();
            $fileName = str_replace(' ','_',$data['idNo']).'.'.$fextention;
            $data['photo']->move($directory,$fileName);
        
		    $data['photo'] = $fileName;
        }else{
            $data['photo'] = 'image.jpg';
        }
		$data['author_id'] = Auth::user()->id;
        
		$code = rand(1,9999999999999999);
		if(empty($data['idNo'])){
			$data['idNo'] = $code;
		}
		$data['age'] = $data['dob'];
		$member = new Member();
		$member->create($data);
		
        return redirect()->route('admin.member.index')->with('success', 'Member Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
		{

			$member = Member ::with('department')->where('id',$id)->first();
			return view('admin.member.show',compact('member'));
		}
		catch (\Exception $e)
		{
			return Redirect::route('admin.member.index')->with("error","There is no record");
		}
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
			$departments = Department::select('id','name')->orderby('name','asc')->pluck('name', 'id');
			$sectors = Sector::select('id','name')->orderby('name','asc')->pluck('name', 'id');
			$member = Member::findOrFail($id);
			return view('admin.member.edit',compact('departments','sectors','member'));
		}
		catch (\Exception $e)
		{
			return Redirect::route('admin.member.index')->with("error","There is no record");
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
			'session' => 'required',
			'department_id' => 'required',
			'sector_id' => 'required',
			'firstName' => 'required',
			'lastName' => 'required',
			'gender' => 'required'
		];

		$validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

		try {
			$member = Member::findOrFail($id);
			if($request->exists('photo'))
			{

				$directory = public_path() . "/assets/images/members/";
				unlink($directory.$member->photo);
				$fextention = $data['photo']->getClientOriginalExtension();
				$fileName=str_replace(' ','_',$member->idNo).'.'.$fextention;
				$data['photo']->move($directory, $fileName);
				$data['photo'] = $fileName;
			}
			else{
				$data['photo'] = $member->photo;
			}
			$data['department_id'] = $member->department_id;
			$data['session'] = $member->session;
			$data['idNo'] = $member->idNo;
			$member->fill($data)->save();
			return Redirect::route('admin.member.index')->with("success","Member Succesfully Updated");
		}
		catch (\Exception $e)
		{
			return Redirect::route('admin.member.index')->with("error","There is no record.");
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
        $member = Member::findOrFail($id);
		$member->delete();
		return Redirect::route('admin.member.index')->with("success","Member Succesfully Deleted.");
    }

    //Member List
    public function memberList($id, $session){
        
        $members = Member::select('id','firstName','lastName')
		->where('department_id',$id)
		->where('session',$session)
		->where('author_id', Auth::user()->id)
		->get();
		return Response()->json([
			'success' => true,
			'members' => $members
		], 200);
    }

	public function memberGroup(Request $request)
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        $members = Member::select('id', 'firstName', 'lastName')
            ->where('group_id', $group->id)
            ->get();

        $result = [];

		return Response()->json([
			'success' => true,
			'members' => $members
		], 200);

    }

}
