@extends('_layouts.main')
@section('title', 'Account Edit')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Accounts') @lang('Edit')</li>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('Edit') @lang('Accounts')</h4>
                </div>
                <div class="card-body">
                    <form class="form form-horizontal" method="post" action="{{ route('admin.account.update',$account->id) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 col-form-label">
                                        <label for="code">@lang('Code')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="code" name="code" value="{{$account->code}}" class="form-control" readonly/>
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
                                        <label for="code">@lang('Date')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input id="date" type="date" value="{{ date('Y-m-d') }}"
                                                class="form-control @error('date') is-invalid @enderror" name="date">
                                        @error('date')
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
                                        <label for="department_id">@lang('Department')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ Form::select('department_id',$departments,$account->department_id,['class'=>'select2 form-control ','id'=>'department_id']) }}
                                        @error('department_id')
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
                                        <label for="sector_id">@lang('Sector')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ Form::select('sector_id',$sectors,$account->sector_id,['class'=>'select2 form-control ','id'=>'sector_id']) }}
                                        @error('sector_id')
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
                                        {{ Form::select('member_id',$members,$account->member_id,['class'=>'select2 form-control ','id'=>'member_id']) }}
                                        @error('member_id')
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
                                        <label for="amount">@lang('Total Amount')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="amount" name="amount" value="{{$account->amount}}" class="form-control"/>
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
                                        <label for="beginning_balance">@lang('Actual Solde')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="beginning_balance" name="beginning_balance" value="{{$account->member->personalaccount->sum('amount')}}"  class="form-control" readonly/>
                                        @error('beginning_balance')
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
                                        <label for="beginning_balance">@lang('Add New Amount')</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="beginning_balance" name="beginning_balance" placeholder="Ex: 20000"  class="form-control"/>
                                        @error('beginning_balance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="group_id" id="group_id" value="{{ $group->id }}"/>
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

        <div class="col-md-8">
            <div class="card">
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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($accounts as $account)
                                @if($account->author_id == auth()->user()->id)
                                    <?php
                                        $bb = $account->member->personalaccount->sum('amount');
                                        $am = $account->amount - $bb ;
                                        $moitie  = ($account->amount / 2);
                                        $moitieInf = $moitie > $bb;
                                        $moitieSup = $moitie <= $bb;
                                        $complet = $account->amount == $bb;
                                    ?>
                                    <tr>
                                        <td><span class="badge badge-success">{{ $account->code }}</span></td>
                                        @if(!empty($account->member->firstName)  && (!empty($account->member->lastName)) && (!empty($account->department->name)))
                                        <td>{{ $account->member->firstName }} - {{ $account->member->lastName }}</td>
                                        <td>{{ $account->department->name }}</td>
                                        @else
                                        <td>#</td>
                                        <td>#</td>
                                        @endif
                                        <td>{{ number_format($account->amount, 0, ',', '.') }}</td>
                                        <td>{{ number_format($bb, 0, ',', '.') }}</td>
                                        @if($moitieInf)
                                        <td class=" bg bg-danger text-white">
                                            {{ number_format($am, 0, ',', '.') }}
                                        </td>
                                        @elseif($moitieSup AND $moitieSup != $complet)
                                        <td class=" bg bg-warning text-white">
                                            {{ number_format($am, 0, ',', '.') }}
                                        </td>
                                        @elseif($complet)
                                        <td class=" bg bg-success text-white">
                                            @lang('Complete')
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="10" align="center">
                                       
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
                
            });
		})
</script>
<script src="{{ asset('js/helpers.js') }}"></script>
@endpush