<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactRequest;
use App\Models\{Categorie, Contact, Group};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group = Group::where('author_id', Auth::user()->id)->first();
        $contact = Contact::where('group_id',$group->id)->get();

        return view('admin.contact.index', [
            'countcontact' => count($contact),
            'contact' => $contact
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
        return view('admin.contact.create',compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        $attr = $request->all();
        if (!request('customer')) {
            $attr['customer'] = 0;
        } else {
            $attr['customer'] = 1;
        }
        if (!request('supplier')) {
            $attr['supplier'] = 0;
        } else {
            $attr['supplier'] = 1;
        }
        if (!request('employee')) {
            $attr['employee'] = 0;
        } else {
            $attr['employee'] = 1;
        }
        if (!request('active')) {
            $attr['active'] = 0;
        } else {
            $attr['active'] = 1;
        }
        try {
            Contact::create($attr);
        } catch (\Exception $e) {
            return back()->with('error', 'Data Save Failed!')->withErrors($e->getMessage());
        }
        return redirect()->route('admin.contact.index')->with('success', 'Data Saved Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return view('admin.contact.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        return view('admin.contact.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Contact $contact, ContactRequest $request)
    {
        $attr = $request->all();
        if (!request('customer')) {
            $attr['customer'] = 0;
        } else {
            $attr['customer'] = 1;
        }
        if (!request('supplier')) {
            $attr['supplier'] = 0;
        } else {
            $attr['supplier'] = 1;
        }
        if (!request('employee')) {
            $attr['employee'] = 0;
        } else {
            $attr['employee'] = 1;
        }
        if (!request('client')) {
            $attr['client'] = 0;
        } else {
            $attr['client'] = 1;
        }
        if (!request('officer')) {
            $attr['officer'] = 0;
        } else {
            $attr['officer'] = 1;
        }
        if (!request('active')) {
            $attr['active'] = 0;
        } else {
            $attr['active'] = 1;
        }
        try {
            $contact->update($attr);
        } catch (\Exception $e) {
            return back()->with('error', 'Data Failed to update!')->withErrors($e->getMessage());
        }
        return redirect()->route('admin.contact.index')->with('success', 'Data Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contact.index')
            ->with('success', 'Data Deleted Successfully');
    }

    public function contactCode()
    {
        $name = ltrim(request()->name)[0];
        $contact = Contact::select('id', 'name')
            ->where(DB::raw('LEFT(name, 1)'), $name)
            ->count();

        $letter = ucfirst($name) . "-";

        if ($contact > 0) {
            $amount = $contact + 1;
            $result = $letter . sprintf("%05s", $amount);
        } else {
            $result = $letter . "00001";
        }

        return response()->json(['success' => $result]);
    }
}
