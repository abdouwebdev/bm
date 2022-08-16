@extends('_layouts.main')
@section('title', 'Jackpot')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Jackpot')</li>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            @include('admin.jackpot.create')
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        @lang('Jackpot')
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table  id="dtable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 1px;">@lang('Code')</th>
                                    <th>@lang('Jackpot Name')</th>
                                    <th>@lang('Jackpot Amount')</th>
                                    <th>@lang('Amount Available')</th>
                                    <th>@lang('Jackpot Solde Remaining')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jackpots as $jackpot)
                                @php
                                    $solde = $jackpot->group->jackpotdetail->sum('amount');
                                    $jackpoRestant = ($jackpot->amount - $solde);
                                @endphp
                                
                                <tr>
                                    <td style="width: 30px;"><span class="badge badge-success">{{ $jackpot->code }}</span></td>
                                    <td>{{ $jackpot->name }}</td>
                                    <td>{{ number_format($jackpot->amount, 0, ',', ' ') }}</td>
                                    <td>{{ number_format($solde, 0, ',', ' ') }}</td>
                                    <td>{{ number_format($jackpoRestant, 0, ',', ' ') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                                data-toggle="dropdown">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.jackpot.show', $jackpot->id) }}">
                                                    <i data-feather="eye"></i>
                                                    <span class="ml-1">@lang('Show')</span>
                                                </a>
                                                <a class="dropdown-item" href="{{ route('admin.jackpot.edit', $jackpot->id) }}">
                                                    <i data-feather="edit"></i>
                                                    <span class="ml-1">@lang('Edit')</span>
                                                </a>
                                                <a href="javascript:void('delete')" class="dropdown-item text-danger" 
                                                    onclick="deleteConfirm('form-delete', '{{ $jackpot->id }}')">
                                                    <i data-feather="trash"></i>
                                                    <span class="ml-1">@lang('Delete')</span>
                                                </a>
                                                <form id="form-delete{{ $jackpot->id }}" action="{{ route('admin.jackpot.destroy', $jackpot->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection