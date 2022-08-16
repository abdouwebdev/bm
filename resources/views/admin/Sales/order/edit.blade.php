@extends('_layouts.main')
@section('title', 'Sale')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.sales.') }}">@lang('Sale')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.sales.order.index') }}">@lang('Order')</a>
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
        <form class="forms-sample" class="repeater" action="{{ route('admin.sales.order.update', $order->id) }}" method="POST">
            <div class="card ">
                <div class="card-body">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date">{{ __('Date') }}</label>
                                <input type="date" name="date" id="date" 
                                    class="form-control" value="{{ $order->date }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="code">{{ __('Order Code') }}</label>
                                <input class="form-control" id="code" type="text" value="{{ $order->code }}" name="code"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="offer_id">{{ __('Offer') }}</label>
                                <input type="text" id="offer_id" class="form-control"
                                    value="{{ $order->offer->code }}" disabled>
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
                                <table class="table table-borderless">
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
                            <table class="table table-borderless col-md-5 ml-auto border-top mt-2">
                                <tbody>
                                    <tr class="rowComponentTotal">
                                        <td style="width: 100px"><strong>@lang('Total')</strong></td>
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
                    <a href="{{ route('admin.sales.order.index') }}" class="btn btn-danger">@lang('RETURN')</a>
                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        @lang('UPDATE')
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

    .rowComponentTotal td{
        padding-right: 8px !important;
        padding-left: 0px !important;
    }
    .rowComponentTotal td .form-control{
        border-radius:0px !important;
    }

    @media only screen and (max-width: 768px) {
        .tambah-pelanggan {
            margin-top: 0;
            float: right;
        }
    }

    @media only screen and (min-width: 768px) {
        .tambah-pelanggan {
            margin-top: 23px;
        }
    }

</style>
@endpush

@push('script')
<script src="{{ asset('plugins/jquery.repeater/jquery.repeater.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
<script src="{{ asset('js/helpers.js') }}"></script>
<script>

    function generateUUID() {
        var d = new Date().getTime();
        var d2 = (performance && performance.now && (performance.now()*1000)) || 0;
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16;
            if (d > 0) {
                r = (d + r)%16 | 0;
                d = Math.floor(d/16);
            } else {
                r = (d2 + r)%16 | 0;
                d2 = Math.floor(d2/16);
            }
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }

    function jumlahin() {
        let total =  0
        let cols_debit = document.querySelectorAll('.total')
        for (let i = 0; i < cols_debit.length; i++) {
            let e_debit = cols_debit[i];
            total += parseFloat(e_debit.value.replace(/,/g, '')) == "" ? 0 : parseFloat(e_debit.value.replace(/,/g, ''))
        }
        return total;
    }

    function field_dinamis() {
        Object.keys(arguments).forEach(el => {
            arguments[el] = parseInt(arguments[el])
        })

        let id = arguments[0]
        let amount = arguments[1]
        let total = arguments[2]
        let subtotal = arguments[3]

        $("#total").val(subtotal)

        if (id == undefined) {
            id = ''
        }
        if (amount == undefined) {
            amount = ''
        }
        if (total == undefined) {
            total = ''
        }

        let index = $('#dynamic_field tr').length
        let uuid = generateUUID()
        let html = `
            <tr class="rowComponent">
                <input type="hidden" width="10px" name="orders[${index}][id]" value="${id}">
                <td class="no" hidden>
                    <input type="text" value="${index + 1}" class="form-control" disabled>
                </td>
                <td>
                    <select name="orders[${index}][product_id]" class="form-control select-${index}"></select>
                </td>
                <td>
                    <input type="text" name="orders[${index}][amount]" class="form-control amount" 
                        value="${amount}" placeholder="0" readonly>
                </td>
                <td>
                    <input type="text" name="orders[${index}][unit]" class="form-control unit"  readonly>
                </td>
                <td>
                    <input type="text" name="orders[${index}][price]" class="form-control price" readonly>
                </td>
                <td>
                    <input type="text" name="orders[${index}][total]" class="form-control total" 
                        value="${total}" placeholder="0" readonly>
                </td>
                <td>
                    <button type="button" name="remove"
                        class="btn btn-danger btn-sm text-white btn_remove">
                        <i data-feather="trash-2"></i>
                    </button>
                </td>
            </tr>
        `
        $("#dynamic_field").append(html)

        // const jumlah = document.getElementsByName(`penawarans[${index}][jumlah]`);
        // const total = document.getElementsByName(`penawarans[${index}][total]`);
        // jumlah.addEventListener('change', function (e){
        //     total.value = subTotal(index);
        // });

        $('[name="orders['+index+'][amount]"]').on('change', function () {

            const price = $('[name="orders['+index+'][price]"]').val();
            const total = parseFloat(price.replace(/,/g, '')) * parseInt($(this).val());
            $('[name="orders['+index+'][total]"]').val(formatter(total));

            $("#total").val(formatter(jumlahin()))

        });
        // jurnalEachColumn(index)
        feather.replace()
        $('select[name="orders['+index+'][product_id]"]').select2({
            placeholder: '-- @lang('Choose Product') --',
            ajax: {
                url: '{{ route('api.select2.get-product') }}',
                type: 'post',
                dataType: 'json',
                data: params => {
                    return {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        search: params.term
                    }
                },
                processResults: data => {
                    return {
                        results: data
                    }
                },
                cache: true
            },
            allowClear: true
        })

        $('select[name="orders['+index+'][product_id]"]').on('select2:select', function (e) {
            const unit = e.params.data.unit
            const price = e.params.data.price_sell

            $('[name="orders['+index+'][unit]"]').val(unit)
            $('[name="orders['+index+'][price]"]').val(formatter(price))
            $('[name="orders['+index+'][amount]"]').attr('readonly', false)
            $('[name="orders['+index+'][price]"]').attr('readonly', false)
        })

        document.querySelectorAll('.price').forEach(item => {
            item.addEventListener('keyup', function(event) {
                
                const n = parseInt(this.value.replace(/\D/g,''),10);
                item.value = formatter(n);
                
                // const total = parseFloat(item.value.replace(/,/g, '')) * parseInt($('[name="penawarans['+index+'][jumlah]"]').val());
                // $('[name="penawarans['+index+'][total]"]').val(formatter(total));

            })
        })

        $('[name="orders['+index+'][amount]"]').on('change', function () {

            const jumlahDua = parseInt($('[name="orders['+index+'][amount]"]').val());
            const hargaDua = $(this).val();
            const totalDua = jumlahDua * parseFloat(hargaDua.replace(/,/g, ''))
            $('[name="orders['+index+'][total]"]').val(formatter(totalDua));

            $("#total").val(formatter(jumlahin()))
            
        });
    }

    function getNumberOfTr() {
        $('#dynamic_field tr').each(function(index, tr) {
            $(this).find("td.no input").val(index + 1)
        })
    }
</script>

<script>
    let subtotal = 0;
    let product_url = '{{ route('api.select2.get-product.selected', ':id') }}'

    @foreach ($order->order_details as $index => $detail)
        subtotal += parseInt('{{ $detail->total }}')
        field_dinamis(
            '{{ $detail->id }}', '{{ $detail->amount }}', '{{ $detail->total }}',
            subtotal
        );

        $.ajax({
            type: 'get',
            url: product_url.replace(':id', '{{ $detail->product_id }}'),
            error: (err) => {
                console.log(err);
            }
        }).then((data) => {
            let option = new Option(data.text, data.id, true, true)
            $('select[name="orders[{{ $index }}][product_id]"]').append(option).trigger('change')
            $('select[name="orders[{{ $index }}][product_id]"]').trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            })
        })
    @endforeach

    $(document).ready(function(){
        getNumberOfTr()
        $('#add').click(function(){
            field_dinamis()
        })
        $(document).on('click', '.btn_remove', function() {
            let parent = $(this).parent()
            let id = parent.data('id')
            let delete_data = $("input[name='delete_data']").val()
            if(id !== 'undefined' && id !== undefined) {
                $("input[name='delete_data']").val(delete_data + ';' + id)
            }
            $('.btn_remove').eq($('.btn_remove').index(this)).parent().parent().remove()
            getNumberOfTr()
            $("#total").val(formatter(jumlahin()))
        })
    })
</script>
@endpush
