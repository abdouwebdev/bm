@extends('_layouts.main')
@section('title', 'Create Member')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.department.index') }}">@lang('Department')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Edit') @lang('Department')</li>
@endpush
@section('content')
<div class="container">
    <div class="row card">
        <div class="col-md-6 mt-4">
            <form class="form form-horizontal" method="post" action="{{ route('admin.department.update',$department->id) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="group_id"  name="group_id"  value="{{$department->group_id}}" class="form-control" readonly/>
                        <div class="form-group row">
                            <div class="col-sm-2 col-form-label">
                                <label for="code">@lang('Code')</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="code" value="{{ $department->code }}" name="code"  class="form-control" readonly/>
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
                            <div class="col-sm-2 col-form-label">
                                <label for="code">@lang('Name')</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="name"  name="name" value="{{$department->name}}" class="form-control"/>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary" style="width: 200px">@lang('UPDATE')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection