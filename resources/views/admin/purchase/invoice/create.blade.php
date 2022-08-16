@extends('_layouts.main')
@section('title', 'Purchase')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.purchase.') }}">@lang('Purchase')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.purchase.invoice.index') }}">@lang('Invoice')</a>
</li>
<li class="breadcrumb-item active" aria-current="page">@lang('ADD')</li>
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
        <form class="forms-sample" class="repeater" action="{{ route('admin.purchase.invoice.store') }}" method="POST">
            <div class="card ">
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="group_id" id="group_id" value="{{ $group->id }}" readonly>
                    <input type="hidden" name="code" value="{{ $code }}">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date">{{ __('Date') }}<span class="text-danger">*</span></label>
                                <input id="date" type="date" value="{{ date('Y-m-d') }}"
                                    class="form-control @error('date') is-invalid @enderror" name="date">
                                <div class="help-block with-errors"></div>
                                @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="code">{{ __('Order Code') }}<span class="text-danger">*</span></label>
                                <input class="form-control" id="code" type="text" value="{{ $code }}" name="code"
                                    readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="order_id">{{ __('Order') }}<span class="text-danger">*</span></label>
                                <select name="order_id" id="order_id"
                                    class="form-control select2 @error('order_id') is-invalid @enderror">
                                </select>
                                <div class="help-block with-errors"></div>
                                @error('order_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                                            <td>@lang('Price')</td>
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
                                            <input type="text" name="total" class="form-control" id="total"
                                                placeholder="0" readonly>
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
                    <a href="{{ route('admin.purchase.order.index') }}" class="btn btn-danger">@lang('RETURN')</a>
                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        @lang('ADD')
                    </button>
                </div>
            </div>
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
        .tambah-pelanggan {
            margin-top: 0;
            float: right;
        }
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

    @media only screen and (min-width: 768px) {
        .tambah-pelanggan {
            margin-top: 23px;
        }
        .rowComponentTotal th{
            width: 100px !important;
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

    field_dinamis('invoices', '{{ route('admin.get-buy-product') }}');

    $(document).ready(function () {

        $("#order_id").select2({
            placeholder: "-- @lang('Choose Order') --",
            ajax: {
                url: '{{ route('admin.get-buy-order') }}',
                type: 'post',
                dataType: 'json',
                data: params => {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term
                    }
                },
                error: (err) => {
                    console.log(err)
                },
                processResults: data => {
                    return {
                        results: data
                    }
                },
                cache: true
            },
        });

        $('#order_id').on('select2:select', function (e) {
            const data = e.params.data;
            const detail = data.detail;
            let subtotal = 0;

            $(this).attr('disabled', true)
            $("#add").attr('disabled', true)
            $("#btn-submit").attr('disabled', true)

            for (let index = 0; index < detail.length; index++) {
                let product_id = detail[index].product_id;
                let amount = detail[index].amount;
                let unit = detail[index].unit;
                let price = detail[index].price;
                let total = detail[index].total;

                let selected_product_url = '{{ route('api.select2.get-buy-product.selected', ':id') }}';

                $("#dynamic_field").html('')

                $.ajax({
                    url: selected_product_url.replace(':id', product_id),
                    type: 'get',
                }).then((data) => {
                    field_dinamis('invoices', '{{ route('admin.get-buy-product') }}');
                    $(".btn_remove").attr('disabled', true);

                    let option = new Option(data.text, data.id, true, true)

                    $('select[name="invoices['+index+'][product_id]"]').append(option).trigger('change')
                    $('select[name="invoices['+index+'][product_id]"]').trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    })

                    $('[name="invoices['+index+'][price]"]').attr('readonly', false)
                    $('[name="invoices['+index+'][amount]"]').attr('readonly', false)

                    $('[name="invoices['+index+'][amount]"]').val(amount)
                    $('[name="invoices['+index+'][unit]"]').val(unit)
                    $('[name="invoices['+index+'][price]"]').val(formatter(price))
                    $('[name="invoices['+index+'][total]"]').val(formatter(total))

                    subtotal += total;
                    $("#total").val(formatter(subtotal))

                    if ((index + 1) == detail.length) {
                        $(this).attr('disabled', false)
                        $(".btn_remove").attr('disabled', false);
                        $("#add").attr('disabled', false)
                        $("#btn-submit").attr('disabled', false)
                    }
                })
            }
        })

        $('#add').click(function () {
            field_dinamis('invoices', '{{ route('admin.get-buy-product') }}');
            checkRowLength();
        })

        $(document).on('click', '.btn_remove', function () {
            let parent = $(this).parent()
            let id = parent.data('id')
            let delete_data = $("input[name='delete_data']").val()
            if (id !== 'undefined' && id !== undefined) {
                $("input[name='delete_data']").val(delete_data + ';' + id)
            }
            $('.btn_remove').eq($('.btn_remove').index(this)).parent().parent().remove()
            getNumberOfTr('invoices')
            checkRowLength();
            $("#total").val(formatter(jumlahin()))
        })
    })

</script>
@endpush
