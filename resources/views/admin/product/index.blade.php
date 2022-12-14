@extends('_layouts.main')
@section('title', 'Data Product')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Product')</li>
@endpush
@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="card card-payment">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.product.create') }}" class="btn btn-sm btn-primary shadow"><i data-feather="plus"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" @if($products->count() == 1) style="height: 140px" @endif>
                        <thead>
                            <tr>
                                <th style="width: 1px">#</th>
                                <th>@lang('Product Name')</th>
                                <th>@lang('Selling Price')</th>
                                <th>@lang('Purchasse Price')</th>
                                <th>@lang('Status')</th>
                                <th style="width: 1px">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->price_buy, 0, ',', '.') }}</td>
                                <td>{{ number_format($product->price_sell, 0, ',', '.') }}</td>
                                <td>{{ $product->status == 1 ? 'Active' : 'Not active' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                            data-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.product.show', $product->id) }}">
                                                <i data-feather="eye"></i>
                                                <span class="ml-1">@lang('Show')</span>
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.product.edit', $product->id) }}">
                                                <i data-feather="edit"></i>
                                                <span class="ml-1">@lang('Edit')</span>
                                            </a>
                                            <a href="javascript:void('delete')" class="dropdown-item text-danger" 
                                                onclick="deleteConfirm('form-delete', '{{ $product->id }}')">
                                                <i data-feather="trash"></i>
                                                <span class="ml-1">@lang('Delete')</span>
                                            </a>
                                            <form id="form-delete{{ $product->id }}" action="{{ route('admin.product.destroy', $product->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" align="center">
                                    Data Empty.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <hr style="margin-top: -1px">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
