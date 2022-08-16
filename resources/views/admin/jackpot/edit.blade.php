@extends('_layouts.main')
@section('title', 'jackpot Edit')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item" >
    <a href="{{ route('admin.jackpot.index') }}">@lang('Jackpot')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Jackpot') @lang('Edit')</li>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('Edit') @lang('Jackpot')</h4>
                </div>
                <div class="card-body">
                    <form class="form form-horizontal" method="post" action="{{ route('admin.jackpot.update',$jackpot->id) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 col-form-label">
                                        <label for="code">@lang('Code')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="code" name="code" value="{{$jackpot->code}}" class="form-control" readonly/>
                                        @error('code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 col-form-label">
                                        <label for="name">@lang('Jackpot Name')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="name" name="name" value="{{$jackpot->name}}" class="form-control"/>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 col-form-label">
                                        <label for="session">@lang('Session')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        {!!Form::select('', $sessions, null, ['placeholder' => 'Session','class'=>'select2 session form-control ','required'=>'required' ,'id'=>'session'])!!}
                                        <span id="session" class="text-danger" >{{ $errors->first('session') }}</span>
                                        @error('session')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 col-form-label">
                                        <label for="member_id">@lang('Members')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ Form::select('member_id',[], null, ['placeholder' => 'Select Member','class'=>'select2 form-control member','required'=>'required','id'=>'member_id']) }}
                                        @error('member_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" id="group_id" name="group_id" value="{{ $group->id}}" class="form-control" readonly/>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 col-form-label">
                                        <label for="amount">@lang('Total Amount')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="amount" name="amount" value="{{$jackpot->amount}}" class="form-control" readonly/>
                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 col-form-label">
                                        <label for="solde">@lang('Add New Amount')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="solde" name="solde" placeholder="Ex: 20000"  class="form-control"/>
                                        @error('solde')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary" style="width: 200px">@lang('UPDATE')!
                                </button>
                            </div>
                        </div>
                    </form>
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
        $('#session').on('change',function (){
            $.ajax({
                url:'/admin/member/group',
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
        });
    });
    
</script>
<script src="{{ asset('js/helpers.js') }}"></script>
@endpush