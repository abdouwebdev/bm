<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Group;
use App\Models\Member;
use App\Models\PersonalAccount;
use App\Models\Purchase\Invoice;
use App\Models\Purchase\Order;
use App\Models\Sale\InvoiceSale;
use App\Models\Sale\OrderSale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){

         //graph data
		 $monthlyIncome = Account::selectRaw('month(date) as month, sum(amount) as amount')
         ->where('group_id', Auth::user()->group->id)
		 ->groupBy('month')
		 ->get();

		 //graph data
		 $monthlyRest = PersonalAccount::selectRaw('month(date) as month, sum(amount) as beginning_balance')
         ->where('group_id', Auth::user()->group->id)
		 ->groupBy('month')
		 ->get();

         //order sale
		 $orderSale = OrderSale::selectRaw('month(date) as month, sum(total) as amount')
         ->where('group_id', Auth::user()->group->id)
		 ->groupBy('month')
		 ->get();
        //order buy
         $orderBuy = Order::selectRaw('month(date) as month, sum(total) as amount')
         ->where('group_id', Auth::user()->group->id)
		 ->groupBy('month')
		 ->get();
        
         //graph data Invoice Sale
         $invoiceSale = InvoiceSale::selectRaw('month(date) as month, sum(total) as amount')
         ->where('group_id', Auth::user()->group->id)
		 ->groupBy('month')
		 ->get();

         //graph data Invoice Sale
         $invoiceBuy = Invoice::selectRaw('month(date) as month, sum(total) as amount')
         ->where('group_id', Auth::user()->group->id)
		 ->groupBy('month')
		 ->get();

         $orderSale = $this->datahelper($orderSale);
         $orderBuy = $this->datahelper($orderBuy);
         $invoiceSale = $this->datahelper($invoiceSale);
         $invoiceBuy = $this->datahelper($invoiceBuy);


        $balanceExpected = Account::where('group_id', Auth::user()->group->id)->sum('amount');
        $balanceAvailable = PersonalAccount::where('group_id', Auth::user()->group->id)->sum('amount');
        $balanceRest = ($balanceExpected - $balanceAvailable);

        $months = [];
        $month = [];
        $monthValue = [];
        $monthOrderSale = [];
        $monthOrderSaleValue = [];
        $monthOrderBuyValue = [];
        $monthInvoiceSaleValue = [];
        $monthInvoiceBuyValue = [];

        $incomes = $this->datahelper($monthlyIncome);
        $income = $this->datahelper($monthlyRest);
		$months = json_encode($incomes['key']);
        $month = json_encode($income['key']);
        $monthValue = json_encode($incomes['value']);
        $monthOrderSale = json_encode($orderSale['key']);
        $monthOrderSaleValue = json_encode($orderSale['value']);
        $monthOrderBuyValue = json_encode($orderBuy['value']);
        $monthInvoiceSaleValue = json_encode($invoiceSale['value']);
        $monthInvoiceBuyValue = json_encode($invoiceBuy['value']);

        $data = $this->datahelper2($monthlyRest);
		$monthRest = json_encode($data['value']);

        $genderM = Member::where('gender',"Male")->where('group_id',Auth::user()->group->id)->get();
        $genderF = Member::where('gender',"Female")->where('group_id', Auth::user()->group->id)->get();
        $gender = count(Member::where('group_id', Auth::user()->group->id)->get());

        $genderMNum = count($genderM);
        $genderFNum = count($genderF);
        if($gender > 0){
            $genderMP = ($genderMNum/$gender)*100;
            $genderWP = ($genderFNum/$gender)*100;
        }else{
            $genderMP = 0;
            $genderWP = 0;
        }
         
         
        $memberAgeY = count (Member::where('group_id', Auth::user()->group->id)->where('age', '<', 18)->get());
        $memberAgeV = count (Member::where('group_id', Auth::user()->group->id)->where('age', '>', 18)->get());
        $ageAv = Member::where('group_id',Auth::user()->group->id)->avg('age');

        if($gender > 0){
            $ageYP = ($memberAgeY/$gender)*100;
            $ageVP = ($memberAgeV/$gender)*100;
        }else{
            $ageYP = 0;
            $ageVP = 0;
        }

        return view('user.index',[
            'balanceAvailable' => $balanceAvailable,
            'balanceRest' => $balanceRest,
            'balanceExpected' => $balanceExpected,
            'months' => $months,
            'month' => $month,
            'monthRest' => $monthRest,
            'monthValue' => $monthValue,
            'monthOrderSale' => $monthOrderSale,
            'monthOrderSaleValue' => $monthOrderSaleValue,
            'monthOrderBuyValue' => $monthOrderBuyValue,
            'monthInvoiceSaleValue' => $monthInvoiceSaleValue,
            'monthInvoiceBuyValue' => $monthInvoiceBuyValue,
            'gender' => $gender,
            'genderMP' => $genderMP,
            'genderWP' => $genderWP,
            'genderMNum' => $genderMNum,
            'genderFNum' => $genderFNum,
            'memberAgeY' => $memberAgeY,
            'memberAgeV' => $memberAgeV,
            'ageYP' =>$ageYP,
            'ageVP' => $ageVP,
            'ageAv' => $ageAv
        ]);
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
