@extends('_layouts.main')
@section('title', 'Account')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{route('admin.account.index')}}">@lang('Accounts')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Show') @lang('Accounts')</li>
@endpush
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap.min.css') }}" >
<link rel="stylesheet" href="{{ asset('assets/css/buttons.bootstrap.min.css') }}" >
@section('content')
<div class="container">
    <div class="row card">
        <h4 class="card-header text-center">@lang('Personal Account')</h4>
        <div class="col-md-12">
            <div class="card-body">
                <div class="table-responsive">
                    <table  id="dtable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 1px;">@lang('Code')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Department')</th>
                                <th>@lang('Sector')</th>
                                <th>@lang('Total Amount')</th>
                                <th>@lang('Personal Solde')</th>
                                <th>@lang('Remaining amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge badge-success">{{ $account->code }}</span></td>
                                <td>{{ $account->member->firstName }} - {{ $account->member->lastName }}</td>
                                <td>{{ $account->department->name }}</td>
                                <td>{{ $account->sector->name }}</td>
                                <td>{{ number_format($account->amount, 0, ',', ' ') }}</td>
                                <td>
                                    @foreach ($pa as $item)
                                        <li style="list-style: none;" class="mt-2">
                                             {{$item->date}} =>  {{$item->amount}}<hr>
                                        </li>
                                    @endforeach
                                </td>
                                <td>
                                    @php 
                                        $remain = ($account->amount) - ($account->member->personalaccount->sum('amount'));
                                    @endphp
                                    {{ number_format($remain, 0, ',', ' ') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('select2')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
@endpush

@push('script')
<script>
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(function () {
        $('.select2').select2({
            
        });

        var handleDataTableButtons = function() {
        if ($("#dtable").length) {
            $("#dtable").DataTable({
           responsive: true,
           dom: "Bfrtip",
           buttons: [
             {
               extend: "copy",
               className: "btn-sm"
             },
             {
               extend: "csv",
               className: "btn-sm"
             },
             {
               extend: "excel",
               className: "btn-sm"
             },
             {
               extend: "pdfHtml5",
               className: "btn-sm"
             },
             {
               extend: "print",
               className: "btn-sm"
             },
           ],
           responsive: false
         });
       }
     };

     TableManageButtons = function() {
       "use strict";
       return {
         init: function() {
           handleDataTableButtons();
         }
       };
     }();

    TableManageButtons.init();  
	})
</script>
<script src="{{ asset('js/helpers.js') }}"></script>
@endpush