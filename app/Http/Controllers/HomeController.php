<?php

namespace App\Http\Controllers;
use App\Models\Account;
use App\Models\Group;
use App\Models\Member;
use App\Models\PersonalAccount;
use App\Models\Purchase\Invoice;
use App\Models\Purchase\Order;
use App\Models\Sale\InvoiceSale;
use App\Models\Sale\OrderSale;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home(){
         //graph data
		 $monthlyIncome = Account::selectRaw('month(date) as month, sum(amount) as amount')
         ->where('author_id', Auth::user()->id)
		 ->groupBy('month')
		 ->get();

		 //graph data
		 $monthlyRest = PersonalAccount::selectRaw('month(date) as month, sum(amount) as beginning_balance')
         ->where('author_id', Auth::user()->id)
		 ->groupBy('month')
		 ->get();

         //graph data Order Sale
         $group = Group::where('author_id', Auth::user()->id)->first();
         //order sale
		 $orderSale = OrderSale::selectRaw('month(date) as month, sum(total) as amount')
         ->where('group_id', $group->id)
		 ->groupBy('month')
		 ->get();
        //order buy
         $orderBuy = Order::selectRaw('month(date) as month, sum(total) as amount')
         ->where('group_id', $group->id)
		 ->groupBy('month')
		 ->get();
        
         //graph data Invoice Sale
         $invoiceSale = InvoiceSale::selectRaw('month(date) as month, sum(total) as amount')
         ->where('group_id', $group->id)
		 ->groupBy('month')
		 ->get();

         //graph data Invoice Sale
         $invoiceBuy = Invoice::selectRaw('month(date) as month, sum(total) as amount')
         ->where('group_id', $group->id)
		 ->groupBy('month')
		 ->get();

         $orderSale = $this->datahelper($orderSale);
         $orderBuy = $this->datahelper($orderBuy);
         $invoiceSale = $this->datahelper($invoiceSale);
         $invoiceBuy = $this->datahelper($invoiceBuy);


         $months = [];
         $month = [];
         $monthRest = [];
         $monthOrderSale = [];
         $monthValue = [];
         $monthOrderSaleValue = [];
         $monthOrderBuyValue = [];
         $monthInvoiceSaleValue = [];
         $monthInvoiceBuyValue = [];

         $incomes = $this->datahelper($monthlyIncome);
         $income = $this->datahelper($monthlyRest);
		 $months = json_encode($incomes['key']);
         $month = json_encode($income['key']);
         $monthOrderSale = json_encode($orderSale['key']);
		 $monthValue = json_encode($incomes['value']);
         $monthOrderSaleValue = json_encode($orderSale['value']);
         $monthOrderBuyValue = json_encode($orderBuy['value']);
         $monthInvoiceSaleValue = json_encode($invoiceSale['value']);
         $monthInvoiceBuyValue = json_encode($invoiceBuy['value']);

		 $data = $this->datahelper2($monthlyRest);
		 $monthRest = json_encode($data['value']);

        $balanceExpected = Account::where('author_id', Auth::user()->id)->sum('amount');
        $balanceAvailable = PersonalAccount::where('author_id', Auth::user()->id)->sum('amount');
        $balanceRest = ($balanceExpected - $balanceAvailable);
        $invoiceSaleBalance = InvoiceSale::where('group_id', $group->id)->sum('total');
        $invoiceBuyBalance = Invoice::where('group_id', $group->id)->sum('total');

        $genderM = Member::where('gender',"Male")->where('author_id', Auth::user()->id)->get();
        $genderF = Member::where('gender',"Female")->where('author_id', Auth::user()->id)->get();
        $gender = count(Member::where('author_id', Auth::user()->id)->get());

        $genderMNum = count($genderM);
        $genderFNum = count($genderF);
        if($gender > 0){
            $genderMP = ($genderMNum/$gender)*100;
            $genderWP = ($genderFNum/$gender)*100;
        }else{
            $genderMP = 0;
            $genderWP = 0;
        }
         
         
        $memberAgeY = count (Member::where('group_id', $group->id)->where('age', '<', 18)->get());
        $memberAgeV = count (Member::where('group_id', $group->id)->where('age', '>', 18)->get());
        $ageAv = Member::where('author_id', Auth::user()->id)->where('group_id',$group->id)->avg('age');

        if($gender > 0){
            $ageYP = ($memberAgeY/$gender)*100;
            $ageVP = ($memberAgeV/$gender)*100;
        }else{
            $ageYP = 0;
            $ageVP = 0;
        }

        return view('home')->with('monthlyIncome',$monthlyIncome)
		                   ->with('balanceExpected',$balanceExpected)
						   ->with('incomes',$incomes)
                           ->with('orderSale',$orderSale)
						   ->with('months',$months)
                           ->with('month',$month)
                           ->with('monthOrderSale',$monthOrderSale)
						   ->with('monthRest',$monthRest)
						   ->with('monthValue',$monthValue)
                           ->with('monthOrderSaleValue',$monthOrderSaleValue)
                           ->with('monthOrderBuyValue',$monthOrderBuyValue)
                           ->with('monthInvoiceSaleValue',$monthInvoiceSaleValue)
                           ->with('monthInvoiceBuyValue',$monthInvoiceBuyValue)
                           ->with('genderMNum',$genderMNum)
                           ->with('genderFNum',$genderFNum)
                           ->with('genderMP',$genderMP)
                           ->with('genderWP',$genderWP)
                           ->with('group',$group)
                           ->with('balanceAvailable',$balanceAvailable)
                           ->with('invoiceSaleBalance',$invoiceSaleBalance)
                           ->with('invoiceBuyBalance',$invoiceBuyBalance)
                           ->with('balanceRest',$balanceRest)
                           ->with('memberAgeY',$memberAgeY)
                           ->with('memberAgeV',$memberAgeV)
                           ->with('ageYP',$ageYP)
                           ->with('ageVP',$ageVP)
                           ->with('ageAv',$ageAv)
                           ->with('gender',$gender);
    }

    function datahelper($data)
	{
		$DataKey = [];
		$DataVlaue =[];
		foreach ($data as $d) {
			array_push($DataKey,date("F", mktime(0, 0, 0, $d->month, 10)));
			array_push($DataVlaue,$d->amount);

		}
		return ["key"=>$DataKey,"value"=>$DataVlaue];

	}

	function datahelper2($data)
	{
		$DataKey = [];
		$DataVlaue =[];
		foreach ($data as $d) {
			array_push($DataKey,date("F", mktime(0, 0, 0, $d->month, 10)));
			array_push($DataVlaue,$d->beginning_balance);

		}
		return ["key"=>$DataKey,"value"=>$DataVlaue];

	}
}
