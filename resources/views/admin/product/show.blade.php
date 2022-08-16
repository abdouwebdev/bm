@extends('_layouts.main')
@section('title', 'Data Product')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.product.index') }}">@lang('Product')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Show') @lang('Product')</li>
@endpush
@section('content')

<div class="row">
    <div class="card">
        <!-- Product Details starts -->
        <div class="card-body">
            <div class="row my-2">
                <div class="col-12 col-md-12">
                    <h4>{{ $product->name }}</h4>
                    <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                        <h4 class="item-price mr-1">@lang('Purchasse Price') : {{number_format($product->price_buy, 0, ',', '.') }}</h4>
                        <h4 class="item-price mr-1">@lang('Selling Price') : {{number_format($product->price_sell, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
