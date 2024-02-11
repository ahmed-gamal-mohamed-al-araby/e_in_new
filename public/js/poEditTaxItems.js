var items = [];


(function ($) {
    var modal = $("#addItemsForm");

    modal.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: {
            product_code: {
                required: true,
            },
            quantity: {
                required: true,
            },
            item_price: {
                required: true
            },
            product_name: {
                required: true
            },
            "unit[]": {
                required: true
            },

        },
        messages: {
            quantity: {
                required: 'PLease enter quantity <i class="zmdi zmdi-info"></i>'
            },
            item_price: {
                required: 'PLease enter Price <i class="zmdi zmdi-info"></i>'
            },
            product_code: {
                required: 'PLease enter Internal Code to show Item Code <i class="zmdi zmdi-info"></i>'
            },
            product_name: {
                required: 'PLease enter Internal Code to show product name <i class="zmdi zmdi-info"></i>'
            },
            "unit[]": {
                required: 'Unit Required <i class="zmdi zmdi-info"></i>'
            }
        },
        onfocusout: function (element) {
            $(element).valid();
        },
    });





    jQuery.extend(jQuery.validator.messages, {
        required: "",
        remote: "",
        url: "",
        date: "",
        dateISO: "",
        number: "",
        digits: "",
        creditcard: "",
        equalTo: ""
    });




    $('#button').click(function () {
        $("input[type='file']").trigger('click');
    })

    $("input[type='file']").change(function () {
        $('#val').text(this.value.replace(/C:\\fakepath\\/i, ''))
    })

})(jQuery);

$(window).on('load', function () {

    if ($(".tax-items").length <= 1) {
        $('.delete_tax_row').hide();
    }
    // disable hide modal in click without close buttonx


    var global_net_total = $('.add-invoice-items').find('.net_total');
    var discount_items_rate = $('.add-invoice-items').find('.discount_items_rate');
    var discount_items_number = $('.add-invoice-items').find('.discount_items_number');
    var tableRowCount = 0;
    var checkDataInModal = null;

    var T1SubTypes = ['choose', 'V001', 'V002', 'V003', 'V004', 'V005', 'V006', 'V007', 'V008', 'V009', 'V010'];
    var T2SubTypes = ['choose', 'Tbl01'];
    var T3SubTypes = ['choose', 'Tbl02'];
    var T4SubTypes = ['choose', 'W001', 'W002', 'W003', 'W004', 'W005', 'W006', 'W007', 'W008', 'W009', 'W010',
        'W011', 'W012', 'W013', 'W014', 'W015', 'W016'];
    var T5SubTypes = ['choose', 'v001', 'ST01'];
    var T6SubTypes = ['choose', 'ST02'];
    var T7SubTypes = ['choose', 'Ent01', 'Ent02'];
    var T8SubTypes = ['choose', 'RD01', 'RD02'];
    var T9SubTypes = ['choose', 'SC01', 'SC02'];
    var T10SubTypes = ['choose', 'Mn01', 'Mn02'];
    var T11SubTypes = ['choose', 'MI01', 'MI02'];
    var T12SubTypes = ['choose', 'OF01', 'OF02'];
    var T13SubTypes = ['choose', 'ST03'];
    var T14SubTypes = ['choose', 'ST04'];
    var T15SubTypes = ['choose', 'Ent03', 'Ent04'];
    var T16SubTypes = ['choose', 'RD03', 'RD04'];
    var T17SubTypes = ['choose', 'SC03', 'SC04'];
    var T18SubTypes = ['choose', 'Mn03', 'Mn04'];
    var T19SubTypes = ['choose', 'MI03', 'MI04'];
    var T20SubTypes = ['choose', 'OF03', 'OF04'];




    //  add new item
    $('.addNewItem').click(function (e) {
        tableRowCount = $('.tableForItems tbody tr').length;
        items.push(new Item());
        $('#addline').data('checkData', 'null');
        checkDataInModal = $('#addline').data('checkData');
    });

    //  add new tax row
    $("#add_tax_row").click(function (e) {  // Add tax row

        $('.delete_tax_row').show();

        // remove option if selected
        let $table = $('.tax-items-table');
        let $top = $table.find('div.tax-items').first();
        let $new = $top.clone(true);

        $new.removeClass('d-none');
        $table.append($new);
        $new.find('input[type=text]').val('');
        $new.find('input[type=number]').val('');
        $new.find('input[type=number]').prop('readonly', true);

        $('.tax-items').not('.d-none').find('.tax-type').each(function (index, element) {
            if ($(element).val() != null) {
                $new.find(".tax-type option[value='" + $(element).val() + "']").hide();
            }
        });

        resetTaxIds();
        taxesCounter($new);
    });

    // remove tax row
    $(".delete_tax_row").click(function (e) { // delete tax row
        $('.delete_tax_row').show();

        let taxType = $(this).parents('.tax-items').find('.tax-type').val();
        $('.tax-items').find(".tax-type option[value='" + taxType + "']").show();


        $(this).parents('.tax-items').remove();
        if ($(".tax-items").length <= 1) {
            $('.delete_tax_row').hide();
        }

        calcTotalForm();
        changeSumTaxableItems();
        changeSumT2T3();
        resetTaxIds();

    });



    // reset tax items counter
    let taxCounter = 0;
    function taxesCounter(object) {
        var taxId = object.find('.remove_new_row_tax').data('taxId', taxCounter++);
    }
    // reset tax items ids
    function resetTaxIds() {
        $('.tax-items').not('.d-none').find('.remove_new_row_tax')
            .each(function (index, element) {
                $(element).data('taxId', index).next().text(index);
            });
        taxCounter = $('.tax-items').not('.d-none').length - 1;
    }
    // delete item
    $('.tableForItems tbody').on('click', '.tableItemsBtn.deleteItem', function () {
        let itemIndex = $(this).data('itemId');
        items.splice(itemIndex, 1);
        $(this).parents('tr').remove();
    });

    $('.quantity').on('keyup input change', function () { // items quantity
        calcItemsTotalSales($(this));
        netTotalByQuantity();
    });
    $('.item_price').on('keyup input change', function () { // price of item
        calcItemsTotalSales($(this));
        netTotalByQuantity();
    });

    function calcItemsTotalSales(row) {  // calc total salse of items => quantity * item price
        let quantity = row.parents().find('.quantity').val();
        let item_price = row.parents().find('.item_price').val();
        if ((item_price) != '' && (quantity) != '') {
            $('.price').parents().find('.sales_amount').val(parseFloat(quantity) * parseFloat(item_price));
            totalAmount();
        } else {
            $('.price').parents().find('.sales_amount').val('');
        }
    }

    $('.discount_items_rate').on('keyup input change', function () { // add discount in items by rate
        discount('rate');
    });

    $('.discount_items_number').on('keyup input change', function () { // add discount in items by value
        discount();
    });

    function discount(type) { // discount sales amount with rate or value
        let sales_amount = parseFloat($('.add-invoice-items').find('.sales_amount').val());
        sales_amount = sales_amount ? sales_amount : 0;
        if (type == 'rate') {
            discount_items_number.val(null);
            let discount_value = sales_amount * (discount_items_rate.val() / 100);
            discount_items_number.val(discount_value);
            global_net_total.val(sales_amount - discount_value);
        } else {
            discount_items_rate.val(null);
            global_net_total.val(sales_amount - discount_items_number.val());
        }
        totalAmount();
        calcTotalForm();
    }

    function netTotalByQuantity() { // add events on quantity and price
        if (discount_items_rate.val() != '') {
            discount('rate');
        } else if (discount_items_rate.val() == '') {
            discount();
        }
        calcTotalForm();
    }

    $('.tax-items').on('keyup input change', function () { // calc total form tax
        calcTotalForm();
    });
    $('.itemsDiscount').on('keyup input change', function () { // on change items discount input
        calcTotalForm();
    });
    $('.differ_value').on('keyup input change', function () { // on change items discount input
        calcTotalForm();
    });

    function calcTotalForm() { // calc total amount and total tax amount by rate or fixed value
        $('.tax-items').not('.d-none').each(function (i, element) {
            var html = $(this).html();
            if (html != '') {

                $(this).find('.row_total_tax').prop('readonly', true);
                $(this).find('.tax_rate').prop('readonly', false);

                changeSumTaxableItems();
                changeSumNonTaxableItems();
                changeSumT2T3();

                let Tax_type = $(this).find('.tax-type').val();

                if (Tax_type >= 5 && Tax_type <= 12) { // taxable fees
                    CallT5_T20($(this), true);

                } else if (Tax_type >= 13 && Tax_type <= 20) { // non-taxable fees
                    CallT5_T20($(this), false);
                }

                if (Tax_type == 3 || Tax_type == 6) { // Fixed Amount
                    CallT3T6($(this));
                }

                if (Tax_type == 4) { // calc t4
                    CallT4($(this));
                }

                if (Tax_type == 2) { // calc t2
                    CallT2($(this));
                }

                if (Tax_type == 1) { // calc t1
                    CallT1($(this));
                }
            }
        });
        totalAmount();
    }

    function sumRowTaxes(taxesFilter) { // sum taxable and non- taxable and t2t3
        let total = 0;
        let row_tax_type = 0;
        if (taxesFilter === 'taxable')
            $('.tax-items').not('.d-none').find('.row_total_tax').each(function () {
                const tax_type_value = $(this).parents('.tax-row-container').find('.tax-type').val();
                if (tax_type_value >= 5 && tax_type_value <= 12)
                    total += (parseFloat($(this).val()) ? parseFloat($(this).val()) : 0);
            });
        else if (taxesFilter === 'T2T3')
            $('.tax-items').not('.d-none').find('.row_total_tax').each(function () {
                const tax_type_value = $(this).parents('.tax-row-container').find('.tax-type').val();
                if (tax_type_value == 2 || tax_type_value == 3)
                    total += (parseFloat($(this).val()) ? parseFloat($(this).val()) : 0);
            });
        else if (taxesFilter === 'notTaxable')
            $('.tax-items').not('.d-none').find('.row_total_tax').each(function () {
                const tax_type_value = $(this).parents('.tax-row-container').find('.tax-type').val();
                if (tax_type_value >= 13 && tax_type_value <= 20)
                    total += (parseFloat($(this).val()) ? parseFloat($(this).val()) : 0);
            });
        else
            $('.tax-items').not('.d-none').find('.row_total_tax').each(function () {
                row_tax_type = $(this).parents('.tax-row-container').find('.tax-type').val();
                if (row_tax_type == 4) {
                    total -= (parseFloat($(this).val()) ? parseFloat($(this).val()) : 0);
                } else {
                    total += (parseFloat($(this).val()) ? parseFloat($(this).val()) : 0);
                }
            });
        return parseFloat(total.toFixed(5));
    }

    function totalAmount() { //calc total amount in each tax row with discount

        let total = parseFloat(sumRowTaxes());
        total = total ? total : 0;

        let net_total = parseFloat($('.add-invoice-items').find('.net_total').val());
        net_total = net_total ? net_total : 0;

        let itemsDiscount = $('.itemsDiscount').val();
        itemsDiscount = itemsDiscount ? itemsDiscount : 0;
        if (itemsDiscount != 0)
            $('.total_amount').val((total + net_total - parseFloat(itemsDiscount).toFixed(5)));
        else
            $('.total_amount').val((total + net_total).toFixed(5));
    }

    function CallT5_T20(row, taxableItem) { // calc taxable types and non taxable types
        let taxType = row.find('.tax-type').val();
        if (taxType != 6) {
            let tax_rate = parseFloat(row.find('.tax_rate').val());
            tax_rate = tax_rate ? tax_rate : 0;
            row.find('.row_total_tax').val(parseFloat(global_net_total.val()) * (tax_rate / 100)).val();
        }
        if (taxableItem) {
            changeSumTaxableItems();
        } else {
            changeSumNonTaxableItems();
        }
    }

    function CallT4(row) { // calc amount of t4
        let net_total = parseFloat($('.add-invoice-items').find('.net_total').val());
        net_total = net_total ? net_total : 0;
        let itemsDiscount = parseFloat($('.add-invoice-items').find('.itemsDiscount').val());
        itemsDiscount = itemsDiscount ? itemsDiscount : 0;
        let tax_rate = parseFloat(row.find('.tax_rate').val());
        tax_rate = tax_rate ? tax_rate : 0;
        let t4TotalAmount = 0;
        if (itemsDiscount != 0) {
            t4TotalAmount = (net_total - itemsDiscount) * (tax_rate / 100).toFixed(5);
        } else {
            t4TotalAmount = (net_total) * (tax_rate / 100);
        }
        row.find('.row_total_tax').val(t4TotalAmount.toFixed(5));

    }

    function CallT2(row) { //calc amount of t2
        let itemsTaxable = sumRowTaxes('taxable');
        itemsTaxable = itemsTaxable ? itemsTaxable : 0;
        let valueDiffer = parseFloat($('.add-invoice-items').find('.differ_value').val());
        valueDiffer = valueDiffer ? valueDiffer : 0;
        let tax_rate = parseFloat(row.find('.tax_rate').val());
        tax_rate = tax_rate ? tax_rate : 0;

        t2TotalAmount = ((parseFloat(global_net_total.val()) ? parseFloat(global_net_total.val()) : 0)
            + valueDiffer + itemsTaxable) * (tax_rate / 100);

        row.find('.row_total_tax').val(t2TotalAmount.toFixed(5));
    }

    function CallT1(row) { //calc amount of t1
        let sumT2T3 = sumRowTaxes('T2T3');
        sumT2T3 = sumT2T3 ? sumT2T3 : 0;

        let itemsTaxable = sumRowTaxes('taxable');
        itemsTaxable = itemsTaxable ? itemsTaxable : 0;

        let valueDiffer = parseFloat($('.add-invoice-items').find('.differ_value').val());
        valueDiffer = valueDiffer ? valueDiffer : 0;

        let tax_rate = parseFloat(row.find('.tax_rate').val());
        tax_rate = tax_rate ? tax_rate : 0;


        t1TotalAmount = ((parseFloat(global_net_total.val()) ? parseFloat(global_net_total.val()) : 0) + valueDiffer + sumT2T3 + itemsTaxable) * (tax_rate / 100);
        row.find('.row_total_tax').val(t1TotalAmount.toFixed(5));
    }

    function CallT3T6(row) { // fixed value without rate
        row.find('.tax_rate').prop('readonly', true);
        row.find('.row_total_tax').prop('readonly', false);
        row.find('.tax_rate').val('');
    }

    function changeSumT2T3() { //sum t2 or t3 amount
        sumRowTaxes('T2T3');
    }

    function changeSumTaxableItems() { // sum of taxable items from t5 to t12
        if (sumRowTaxes('taxable') == 0) {
            $('.add-invoice-items').find('.taxable_fees').val('');
        }
        else {
            $('.add-invoice-items').find('.taxable_fees').val(sumRowTaxes('taxable'));
        }

    }

    function changeSumNonTaxableItems() { // sum of non taxable items from t12 to t20
        // console.log(sumRowTaxes('notTaxable'));
        sumRowTaxes('notTaxable');
    }

    var previousValue;
    $(".tax-type").on('focus', function () {
        previousValue = this.value;

    }).change(function () {

        var rowSubType = $(this).parents('.tax-items').find('.subtype');
        var typeName = $(this).parents('.tax-items').find('.typeName');

        $('.tax-type').not($(this)).find('option[value="' + previousValue + '"]').show();
        $('.tax-type').not($(this)).find('option[value="' + $(this).val() + '"]').hide();

        rowSubType.empty();
        var dataSelected = $(this).val();

        appendSubType(dataSelected, rowSubType, typeName);
    });

    function appendSubType(dataSelected, rowSubType, typeName) {

        if (dataSelected == 1) {
            typeName.text('Value added tax');
            $.each(T1SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 2) {
            typeName.text('Table tax (percentage)');
            $.each(T2SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 3) {
            typeName.text('Table tax (Fixed Amount)');
            $.each(T3SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 4) {
            typeName.text('Withholding tax (WHT)');
            $.each(T4SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 5) {
            typeName.text('Stamping tax (percentage)');
            $.each(T5SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 6) {
            typeName.text('Stamping Tax (amount)');
            $.each(T6SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 7) {
            typeName.text('Entertainment tax');
            $.each(T7SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 8) {
            typeName.text('Resource development fee');
            $.each(T8SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 9) {
            typeName.text('Service charges');
            $.each(T9SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 10) {
            typeName.text('Municipality Fees');
            $.each(T10SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 11) {
            typeName.text('Medical insurance fee');
            $.each(T11SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 12) {
            typeName.text('Other fees');
            $.each(T12SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 13) {
            typeName.text('Stamping tax (percentage)');
            $.each(T13SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 14) {
            typeName.text('Stamping Tax (amount)');
            $.each(T14SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 15) {
            typeName.text('Entertainment tax');
            $.each(T15SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 16) {
            typeName.text('Resource development fee	');
            $.each(T16SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 17) {
            typeName.text('Service charges');
            $.each(T17SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 18) {
            typeName.text('Municipality Fees');
            $.each(T18SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 19) {
            typeName.text('Medical insurance fee');
            $.each(T19SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        } else if (dataSelected == 20) {
            typeName.text('Other fees');
            $.each(T20SubTypes, function (index, value) {
                rowSubType.append($('<option>', {
                    value: value,
                    text: value
                }))
            });
        }
    }
});
