<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sale\AccountReceiveSale;
use Illuminate\Http\Request;

class AccountReceivableController extends Controller
{
    public function index()
    {
        $receives = AccountReceiveSale::where('status', '0')->paginate(10);
        
        return view('admin.sales.receivable.index', compact('receives'));
    }
}
