@extends('_layouts.main')
@section('title', 'Offer')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.sales.') }}">@lang('Sale')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.sales.offer.index') }}">@lang('Offer')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Edit')</li>
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
        <form class="forms-sample" class="repeater" action="{{ route('admin.sales.offer.update', $offer->id) }}" method="POST">
            <div class="card ">
                <div class="card-body">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="date">{{ __('Date') }}<span class="text-red">*</span></label>
                                <input id="date" type="date" value="{{ old('date') ?? $offer->date }}"
                                    class="form-control @error('date') is-invalid @enderror" name="date">
                                <div class="help-block with-errors"></div>
                                @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="code">{{ __('Offer Code') }}<span class="text-red">*</span></label>
                                <input class="form-control" id="code" type="text" value="{{ $offer->code }}" name="code"
                                    disabled>
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
                                <button type="button" id="add" class="btn btn-success my-2"
                                    style="width: 100%; height: 50px">
                                    <i data-feather="plus"></i>
                                    @lang('Add New Row')
                                </button>
                                <table class="table table-borderless col-sm-6 ml-auto border-top">
                                    <tbody>
                                        <tr>
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
            </div>
            <hr>
            <div class="col-md-12 mt-4">
                <div class="form-group">
                    <a href="{{ route('admin.sales.offer.index') }}" class="btn btn-danger">@lang('RETURN')</a>
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
            padding: 0px 8px 16px 0 !important
    }
    .rowHead td{
        padding: 0px 8px 16px 0 !important
    }
    .rowComponent td .form-control{
        border-radius:0px !important;
    }

</style>
@endpush

@push('script')
<script src="{{ asset('plugins/jquery.repeater/jquery.repeater.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
<script src="{{ asset('js/helpers.js') }}"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

</script>
<script>

</script>
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
        let product_id = arguments[1]
        let amount = arguments[2]
        let total = arguments[3]
        let subtotal = arguments[4]

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
                <input type="hidden" name="offers[${index}][id]" value="${id}">
                <td class="no" hidden>
                    <input type="text" value="${index + 1}" class="form-control" disabled>
                </td>
                <td>
                    <select name="offers[${index}][product_id]" class="form-control select-${index}"></select>
                </td>
                <td>
                    <input type="text" name="offers[${index}][amount]" class="form-control amount" 
                        value="${amount}" placeholder="0">
                </td>
                <td>
                    <input type="text" name="offers[${index}][unit]" class="form-control unit"  readonly>
                </td>
                <td>
                    <input type="text" name="offers[${index}][price]" class="form-control price" readonly>
                </td>
                <td>
                    <input type="text" name="offers[${index}][total]" class="form-control total" 
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

        // const amount = document.getElementsByName(`penawarans[${index}][amount]`);
        // const total = document.getElementsByName(`penawarans[${index}][total]`);
        // amount.addEventListener('change', function (e){
        //     total.value = subTotal(index);
        // });

        $('[name="offers['+index+'][amount]"]').on('change', function () {
            const harga = $('[name="offers['+index+'][price]"]').val();
            const total = parseFloat(harga.replace(/,/g, '')) * parseInt($(this).val());
            $('[name="offers['+index+'][total]"]').val(formatter(total));

            $("#total").val(formatter(jumlahin()))
            
        });
        // jurnalEachColumn(index)
        feather.replace()
        $('select[name="offers['+index+'][product_id]"]').select2({
            placeholder: '-- @lang('Choose Product') --',
            ajax: {
                url: '{{ route('api.select2.get-product') }}',
                type: 'post',
                dataType: 'json',
                data: params => {
                    return {
                        _token: CSRF_TOKEN,
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

        let product_url = '{{ route('api.select2.get-product.selected', ':id') }}'
        $.ajax({
            type: 'get',
            url: product_url.replace(':id', product_id),
            error: (err) => {
                console.log(err);
            }
        }).then((data) => {
            let option = new Option(data.text, data.id, true, true)
            $('select[name="offers['+index+'][product_id]"]').append(option).trigger('change')
            $('select[name="offers['+index+'][product_id]"]').trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            })
        })

        $('select[name="offers['+index+'][product_id]"]').on('select2:select', function (e) {
            const unit = e.params.data.unit
            const price = e.params.data.price_sell

            $('[name="offers['+index+'][unit]"]').val(unit)
            $('[name="offers['+index+'][price]"]').val(formatter(price))
            $('[name="offers['+index+'][price]"]').attr('readonly', false)
            $('[name="offers['+index+'][amount]"]').attr('readonly', false)
        })

        document.querySelectorAll('.price').forEach(item => {
            item.addEventListener('keyup', function(event) {
                
                const n = parseInt(this.value.replace(/\D/g,''),10);
                item.value = formatter(n);
                
                // const total = parseFloat(item.value.replace(/,/g, '')) * parseInt($('[name="offers['+index+'][amount]"]').val());
                // $('[name="offers['+index+'][total]"]').val(formatter(total));

            })
        })

        $('[name="offers['+index+'][price]"]').on('change', function () {

            const jumlahDua = parseInt($('[name="offers['+index+'][amount]"]').val());
            const hargaDua = $(this).val();
            const totalDua = jumlahDua * parseFloat(hargaDua.replace(/,/g, ''))
            $('[name="offers['+index+'][total]"]').val(formatter(totalDua));

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

    @foreach ($offer_details as $detail)
        subtotal += parseInt('{{ $detail->total }}')
        field_dinamis(
            '{{ $detail->id }}', '{{ $detail->product_id }}',
            '{{ $detail->amount }}', '{{ $detail->total }}',
            subtotal
        );
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
