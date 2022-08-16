@extends('_layouts.main')
@section('title', 'Account')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">Data Master</a>
</li>
<li class="breadcrumb-item" aria-current="page">Department</li>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-payment">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.department.create') }}" class="btn btn-sm btn-primary shadow"><i data-feather="plus"></i></a>
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
                            @foreach ($departments as $department)
                            <tr>
                                <td>{{ $department->code }}</td>
                                <td>{{ $department->name }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                            data-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.department.edit', $department->id) }}">
                                                <i data-feather="edit"></i>
                                                <span class="ml-1">@lang('Edit')</span>
                                            </a>
                                            <a href="javascript:void('delete')" class="dropdown-item text-danger" 
                                                onclick="deleteConfirm('form-delete', '{{ $department->id }}')">
                                                <i data-feather="trash"></i>
                                                <span class="ml-1">@lang('Delete')</span>
                                            </a>
                                            <form id="form-delete{{ $department->id }}" action="{{ route('admin.department.destroy', $department->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </div>
                                    </div>
                                </td>
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
@endsection