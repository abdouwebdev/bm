@extends('_layouts.main')
@section('title', 'Account')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Accounts')</li>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            @include('admin.account.create')
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        @lang('Accounts List') {{ $accountList }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table  id="dtable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 1px;">@lang('Code')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Department')</th>
                                    <th>@lang('Total Amount')</th>
                                    <th>@lang('Beginning Balance')</th>
                                    <th>@lang('Remaining amount')</th>
                                    <th style="width: 1px">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($accounts as $account)
                                @if($account->author_id == auth()->user()->id)
                                    <?php
                                        if(!empty($account->member->personalaccount->sum('amount'))){
                                            $bb = $account->member->personalaccount->sum('amount');
                                        }else{
                                            $bb = $account->beginning_balance;
                                        }
                                        $am = $account->amount - $bb ;
                                        $moitie  = ($account->amount / 2);
                                        $moitieInf = $moitie > $bb;
                                        $moitieSup = $moitie <= $bb;
                                        $complet = $account->amount == $bb;
                                    ?>
                                    <tr>
                                        <td style="width: 30px;"><span class="badge badge-success">{{ $account->code }}</span></td>
                                        @if(!empty($account->member->firstName)  && (!empty($account->member->lastName)) && (!empty($account->department->name)))
                                        <td>{{ $account->member->firstName }} - {{ $account->member->lastName }}</td>
                                        <td>{{ $account->department->name }}</td>
                                        @else
                                        <td>#</td>
                                        <td>#</td>
                                        @endif
                                        <td style="width: 100px;">{{ number_format($account->amount, 0, ',', ' ') }}</td>
                                        <td>{{ number_format($bb, 0, ',', ' ') }}</td>
                                        @if($moitieInf)
                                        <td class=" bg bg-danger text-white">
                                            {{ number_format($am, 0, ',', ' ') }}
                                        </td>
                                        @elseif($moitieSup AND $moitieSup != $complet)
                                        <td class=" bg bg-warning text-white">
                                            {{ number_format($am, 0, ',', ' ') }}
                                        </td>
                                        @elseif($complet)
                                        <td class=" bg bg-success text-white">
                                            @lang('Complete')
                                        </td>
                                        @endif
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                                    data-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.account.show', $account->id) }}">
                                                        <i data-feather="eye"></i>
                                                        <span class="ml-1">@lang('Show')</span>
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('admin.account.edit', $account->id) }}">
                                                        <i data-feather="edit"></i>
                                                        <span class="ml-1">@lang('Edit')</span>
                                                    </a>
                                                    <a href="javascript:void('delete')" class="dropdown-item text-danger" 
                                                        onclick="deleteConfirm('form-delete', '{{ $account->id }}')">
                                                        <i data-feather="trash"></i>
                                                        <span class="ml-1">@lang('Delete')</span>
                                                    </a>
                                                    <form id="form-delete{{ $account->id }}" action="{{ route('admin.account.destroy', $account->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="10" align="center">
                                       @lang('No Data')
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
<script>
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(function () {
        $('.select2').select2({
            placeholder: "@lang('Select Department')",
        });
        $('.select1').select2({
            placeholder: "@lang('Sector')",
        });
        $('.select3').select2({
            placeholder: "@lang('Session')",
        });
        $('.select').select2({
            placeholder: "@lang('Select Member')",
        });

        $('#session').on('change',function (){
            var dept = $('#department_id').val();
            var session = $('#session').val();
            if(!dept || !session){
                alert("error!");
            }
            else {
                $.ajax({
                    url:'/admin/member/'+dept+'/'+session,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        _token: CSRF_TOKEN
                    },
                    success: function(data) {
                        $('#member_id').empty();
                        $('#member_id').append('<option  value="">@lang('Select Member')</option>');
                        $.each(data.members, function(key, value) {
                            $('#member_id').append('<option  value="'+value.id+'">'+value.firstName+' '+value.lastName+'</option>');

                        });
                        $(".member").select2({
                            placeholder: "@lang('Select Member')",
                            allowClear: true
                        });

                    },
                    error: function(data){
                        var respone = JSON.parse(data.responseText);
                        $.each(respone.message, function( key, value ) {
                            alert('error');
                        });
                    }
                });
            }
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