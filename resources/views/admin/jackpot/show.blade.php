@extends('_layouts.main')
@section('title', 'Jackpot')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{route('admin.jackpot.index')}}">@lang('Jackpot')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Show') @lang('Jackpot')</li>
@endpush
@section('content')
<div class="container">
    <div class="row card">
        <div class="col-md-12">
            <div class="card-body">
                <div class="table-responsive">
                    <table  id="dtable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Date')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Department')</th>
                                <th>@lang('Amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jds as $jd)
                                <tr>
                                    <td>{{$jd->date}}</td>
                                    <td>{{$jd->member->firstName}} {{$jd->member->lastName}}</td>
                                    <td>{{$jd->member->department->name}}</td>
                                    <td>{{$jd->amount}}</td>
                                </tr>
                            @endforeach
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