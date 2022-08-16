@extends('_layouts.main')
@section('title', 'Communication')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Communication')</li>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            @include('admin.communication.create')
        </div>
        <div class="col-md-6">
            <div class="card">
                @foreach ($communications as $communication)
                    <h5 class="card-body text-center">{{ $communication->title }}</h5>
                    <p class="text-justify mr-1 ml-1">
                        {{ $communication->body }}
                    </p>
                    <div class="col-md-6">
                        <a href="javascript:void('delete')" class="btn btn-danger"
                            onclick="deleteConfirm('form-delete', '{{ $communication->id }}')">
                            <i data-feather="trash"></i>
                            <span>@lang('Delete')</span>
                        </a>
                        <form id="form-delete{{ $communication->id }}" action="{{ route('admin.communication.destroy', $communication->id) }}" method="POST">
                            @csrf
                            @method('delete')
                        </form>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection