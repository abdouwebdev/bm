@extends('_layouts.main')
@section('title', 'Account')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Users')</li>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-center mb-4">
                <div class="d-flex align-items-center">
                    @lang('Accounts List') <strong class="ml-1">{{ $userCount }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table  id="dtable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Role')</th>
                                    <th>@lang('Address')</th>
                                    <th style="width: 1px">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userAdmin as $ua)
                                <tr>
                                    <td>{{ $ua->name }}</td>
                                    <td>{{ $ua->email }}</td>
                                    <td>{{ $ua->role }}</td>
                                    <td>{{ $ua->address }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                                data-toggle="dropdown">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="javascript:void('delete')" class="dropdown-item text-danger" 
                                                    onclick="deleteConfirm('form-delete', '{{ $ua->id }}')">
                                                    <i data-feather="trash"></i>
                                                    <span class="ml-1">@lang('Delete')</span>
                                                </a>
                                                <form id="form-delete{{ $ua->id }}" action="{{ route('superadmin.destroy', $ua->id) }}" method="POST">
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
        <div class="col-md-4">
            @include('superadmin.user.group')
        </div>
    </div>
</div>
@endsection