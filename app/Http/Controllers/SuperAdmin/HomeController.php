<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Member;
use App\Models\PersonalAccount;
use App\Models\Purchase\Invoice;
use App\Models\Purchase\Order;
use App\Models\Sale\InvoiceSale;
use App\Models\Sale\OrderSale;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //graph data
		 $monthlyIncome = Account::selectRaw('month(date) as month, sum(amount) as amount')
		 ->groupBy('month')
		 ->get();

		 //graph data
		 $monthlyRest = PersonalAccount::selectRaw('month(date) as month, sum(amount) as beginning_balance')
		 ->groupBy('month')
		 ->get();

         //order sale
		 $orderSale = OrderSale::selectRaw('month(date) as month, sum(total) as amount')
		 ->groupBy('month')
		 ->get();
        //order buy
         $orderBuy = Order::selectRaw('month(date) as month, sum(total) as amount')
		 ->groupBy('month')
		 ->get();
        
         //graph data Invoice Sale
         $invoiceSale = InvoiceSale::selectRaw('month(date) as month, sum(total) as amount')
		 ->groupBy('month')
		 ->get();

         //graph data Invoice Sale
         $invoiceBuy = Invoice::selectRaw('month(date) as month, sum(total) as amount')
		 ->groupBy('month')
		 ->get();

         $orderSale = $this->datahelper($orderSale);
         $orderBuy = $this->datahelper($orderBuy);
         $invoiceSale = $this->datahelper($invoiceSale);
         $invoiceBuy = $this->datahelper($invoiceBuy);


         $months = [];
         $month = [];
         $monthOrderSale = [];
         $monthValue = [];
         $monthOrderSaleValue = [];
         $monthOrderBuyValue = [];
         $monthInvoiceSaleValue = [];
         $monthInvoiceBuyValue = [];

         $incomes = $this->datahelper($monthlyIncome);
         $income = $this->datahelper($monthlyRest);
         $month = json_encode($income['key']);
		 $months = json_encode($incomes['key']);
         $monthOrderSale = json_encode($orderSale['key']);
		 $monthValue = json_encode($incomes['value']);
         $monthOrderSaleValue = json_encode($orderSale['value']);
         $monthOrderBuyValue = json_encode($orderBuy['value']);
         $monthInvoiceSaleValue = json_encode($invoiceSale['value']);
         $monthInvoiceBuyValue = json_encode($invoiceBuy['value']);

		 $data = $this->datahelper2($monthlyRest);
		 $monthRest = json_encode($data['value']);

        $balanceExpected = Account::sum('amount');
        $balanceAvailable = PersonalAccount::sum('amount');
        $balanceRest = ($balanceExpected - $balanceAvailable);
        $invoiceSaleBalance = InvoiceSale::sum('total');
        $invoiceBuyBalance = Invoice::sum('total');

        $genderM = Member::where('gender',"Male")->get();
        $genderF = Member::where('gender',"Female")->get();
        $gender = count(Member::get());

        $genderMNum = count($genderM);
        $genderFNum = count($genderF);
        if($gender > 0){
            $genderMP = ($genderMNum/$gender)*100;
            $genderWP = ($genderFNum/$gender)*100;
        }else{
            $genderMP = 0;
            $genderWP = 0;
        }
         
         
        $memberAgeY = count (Member::where('age', '<', 18)->get());
        $memberAgeV = count (Member::where('age', '>', 18)->get());
        $ageAv = Member::avg('age');

        if($gender > 0){
            $ageYP = ($memberAgeY/$gender)*100;
            $ageVP = ($memberAgeV/$gender)*100;
        }else{
            $ageYP = 0;
            $ageVP = 0;
        }

        return view('superadmin.index')->with('monthlyIncome',$monthlyIncome)
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
