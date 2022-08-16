@extends('_layouts.main')
@section('title', 'Purchase')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.purchase.') }}">@lang('Purchase')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.purchase.offer.index') }}">@lang('Offer')</a>
</li>
<li class="breadcrumb-item active" aria-current="page">@lang('Edit')</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="alert-body">
                    @foreach ($errors->all() as $error)
                    <ul style="margin: 0 12px 0 -11px">
                        <li>{{ $error }}</li>
                    </ul>
                    @endforeach
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <form class="forms-sample" class="repeater" action="{{ route('admin.purchase.offer.update', $offer->id) }}" method="POST">
            <div class="card ">
                <div class="card-body">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="date">{{ __('Date') }}</label>
                                <input id="date" type="date" value="{{ $offer->date }}"
                                    class="form-control" disabled>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="code">{{ __('Offer Code') }}</label>
                                <input id="code" type="text" value="{{ $offer->code }}"
                                    class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-borderless mt-2">
                                    <thead>
                                        <tr class="rowHead">
                                            <td>@lang('Product')</td>
                                            <td>@lang('Amount')</td>
                                            <td>@lang('Unit')</td>
                                            <td>@lang('Unit') @lang('Price')</td>
                                            <td>@lang('Total')</td>
                                        </tr>
                                    </thead>
                                    <tbody id="dynamic_field"></tbody>
                                </table>
                            </div>
                            <button type="button" id="add" class="btn btn-success my-2"
                                style="width: 100%; height: 50px">
                                <i data-feather="plus"></i>
                                @lang('Add New Row')
                            </button>
                            <table class="table table-borderless col-sm-6 ml-auto border-top">
                                <tbody>
                                    <tr class="rowComponentTotal">
                                        <th style="width: 180px">@lang('Total')</th>
                                        <td>
                                            <input type="text" name="total" class="form-control" id="total" placeholder="0" readonly>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-md-12 mt-4">
                <div class="form-group">
                    <a href="{{ route('admin.sales.offer.index') }}" class="btn btn-danger">@lang('RETURN')</a>
                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        @lang('UPDATE')
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('select2')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
@endpush
@push('head')
<style>
    .select2 {
        width: 100% !important;
    }
    .rowComponent td{
        padding: 0px 8px 16px 0 !important;
    }
    .rowHead td{
        padding: 0px 8px 16px 0 !important;
    }
    .rowComponent td .form-control{
        border-radius:0px !important;
    }

    .rowComponentTotal th{
        padding-right: 8px !important;
        padding-left: 0px !important;
    }
    .rowComponentTotal td{
        padding-right: 8px !important;
        padding-left: 0px !important;
    }
    .rowComponentTotal td .form-control{
        border-radius:0px !important;
    }

    @media only screen and (max-width: 1024px) {
        .rowComponent td .amount {
            width: 70px;
        }
        .rowComponentTotal th{
            width: 160px !important;
        }
        .rowComponentTotal td .form-control{
            width: 100%;
        }
    }

    @media only screen and (max-width: 768px) {
        .rowComponentTotal td .form-control{
            width: 200px;
        }
        .rowComponentTotal th{
            width: 100px !important;
        }
        .rowComponentTotal td .form-control{
            width: 100%;
        }
    }

    @media only screen and (max-width: 575px) {
        .rowComponentTotal td .form-control{
            width: 100%;
        }
        .rowComponentTotal th{
            width: 150px !important;
        }
    }

    @media only screen and (max-width: 650px) {
        .rowComponent td .amount {
            width: 60px;
        }
        .rowComponent td .unit {
            width: 100px;
        }
        .rowComponent td .price {
            width: 120px;
        }
        .rowComponent td .total {
            width: 130px;
        }
    }
</style>
@endpush

@push('script')
<script src="{{ asset('plugins/jquery.repeater/jquery.repeater.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
<script src="{{ asset('js/helpers.js') }}"></script>
<script src="{{ asset('js/dynamic_fields.js') }}"></script>
<script>
    let offerdetail_url = '{{ route('api.get-buy-offer.details', ':id') }}';
    let url_product = '{{ route('api.select2.get-buy-product') }}';
    let selected_product = '{{ route('api.select2.get-buy-product.selected', ':id') }}';

    $(document).ready(function(){
        $("#btn-submit").attr('disabled', true)
        $("#add").attr('disabled', true);

        $.ajax({
            type: 'get',
            url: offerdetail_url.replace(':id', '{{ $offer->id }}'),
            dataType: 'json',
            error: (err) => {
                console.log(err);
            },
            success: results => {
                let subtotal = 0;
                Object.keys(results.data).forEach(key => {
                    const data = results.data[key];
                    $.ajax({
                        type: 'get',
                        url: selected_product.replace(':id', data.product_id),
                        error: (err) => {
                            console.log(err);
                        }
                    }).then((result) => {
                        field_dinamis_edit(
                            'offers', url_product,
                            data.id, data.amount, data.total
                        );
                        $(".btn_remove").attr('disabled', true);

                        subtotal += parseInt(data.total)
                        $("#total").val(formatter(subtotal))

                        if ((parseInt(key) + 1) == results.length) {
                            $("#btn-submit").attr('disabled', false)
                            $("#add").attr('disabled', false)
                            $(".btn_remove").attr('disabled', false);
                        }

                        let option = new Option(result.text, result.id, true, true)
                        $('select[name="offers['+ key +'][product_id]"]').append(option).trigger('change')
                        $('select[name="offers['+ key +'][product_id]"]').trigger({
                            type: 'select2:select',
                            params: {
                                data: result
                            }
                        })
                    })
                });
            }
        })

        $('#add').click(function(){
            field_dinamis_edit('offers', url_product);
            checkRowLength();
        })

        $(document).on('click', '.btn_remove', function() {
            let parent = $(this).parent()
            let id = parent.data('id')
            let delete_data = $("input[name='delete_data']").val()

            if(id !== 'undefined' && id !== undefined) {
                $("input[name='delete_data']").val(delete_data + ';' + id)
            }

            $('.btn_remove').eq($('.btn_remove').index(this)).parent().parent().remove()
            getNumberOfTr('offers');
            checkRowLength();

            $("#total").val(formatter(jumlahin()))
        })
    })
</script>
@endpush
