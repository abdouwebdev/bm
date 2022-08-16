@extends('_layouts.main')
@section('title', 'Account')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Sector')</li>
@endpush

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-payment">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.sector.create') }}" class="btn btn-sm btn-primary shadow"><i data-feather="plus"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive mt-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                          <th>@lang('Code')</th>
                          <th>@lang('Name')</th>
                          <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sectors as $sector)
                        <tr>
                            <td>{{$sector->code}}</td>
                            <td>{{$sector->name}}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                        data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.sector.edit', $sector->id) }}">
                                            <i data-feather="edit"></i>
                                            <span class="ml-1">@lang('Edit')</span>
                                        </a>
                                        <a href="javascript:void('delete')" class="dropdown-item text-danger" 
                                            onclick="deleteConfirm('form-delete', '{{ $sector->id }}')">
                                            <i data-feather="trash"></i>
                                            <span class="ml-1">@lang('Delete')</span>
                                        </a>
                                        <form id="form-delete{{ $sector->id }}" action="{{ route('admin.sector.destroy', $sector->id) }}" method="POST">
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
@endsection