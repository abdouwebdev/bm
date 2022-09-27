@extends('_layouts.main')
@section('title', 'Register')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('ADD')</li>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            @include('_auth.register')
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table  id="dtable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Email')</th>
                                    <th style="width: 1px">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                                    data-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                @if(Auth::user()->name != $user->name)
                                                <div class="dropdown-menu">
                                                    <a href="javascript:void('delete')" class="dropdown-item text-danger" 
                                                        onclick="deleteConfirm('form-delete', '{{ $user->id }}')">
                                                        <i data-feather="trash"></i>
                                                        <span class="ml-1">@lang('Delete')</span>
                                                    </a>
                                                    <form id="form-delete{{ $user->id }}" action="{{ route('admin.add-user-manager.destroy', $user->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
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