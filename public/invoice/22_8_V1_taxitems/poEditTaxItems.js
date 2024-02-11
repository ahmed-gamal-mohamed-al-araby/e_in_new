// var form = $("#PoForm");
var currentTaxRow = null;
$.validator.setDefaults({
    ignore: ':hidden, [readonly=readonly]'
});
const chooseOption = $('.tax-type:first option:first');
// Fire main.js content
(function ($) {
    var modal = $("#editItemsForm");
    var currencyModel = $("#addCurrency form");


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
            "tax_type[]":{
                required:true
            },
            "subtype[]":{
                required:true
            },
            "tax_rate[]":{
                required:true
            },
            "row_total_tax[]":{
                required:true
            },
            currency: {
                required: true,
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
            },
            "tax_type[]":{
                required: 'Tax type Required <i class="zmdi zmdi-info"></i>'
            },
            "subtype[]":{
                required: 'Tax subtype Required <i class="zmdi zmdi-info"></i>'
            },
            "tax_rate[]":{
                required: 'Tax rate Required <i class="zmdi zmdi-info"></i>'
            },
            "row_total_tax[]":{
                required: 'Total tax Required <i class="zmdi zmdi-info"></i>'
            },
            currency: {
                required: 'PLease select currency'
            },
        },
        onfocusout: function (element) {
            $(element).valid();
        },
    });

    currencyModel.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: {
            currencyType: {
                required: true,
            },
            currencyRate: {
                required: true,
            },
        },
        messages: {
            currencyType: {
                required: 'PLease enter type <i class="zmdi zmdi-info"></i>'
            },
            currencyRate: {
                required: 'PLease enter Rate <i class="zmdi zmdi-info"></i>'
            },
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

})(jQuery);


    if ($(".tax-items").length <= 1) {
        $('.delete_tax_row').hide();
    }
    // disable hide modal in click without close buttonx
    $('#edit-item').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });

    var itemId,
        addNewItem = false,
        itemUsedQuantityInDocuments = 0,
        itemUsedPriceInDocuments = 0,
        global_net_total = $('.add-invoice-items').find('.net_total'),
        discount_items_rate = $('.add-invoice-items').find('.discount_items_rate'),
        discount_items_number = $('.add-invoice-items').find('.discount_items_number'),

        T1SubTypes = ['V001', 'V002', 'V003', 'V004', 'V005', 'V006', 'V007', 'V008', 'V009', 'V010'],
        T2SubTypes = ['Tbl01'],
        T3SubTypes = ['Tbl02'],
        T4SubTypes = ['W001', 'W002', 'W003', 'W004', 'W005', 'W006', 'W007', 'W008', 'W009', 'W010', 'W011', 'W012', 'W013', 'W014', 'W015', 'W016'],
        T5SubTypes = ['ST01'],
        T6SubTypes = ['ST02'],
        T7SubTypes = ['Ent01', 'Ent02'],
        T8SubTypes = ['RD01', 'RD02'],
        T9SubTypes = ['SC01', 'SC02'],
        T10SubTypes = ['Mn01', 'Mn02'],
        T11SubTypes = ['MI01', 'MI02'],
        T12SubTypes = ['OF01', 'OF02'],
        T13SubTypes = ['ST03'],
        T14SubTypes = ['ST04'],
        T15SubTypes = ['Ent03', 'Ent04'],
        T16SubTypes = ['RD03', 'RD04'],
        T17SubTypes = ['SC03', 'SC04'],
        T18SubTypes = ['Mn03', 'Mn04'],
        T19SubTypes = ['MI03', 'MI04'],
        T20SubTypes = ['OF03', 'OF04'];

    // remove e in input type number

    $('input[type="number"]').keydown(function (e) {
        return e.keyCode == 69 ? false : true;
    });

    // add New Item
    $('.addNewItem').on('click', function () {
        $('.current-currency-text').text($('#currency').prev().text())

        // if budget quantity is one
        // if ($('#purchase-order-type').val() == 'budget') {
        //     $('.quantity').attr('disabled', true).val(1);
        // }

        // show item search in add item
        $('#item-search-container').show();
        addNewItem = true;
        $('#editItemsForm')[0].reset();

        $('#unit option').attr('selected', false);
        $('#unit option:first-child').attr('selected', true);

        $('#currency option').attr('selected', false);
        $('#currency option:first-child').attr('selected', true);
    })

    //  edit Item fire when click on edit button -> get item data and fill modal
    $('.editItem').click(function (e) {

        // remove label error which added becasuse open add then close
        $('label.error').hide();
        $('input.error').removeClass('error');
        $('select.error').removeClass('error');

        // hide item search in edit item
        // $('#item-search-container').hide();

        addNewItem = false;
        itemId = $(this).data('item-id');

        $.ajax({
            type: 'GET',
            url: `${subFolderURL}/${urlLang}/getPoItemById/` + itemId,
            success: function (response) {
                // $('.tax_rate').attr('disabled', false);
                var itemData = JSON.parse(response);

                // show PO item basic data
                itemUsedQuantityInDocuments = parseFloat(itemData.documentItemQuantities);
                itemUsedPriceInDocuments = parseFloat(itemData.documentItemPrices);
                $('#product_code').val(itemData.product_code);
                $('#product_name').val(itemData.product_name);
                $('#description').val(itemData.basicData.description);
                $('.quantity').val(itemData.basicData.quantity);
                if (purchaseOrderType == 'budget') {
                    $('.quantity').attr('disabled', true).val(1);
                }
                $('.unit').val(itemData.basicData.unit);
                $('.item_price ').val(itemData.basicData.item_price);
                $(`.currenty-type-select option`).attr('selected', false); // remove any select from previous event for edit model
                $(`.currenty-type-select option`).each(function (index, element) {
                    if ($(element).val() == itemData.basicData.currency) {
                        $(this).attr('selected', true);
                    }
                });
                $('.currenty-type-select').trigger('change');
                $('.item_price').val(itemData.basicData.item_price);
                $('.sales_amount').val((itemData.basicData.quantity * itemData.basicData.item_price).toFixed(5));

                if (itemData.basicData.discount_item_rate)
                    $('.discount_items_rate').val(itemData.basicData.discount_item_rate);
                else
                    $('.discount_items_number').val(itemData.basicData.discount_item_amount);

                $('.taxable_fees').val(itemData.basicData.taxable_fees);
                $('.differ_value').val(itemData.basicData.value_difference);
                $('.itemsDiscount').val(itemData.basicData.items_discount);
                $('.net_total').val(itemData.basicData.net_total);
                $('.total_amount').val(itemData.basicData.total_amount);

                // Add taxes
                itemData.taxes.forEach(tax => {
                    $('.delete_tax_row').show();

                    // remove option if selected
                    let $table = $('.tax-items-table');
                    let $top = $table.find('div.tax-items').first();
                    let $new = $top.clone(true);

                    $new.removeClass('d-none');
                    $table.append($new);

                    $new.data('tax-id', tax.id);

                    $new.find('.tax-type option').each((index, taxType) => {
                        if ($(taxType).val() == tax.tax_type) {
                            $(taxType).attr('selected', true);
                        }
                    })

                    $('.tax-items').not('.d-none').find('.tax-type').each(function (index, element) {
                        if ($(element).val() != null) {
                            $new.find(".tax-type option[value='" + $(element).val() + "']").hide();
                        }
                    });


                    appendSubType(tax.tax_type, $new.find('.subtype'), $new.find('.typeName'));

                    $new.find('.subtype option').each((index, subtype) => {
                        if ($(subtype).val() == tax.subtype) {
                            $(subtype).attr('selected', true);
                        }
                    })

                    if (tax.tax_rate !== null) {
                        $new.find('.tax_rate').val(tax.tax_rate);
                    }
                    else {
                        $new.find('.tax_rate').attr('readonly', true);
                        $new.find('.row_total_tax').val(tax.amount_tax).attr('readonly', false);
                    }
                    $('.quantity').trigger('change');
                })
            },
        });
    });

    // change currency
    $('.currenty-type-select').on('change', function () {
        $('.current-currency-text').text($(this).find(':selected').val());
    });

    //  add new tax row
    $("#add_tax_row").click(function (e) {  // Add tax row

        // remove option if selected
        let $table = $('.tax-items-table');
        let $top = $table.find('div.tax-items').first();
        let $new = $top.clone(true);

        $new.removeClass('d-none');
        $table.append($new);


        $('.tax-items').not('.d-none').find('.tax-type').each(function (index, element) {
            if ($(element).val() != null) {
                $new.find(".tax-type option[value='" + $(element).val() + "']").hide();
            }
        });

        resetTaxIds();
        taxesCounter($new);
        currentTaxRow = $new;

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

    //  close modal form
    $('#edit-item .close').click(function () {
        $('.tax-items').not('.d-none').remove();
    });

    // form submit to add new item
    $("#editItemsForm").submit(function (event) {

        event.preventDefault();
        item = { basicData: {}, taxes: [], taxesId: [] };
        if ($(this).valid()) {
            item.basicData.id = parseInt(itemId);
            item.basicData.description = $('#description').val();
            item.basicData.quantity = parseFloat($('.quantity').val());
            item.basicData.currency = $('.currenty-type-select').val();
            item.basicData.unit = $('.unit').val();
            item.basicData.item_price = parseFloat($('.item_price').val());
            item.basicData.discount_item_rate = parseFloat($('.discount_items_rate').val());
            item.basicData.discount_item_amount = parseFloat($('.discount_items_number').val());
            item.basicData.taxable_fees = parseFloat($('.taxable_fees').val());
            item.basicData.value_difference = parseFloat($('.differ_value').val());
            item.basicData.items_discount = parseFloat($('.itemsDiscount').val());
            item.basicData.net_total = parseFloat($('.net_total').val());
            item.basicData.total_amount = parseFloat($('.total_amount').val());
            item.basicData.product_id = $('#product_code').data('product-id');

            if (addNewItem) { // Add item
                delete item.basicData.id;
                item.basicData.purchase_order_id = $('#item-search-container').data('purchase-order-id');
                // item.basicData.product_id = $('#product_code').data('product-id');
                delete item.taxesId;
                $('.tax-items').not('.d-none').find('.remove_new_row_tax').each(function (index, element) {
                    let tax = {
                        item_id: item.basicData.id,
                        tax_type: $(element).parents('.tax-items').find('.tax-type').val(),
                        subtype: $(element).parents('.tax-items').find('.subtype').val(),
                        tax_rate: $(element).parents('.tax-items').find('.tax_rate').val(),
                        amount_tax: $(element).parents('.tax-items').find('.row_total_tax').val(),
                    }
                    item['taxes'].push(tax);
                });
                $(".save-form-PoItem").css("pointer-events", "none");
                $.ajax({
                    url: `${subFolderURL}/${urlLang}/poItems/storeIndividualItem`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(item),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {
                        window.location.href = response;
                    }
                });

            } else { // Update item
                $('.tax-items').not('.d-none').find('.remove_new_row_tax').each(function (index, element) {
                    let tax = {
                        id: $(element).parents('.tax-items').data('tax-id'),
                        item_id: item.basicData.id,
                        tax_type: $(element).parents('.tax-items').find('.tax-type').val(),
                        subtype: $(element).parents('.tax-items').find('.subtype').val(),
                        tax_rate: $(element).parents('.tax-items').find('.tax_rate').val(),
                        amount_tax: $(element).parents('.tax-items').find('.row_total_tax').val(),
                    }
                    item['taxes'].push(tax);
                    if ($(element).parents('.tax-items').data('tax-id'))
                        item['taxesId'].push($(element).parents('.tax-items').data('tax-id'));
                });
                let urlLang = window.location.href.includes('/ar/') ? 'ar' : 'en';
                $(".save-form-PoItem").css("pointer-events", "none");
                $.ajax({
                    url: `${subFolderURL}/${urlLang}/poItems/update`,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(item),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {
                        window.location.href = response;
                    }
                });
                ///////////////////////////////////////////////
            }
        }
    });

    // reset tax items counter
    let taxCounter = 0;
    function taxesCounter(object) {
        object.find('.remove_new_row_tax').data('taxId', taxCounter++);
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

    //  edit item
    $('.tableForItems tbody').on("click", '.tableItemsBtn.editItem', function (e) {
        $('#edit-item').data('checkData', 'data');
        checkDataInModal = $('#edit-item').data('checkData');
        itemeditIndex = $(this).data('itemId');
        $('#product_code').val(items[itemeditIndex].product_code);
        $('#product_name').val(items[itemeditIndex].product_name);
        $('#description').val(items[itemeditIndex].description);
        $('.quantity').val(items[itemeditIndex].quantity);
        $('.unit').val(items[itemeditIndex].unit);
        $('.item_price ').val(items[itemeditIndex].item_price);
        $('.sales_amount').val(items[itemeditIndex].sales_amount);
        $('.discount_items_rate').val(items[itemeditIndex].discount_items_rate);
        $('.discount_items_number').val(items[itemeditIndex].discount_items_number);
        $('.taxable_fees').val(items[itemeditIndex].taxable_fees);
        $('.differ_value').val(items[itemeditIndex].differ_value);
        $('.itemsDiscount').val(items[itemeditIndex].items_discount);
        $('.net_total').val(items[itemeditIndex].net_total);
        $('.total_amount').val(items[itemeditIndex].total_amount);

        let taxItemsCount = items[itemeditIndex].tax_items.length;
        let $table = '';
        let $top = '';
        let $new = '';
        let dataSelected = 0;
        let typeSelected = [];
        for (var i = 0; i < taxItemsCount; i++) {
            $('.delete_tax_row').show();
            $table = $('.tax-items-table');
            $top = $table.find('div.tax-items').first();
            $new = $top.clone(true);
            $new.removeClass('d-none');
            $table.append($new);
            $new.find('input[type=text]').val('');
            $new.find('input[type=number]').val('');

            $new.find('.tax-type option[value=' + items[itemeditIndex].tax_items[i].tax_type + ']').attr('selected', 'selected');

            typeSelected.push($new.find('.tax-type').find("option[value='" + items[itemeditIndex].tax_items[i].tax_type + "']").val());

            $new.find('.tax_rate').val(items[itemeditIndex].tax_items[i].tax_rate);
            $new.find('.row_total_tax').val(items[itemeditIndex].tax_items[i].row_total_tax);

            // here function to change sub type
            dataSelected = $new.find('.tax-type').val();
            appendSubType(dataSelected, ($new.find('.subtype')), $new.find('.typeName'));

            $new.find('.subtype option[value="' + items[itemeditIndex].tax_items[i].subtype + '"]').attr('selected', 'selected');
            if (dataSelected == 3 || dataSelected == 6) {
                $new.find('.tax_rate').prop('readonly', true);
                $new.find('.row_total_tax').prop('readonly', false);
                $new.find('.tax_rate').val('');
            }

        }

        // console.log(typeSelected);

        $.each(typeSelected, function (index, value) {

            // $('.tax-type').not($(this)).find('option[value="' + $(this).val() + '"]').hide();
            // $('.tax-type').find('option[value="' + $(value).val() + '"]').show();
            // alert( index + ": " + value );

        });
        $('.tax-items').not('.d-none').find('.subtype').each(function (index, element) {
            var dataSelected = $(element).parents('.tax-items').find('.tax-type').val();
            if (dataSelected == 3 || dataSelected == 6) {
                $new.find('.tax_rate').prop('readonly', true);
                $new.find('.row_total_tax').prop('readonly', false);
                $new.find('.tax_rate').val('');
            }
        });

    });
    let quantityErrorMessage = '';

    $('.quantity').on('focusout', function () {
        if (purchaseOrderType == 'quantity') {
            const that = $(this);
            that.val(that.val().trim());
            if (that.val().trim() == '')
                return;

            let value = that.val().trim();

            if (value) {
                if (!(value.match(/^\d*\.\d{0,5}$/) || value.match(/^\d+$/))) { // if not integer or decimal with more than 5 digits after decimal point (.)
                    value = roundNumberToNDigitsAfterDecimalPoint(value);
                    that.val(value);
                }
            }

            that.parent().next().addClass('d-none');

            if (quantityErrorMessage == '') {
                quantityErrorMessage = that.parent().next().removeClass('d-none').text().trim().split('()');
            }

            if (itemUsedQuantityInDocuments <= that.val()) {
                that.parent().next().addClass('d-none');
            }
            else {
                let errorMessage = [...quantityErrorMessage];
                errorMessage.splice(1, 0, ` (${itemUsedQuantityInDocuments}) `); // add item used quantity in documents
                errorMessage = errorMessage.join(' ');
                that.val('');
                that.parent().next().text(errorMessage).removeClass('d-none');
            }
        }

    });


    let itemPriceErrorMessage = '';
    $('.item_price').on('focusout', function () {
        if (purchaseOrderType == 'budget') {
            const that = $(this);
            that.val(that.val().trim());
            if (that.val().trim() == '')
                return;

            that.parent().next().addClass('d-none');

            if (itemPriceErrorMessage == '') {
                itemPriceErrorMessage = that.parent().next().removeClass('d-none').text().trim().split('()');
            }

            if (itemUsedPriceInDocuments <= that.val()) {
                that.parent().next().addClass('d-none');
            }
            else {
                let errorMessage = [...itemPriceErrorMessage];
                errorMessage.splice(1, 0, ` (${itemUsedPriceInDocuments}) `); // add item used quantity in documents
                errorMessage = errorMessage.join(' ');
                that.val('');
                that.parent().next().text(errorMessage).removeClass('d-none');
            }
        }

    });

    $('.quantity').on('keyup input change', function () { // items quantity
        $(this).parent().next().addClass('d-none');

        calcItemsTotalSales($(this));
        netTotalByQuantity();
    });

    $('.item_price').on('keyup input change', function () { // price of item
        $(this).parent().next().addClass('d-none');
        calcItemsTotalSales($(this));
        netTotalByQuantity();
    });

    function calcItemsTotalSales(row) {  // calc total salse of items => quantity * item price
        let quantity = row.parents().find('.quantity').val();
        let item_price = row.parents().find('.item_price').val();
        if ((item_price) != '' && (quantity) != '') {
            $('.price').parents().find('.sales_amount').val((parseFloat(quantity) * parseFloat(item_price).toFixed(5)));
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
            global_net_total.val((sales_amount - discount_value).toFixed(5));
        } else {
            discount_items_rate.val(null);
            global_net_total.val((sales_amount - discount_items_number.val()).toFixed(5));
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
            $('.total_amount').val((total + net_total - parseFloat(itemsDiscount)).toFixed(5));
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

        rowSubType.append(chooseOption);
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

    $('.tax_rate').on('input', function () {
        $(this).blur().focus();
    });

    // product search
    $('#internal_code').keypress(function (e) { // get item/product data by internal code
        var key = e.which;
        var searchContent = $(this).val();
        if (key == 13) {
            if (searchContent.replace(/^\s+|\s+$/g, "").length != 0) {
                e.preventDefault();
                $('.search-product.spinner-border').show();
                $('#product_name').val('');
                $('#product_code').val('');
                $('#description').val('');
                $('.vaild-product-register-tax').text("");
                $.ajax({
                    type: 'GET',
                    url: `${subFolderURL}/${urlLang}/getProductData/` + searchContent,
                    success: function (response) {
                        var responses = JSON.parse(response);
                        if (responses.length > 0) {
                            responses.forEach(element => {
                                $('#product_code').data('product-id', element['id']);
                                $('#product_code').val(element['product_code']);
                                $('#product_name').val(element['product_name']);
                                $('#description').val(element['description']);
                                $('.search-product.spinner-border').hide();
                                if ($('#product_code').val().length != 0) {
                                    $('#product_code').valid();
                                }
                                if ($('#product_name').val().length != 0) {
                                    $('#product_name').valid();
                                }
                            });
                        } else {
                            $('.vaild-product-register-tax').text(
                                " There are no results, check again!");
                        }

                    },
                    error: function () {
                        $('.vaild-product-register-tax').text("Error occured!");
                    },
                    complete: function () {
                        $('.search-product.spinner-border').hide();
                    }
                });
            } else {
                e.preventDefault();
                $('.vaild-product-register-tax').text("Please Enter Value!");
                $('#product_name').val('');
                $('#product_code').val('');
                $('#description').val('');
            }

        }

    });

function roundNumberToNDigitsAfterDecimalPoint(number) {
    var numberWith5Decimals = number.toString().match(/^\d+(?:\.\d{0,5})?/)[0]
    return numberWith5Decimals;
}
