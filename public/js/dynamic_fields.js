const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

const generateUUID = () => {
    var d = new Date().getTime();
    var d2 = (performance && performance.now && performance.now() * 1000) || 0;
    return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
        /[xy]/g,
        function(c) {
            var r = Math.random() * 16;
            if (d > 0) {
                r = (d + r) % 16 | 0;
                d = Math.floor(d / 16);
            } else {
                r = (d2 + r) % 16 | 0;
                d2 = Math.floor(d2 / 16);
            }
            return (c === "x" ? r : (r & 0x3) | 0x8).toString(16);
        }
    );
};

const jumlahin = () => {
    let total = 0;
    let cols_total = document.querySelectorAll(".total");
    for (let i = 0; i < cols_total.length; i++) {
        let e_total = cols_total[i];

        if (e_total.value == '') {
            e_total.value = 0;
        }

        total += parseFloat(e_total.value.replace(/,/g, "")) == "" ?
            0 :
            parseFloat(e_total.value.replace(/,/g, ""));
    }
    return total;
};

const getNumberOfTr = (form) => {
    $("#dynamic_field tr").each(function(index, tr) {
        $(this).find("td.no input").val(index + 1);
        // reset index. masih ngebug pas ngeselect
        // $(this).find("input.id").attr('name', `${form}[${index}][id]`);
        // $(this).find("td select.product_id").attr('name', `${form}[${index}][product_id]`);
        // $(this).find("td input.amount").attr('name', `${form}[${index}][amount]`);
        // $(this).find("td input.unit").attr('name', `${form}[${index}][unit]`);
        // $(this).find("td input.price").attr('name', `${form}[${index}][price]`);
        // $(this).find("td input.total").attr('name', `${form}[${index}][total]`);
    });
};

function checkRowLength() {
    let length = $("#dynamic_field tr").length;
    if (length > 0) {
        $("#btn-submit").attr('disabled', false)
    } else {
        $("#btn-submit").attr('disabled', true)
    }
}

function field_dinamis(form, url_product) {
    let index = $("#dynamic_field tr").length;
    let uuid = generateUUID();
    let html = `
        <tr class="rowComponent">
            <input type="hidden" width="10px" name="${form}[${index}][id]" class="id" value="${uuid}">
            <td class="no" hidden>
                <input type="text" value="${
                    index + 1
                }" class="form-control" disabled>
            </td>
            <td>
                <select name="${form}[${index}][product_id]" class="form-control select-${index} product_id"></select>
            </td>
            <td>
                <input type="number" name="${form}[${index}][amount]" class="form-control amount" 
                    onkeypress="onlyNumber(event)" min="1" placeholder="0" autocomplete="off" readonly>
            </td>
            <td>
                <input type="text" name="${form}[${index}][unit]" class="form-control unit"  readonly>
            </td>
            <td>
                <input type="text" name="${form}[${index}][price]" class="form-control price" autocomplete="off" readonly>
            </td>
            <td>
                <input type="text" name="${form}[${index}][total]" class="form-control total" value="0" readonly>
            </td>`;
    if (index >= 1) {
        html += `<td>
            <button type="button" name="remove" 
                class="btn btn-danger btn-sm text-white btn_remove">
                <i data-feather="trash-2"></i>
            </button>
        </td></tr>`;
    } else {
        html += `<td></td></tr>`;
    }

    $("#dynamic_field").append(html);

    // const amount = document.getElementsByName(`${form}[${index}][amount]`);
    // const total = document.getElementsByName(`${form}[${index}][total]`);
    // amount.addEventListener('change', function (e){
    //     total.value = subTotal(index);
    // });

    $('[name="' + form + '[' + index + '][amount]"]').on("change", function() {
        const amount = $(this).val() == '' ? 0 : $(this).val();
        const price = $('[name="' + form + '[' + index + '][price]"]').val();
        const total = parseFloat(price.replace(/,/g, "")) * parseInt(amount);

        $('[name="' + form + '[' + index + '][total]"]').val(formatter(total));
        $("#total").val(formatter(jumlahin()));
    });
    // jurnalEachColumn(index)
    feather.replace();
    $('select[name="' + form + '[' + index + '][product_id]"]').select2({
        placeholder: "-- Product --",
        ajax: {
            url: url_product,
            type: "post",
            dataType: "json",
            data: (params) => {
                return {
                    _token: CSRF_TOKEN,
                    search: params.term,
                };
            },
            processResults: (data) => {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        allowClear: true,
    });

    $('select[name="' + form + '[' + index + '][product_id]"]').on("select2:select", function(e) {
        const unit = e.params.data.unit;
        const price = e.params.data.price_buy;

        $('[name="' + form + '[' + index + '][amount]"]').attr("readonly", false);
        $('[name="' + form + '[' + index + '][unit]"]').val(unit);
        $('[name="' + form + '[' + index + '][price]"]').attr("readonly", false).val(formatter(price));

        const amount = $('[name="' + form + '[' + index + '][amount]"]').val() == '' ?
            0 :
            $('[name="' + form + '[' + index + '][amount]"]').val();
        const totalDua = parseInt(amount) * parseInt(price);

        $('[name="' + form + '[' + index + '][total]"]').val(formatter(totalDua));
        $("#total").val(formatter(jumlahin()));
    });

    document.querySelectorAll(".price").forEach((item) => {
        item.addEventListener("keyup", function(event) {
            const val = this.value == '' ? 0 : this.value.replace(/\D/g, "")
            const n = parseInt(val, 10);
            item.value = formatter(n);

            // const total = parseFloat(item.value.replace(/,/g, '')) * parseInt($('[name="' + form + '['+index+'][amount]"]').val());
            // $('[name="' + form + '['+index+'][total]"]').val(formatter(total));
        });
    });

    $('[name="' + form + '[' + index + '][price]"]').on("change", function() {
        const amount = $('[name="' + form + '[' + index + '][amount]"]').val() == '' ?
            0 :
            $('[name="' + form + '[' + index + '][amount]"]').val();
        const jumlahDua = parseInt(amount);
        const hargaDua = $(this).val() == '' ? 0 : $(this).val();
        const totalDua = jumlahDua * parseFloat(hargaDua.replace(/,/g, ""));

        $('[name="' + form + '[' + index + '][total]"]').val(formatter(totalDua));
        $("#total").val(formatter(jumlahin()));
    });
}

function field_dinamis_edit(form, url_product, id = undefined, amount = undefined, total = undefined) {
    if (id == undefined) {
        id = "";
    } else {
        id = parseInt(id);
    }

    if (amount == undefined) {
        amount = "";
    } else {
        amount = parseInt(amount);
    }

    if (total == undefined) {
        total = "";
    } else {
        total = parseInt(total);
    }

    let index = $("#dynamic_field tr").length;
    let uuid = generateUUID();
    let html = `
        <tr class="rowComponent">
            <input type="hidden" width="10px" name="${form}[${index}][id]" class="id" value="${id}">
            <td class="no" hidden>
                <input type="text" value="${
                    index + 1
                }" class="form-control" disabled>
            </td>
            <td>
                <select name="${form}[${index}][product_id]" class="form-control select-${index} product_id"></select>
            </td>
            <td>
                <input type="number" name="${form}[${index}][amount]" class="form-control amount" 
                    onkeypress="onlyNumber(event)" min="1" value="${amount}" placeholder="0" autocomplete="off" readonly>
            </td>
            <td>
                <input type="text" name="${form}[${index}][unit]" class="form-control unit"  readonly>
            </td>
            <td>
                <input type="text" name="${form}[${index}][price]" class="form-control price" autocomplete="off" readonly>
            </td>
            <td>
                <input type="text" name="${form}[${index}][total]" class="form-control total" 
                    value="${total}" placeholder="0" readonly>
            </td>`;
    if (index >= 1) {
        html += `<td>
            <button type="button" name="remove" 
                class="btn btn-danger btn-sm text-white btn_remove">
                <i data-feather="trash-2"></i>
            </button>
        </td></tr>`;
    } else {
        html += `<td></td></tr>`;
    }

    $("#dynamic_field").append(html);

    // const amount = document.getElementsByName(`${form}[${index}][amount]`);
    // const total = document.getElementsByName(`${form}[${index}][total]`);
    // amount.addEventListener('change', function (e){
    //     total.value = subTotal(index);
    // });

    $('[name="' + form + '[' + index + '][amount]"]').on("change", function() {
        const amount = $(this).val() == '' ? 0 : $(this).val();
        const price = $('[name="' + form + '[' + index + '][price]"]').val();
        const total = parseFloat(price.replace(/,/g, "")) * parseInt(amount);

        $('[name="' + form + '[' + index + '][total]"]').val(formatter(total));
        $("#total").val(formatter(jumlahin()));
    });

    feather.replace();

    $('select[name="' + form + '[' + index + '][product_id]"]').select2({
        placeholder: "-- Product --",
        ajax: {
            url: url_product,
            type: "post",
            dataType: "json",
            data: (params) => {
                return {
                    _token: CSRF_TOKEN,
                    search: params.term,
                };
            },
            processResults: (data) => {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        allowClear: true,
    });

    $('select[name="' + form + '[' + index + '][product_id]"]').on("select2:select", function(e) {
        const unit = e.params.data.unit;
        const price = e.params.data.price_buy;

        $('[name="' + form + '[' + index + '][amount]"]').attr("readonly", false);
        $('[name="' + form + '[' + index + '][unit]"]').val(unit);
        $('[name="' + form + '[' + index + '][price]"]').attr("readonly", false).val(formatter(price));

        const amount = $('[name="' + form + '[' + index + '][amount]"]').val() == '' ?
            0 :
            $('[name="' + form + '[' + index + '][amount]"]').val();
        const totalDua = parseInt(amount) * parseInt(price);

        $('[name="' + form + '[' + index + '][total]"]').val(formatter(totalDua));
        $("#total").val(formatter(jumlahin()));
    });

    document.querySelectorAll(".price").forEach((item) => {
        item.addEventListener("keyup", function(event) {
            const val = this.value == '' ? 0 : this.value.replace(/\D/g, "")
            const n = parseInt(val, 10);
            item.value = formatter(n);

            // const total = parseFloat(item.value.replace(/,/g, '')) * parseInt($('[name="' + form + '['+index+'][amount]"]').val());
            // $('[name="' + form + '['+index+'][total]"]').val(formatter(total));
        });
    });

    $('[name="' + form + '[' + index + '][price]"]').on("change", function() {
        const amount = $('[name="' + form + '[' + index + '][amount]"]').val() == '' ?
            0 :
            $('[name="' + form + '[' + index + '][amount]"]').val();
        const jumlahDua = parseInt(amount);
        const hargaDua = $(this).val();
        const totalDua = jumlahDua * parseFloat(hargaDua.replace(/,/g, ""));

        $('[name="' + form + '[' + index + '][total]"]').val(formatter(totalDua));
        $("#total").val(formatter(jumlahin()));
    });
}