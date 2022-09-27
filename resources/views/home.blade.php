@extends('_layouts.main')
@section('title','Dashboard')
@push('breadcrumb')
<li class="breadcrumb-item">
    @lang('Dashboard')
</li>
@endpush
@section('content')
<div class="col-md-12">
    <img src ="{{asset('img/banner-tr2.jpg')}}" width="1270" class="mb-2"/>
    <div class="card shadow card-statistics">
        <div class="card-header">
            <h4 class="card-title">@lang('Statistics and Analysis (Monthly)')</h4>
            <div class="d-flex align-items-center">
                <p class="card-text font-small-2 mr-25 mb-0"><i class="fa fa-clock"></i> {{ date('Y-m-d') }}</p>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <h4> @lang('You are currently connected to') <strong>{{$group->name}}</strong></h4>
        </div>
        <div class="card-body statistics-body">
            <div class="d-flex">
                <p class="card-text">
                    <span class=" btn btn-outline-success">@lang('Expected Sum'): {{ number_format($balanceExpected, 0, ',', '.') }}</span>
                    <span class=" btn btn-outline-primary">@lang('Amount Available'): {{ number_format($balanceAvailable, 0, ',', '.') }}</span>
                    <span class=" btn btn-outline-danger">@lang('Remaining Amount'): {{ number_format($balanceRest, 0, ',', '.') }}</span>
                </p>
            </div>
            <div class="row">
                <div class="col-md-12 d-none d-lg-block">
                    <div class="panel-body">
                        <canvas id="canvas" height="250" width="600"></canvas>
                    </div>
                </div>
            </div>
            <hr class="py-1">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="text-center mb-2"> @lang('Users Percentage') ({{$gender}}) @lang('Members')</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{asset('img/women.png')}}" width="225" height="225" class="mb-2"/><br>
                            <h3> @lang('Percentage'): {{number_format($genderWP, 0, ',', '')}}% <span class="badge bg-primary">{{$genderFNum}}</span></h3>
                        </div>
                        <div class="col-md-6">
                            <img src="{{asset('img/men.png')}}" width="225" height="225" class="mb-2"/><br>
                            <h3> @lang('Percentage'): {{number_format($genderMP, 0, ',', '')}}% <span class="badge bg-primary">{{$genderMNum}}</span></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="text-center mb-2">@lang('Average User Ages') ({{number_format($ageAv, 0, ',', '')}}) @lang('years')</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{asset('img/young.png')}}" width="225" height="225" class="mb-2"/><br>
                            <h3> @lang('Percentage'): {{number_format($ageYP, 0, ',', '')}}% <span class="badge bg-primary">{{$memberAgeY}}</span></h3>
                        </div>
                        <div class="col-md-6">
                            <img src="{{asset('img/elder.png')}}" width="225" height="225" class="mb-2"/><br>
                            <h3> @lang('Percentage'): {{number_format($ageVP, 0, ',', '')}}% <span class="badge bg-primary">{{$memberAgeV}}</span></h3>
                        </div>
                    </div>
                </div>
            </div><hr>
            <div class="row">
                <div class="d-flex">
                    <p class="card-text mt-2">
                        <span class=" btn btn-outline-success">@lang('Invoice Sale'): {{ number_format($invoiceSaleBalance, 0, ',', '.') }}</span>
                        <span class=" btn btn-outline-primary">@lang('Invoice Purchase'): {{ number_format($invoiceBuyBalance, 0, ',', '.') }}</span>
                    </p>
                </div>
                <div class="col-md-12 d-none d-lg-block">
                    <div class="panel-body">
                        <canvas id="canvas2" height="250" width="600"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/apexcharts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/extensions/ext-component-sweet-alerts.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/charts/chart.min.js') }}"></script>

<script>
    //start month income >
    var months = <?php echo $month; ?>;
    var monthValue = <?php echo $monthValue; ?>;
    var monthRest = <?php echo $monthRest; ?>;
    var barChartData = {
        labels: months,
        datasets: [{
            label: "@lang('Monthly Amount Available')",
            backgroundColor: "rgba(255, 0, 0, 0.3)",
            borderColor: "rgba(3, 88, 106, 0.70)",
            pointBorderColor: "rgba(3, 88, 106, 0.70)",
            pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
            pointHoverBackgroundColor: "#fff",
            pointHoverBorderColor: "rgba(151,187,205,1)",
            pointBorderWidth: 2,
            data: monthRest
        }]
    };
    //end month income

    var monthOrderSale = <?php echo $monthOrderSale; ?>;
    var monthOrderSaleValue = <?php echo $monthOrderSaleValue; ?>;
    var monthOrderBuyValue = <?php echo $monthOrderBuyValue; ?>;
    var monthInvoiceSaleValue = <?php echo $monthInvoiceSaleValue; ?>;
    var monthInvoiceBuyValue = <?php echo $monthInvoiceBuyValue; ?>;
    var barChartDataOrder = {
        labels: monthOrderSale,
        datasets: [{
            label: "@lang('Ordered Sale')",
            borderColor: "#008b8b",
            pointBorderWidth: 2,
            data: monthOrderSaleValue
        },
        {
            label: "@lang('Invoice Sale')",
            borderColor: "#8b008b",
            pointBorderWidth: 2,
            data: monthInvoiceSaleValue
        },
        {
            label: "@lang('Ordered Purchase')",
            borderColor: "#ff8c00",
            pointBorderWidth: 2,
            data: monthOrderBuyValue
        },
        {
            label: "@lang('Invoice Purchase')",
            borderColor: "#228b22	",
            pointBorderWidth: 2,
            data: monthInvoiceBuyValue
        }
        ]
    };

    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'line',
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                    
                },
                responsive: true,
                title: {
                    display: true,
                    text: ''
                }
            }
        });

        var ctxO = document.getElementById("canvas2").getContext("2d");
        window.myBar = new Chart(ctxO, {
            type: 'line',
            data: barChartDataOrder,
        });
    };
     

</script>
@endpush
