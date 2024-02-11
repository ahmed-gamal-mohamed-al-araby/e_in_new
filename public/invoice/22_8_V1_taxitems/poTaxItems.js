var items = [];
var url = null, currentTaxRow = null;
$.validator.setDefaults({
    ignore: ':hidden, [readonly=readonly]'
});
const chooseOption = $('.tax-type:first option:first');
function setUrl(_url) {
    url = _url;
}

var form = $("#PoForm");
// Fire main.js content
(function ($) {
    var modal = $("#addItemsForm");
    var currencyModel = $("#addCurrency form");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: {
            client_type: {
                required: true,
            },
            client_id: {
                required: true,
            },
            type: {
                required: true
            },
            purchase_order_reference: {
                required: true,
            },
            project_name: {
                required: true
            },
            project_number: {
                required: true
            },
            project_contract_number: {
                required: true
            },
            payment_terms: {
                required: true
            },
            bank_id: {
                required: true
            },
            delivery_approach: {
                required: true
            },
            delivery_terms: {
                required: true
            },
            items_counter: {
                required: true
            },
            delivery_country_origin: {
                required: true
            },
        },
        messages: {
            client_type: {
                required: validationMessages['client_type']
            },
            client_id: {
                required: validationMessages['client_id']
            },
            type: {
                required: validationMessages['type']
            },
            purchase_order_reference: {
                required: validationMessages['purchase_order_reference']
            },
            project_name: {
                required: validationMessages['project_name']
            },
            project_number: {
                required: validationMessages['project_number']
            },
            project_contract_number: {
                required: validationMessages['project_contract_number']
            },
            payment_terms: {
                required: validationMessages['payment_terms']
            },
            bank_id: {
                required: validationMessages['bank_id']
            },
            delivery_approach: {
                required: validationMessages['delivery_approach']
            },
            delivery_terms: {
                required: validationMessages['delivery_terms']
            },
            items_counter: {
                required: validationMessages['items_counter']
            },
            delivery_country_origin: {
                required: validationMessages['delivery_country_origin']
            },
        },
        onfocusout: function (element) {
            $(element).valid();
        },
    });

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
                required: validationMessages['quantity'],
            },
            item_price: {
                required: validationMessages['item_price'],
            },
            product_code: {
                required: validationMessages['product_code'],
            },
            product_name: {
                required: validationMessages['product_name'],
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
                required: validationMessages['currency'],
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
                required: validationMessages['currency'],
            },
            currencyRate: {
                required: validationMessages['validate_currency_rate'],
            },
        },
        onfocusout: function (element) {
            $(element).valid();
        },
    });

    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        labels: {
            previous: 'Previous',
            next: 'Next',
            finish: 'Submit',
            current: ''
        },
        titleTemplate: '<div class="title"><span class="number">#index#</span>#title#</div>',
        onStepChanging: function (event, currentIndex, newIndex) {
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            $("select").prop("disabled", false);
            submitInvoice();
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

// $(window).on('load', function () {

    if ($(".tax-items").length <= 1) {
        $('.delete_tax_row').hide();
    }

    // disable hide modal in click without close buttonx
    $('#addline').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });

    // create items Object
    class Item {
        constructor(product_id, product_code = 0, product_name = '', description = '', quantity = 0,
            unit = '', currency = '', item_price = 0, sales_amount = 0, discount_items_rate = 0, discount_items_number = 0,
            taxable_fees = 0, differ_value = 0, items_discount = 0, net_total = 0, total_amount = 0, tax_items = []) {
            this.product_id = product_id;
            this.product_code = product_code;
            this.product_name = product_name;
            this.description = description;
            this.quantity = quantity;
            this.unit = unit;
            this.currency = currency;
            this.item_price = item_price;
            this.sales_amount = sales_amount;
            this.discount_items_rate = discount_items_rate;
            this.discount_items_number = discount_items_number;
            this.taxable_fees = taxable_fees;
            this.differ_value = differ_value;
            this.items_discount = items_discount;
            this.net_total = net_total;
            this.total_amount = total_amount;
            this.tax_items = tax_items;
        }

        setItem(product_id, product_code, product_name, description, quantity,
            unit, currency, item_price, sales_amount, discount_items_rate, discount_items_number,
            taxable_fees, differ_value, items_discount, net_total, total_amount) {

            this.product_id = product_id;
            this.product_code = product_code;
            this.product_name = product_name;
            this.description = description;
            this.quantity = quantity;
            this.unit = unit;
            this.currency = currency;
            this.item_price = item_price;
            this.sales_amount = sales_amount;
            this.discount_items_rate = discount_items_rate;
            this.discount_items_number = discount_items_number;
            this.taxable_fees = taxable_fees;
            this.differ_value = differ_value;
            this.items_discount = items_discount;
            this.net_total = net_total;
            this.total_amount = total_amount;
        }
    }
    // create taxes object
    class Tax {
        constructor(tax_type = 1, subtype = 1, tax_rate = 1, row_total_tax = 1) {
            this.tax_type = tax_type;
            this.subtype = subtype;
            this.tax_rate = tax_rate;
            this.row_total_tax = row_total_tax;
        }


        setTax(tax_type, subtype, tax_rate, row_total_tax) {
            this.tax_type = tax_type;
            this.subtype = subtype;
            this.tax_rate = tax_rate;
            this.row_total_tax = row_total_tax;
        }
    }

    var global_net_total = $('.add-invoice-items').find('.net_total');
    var discount_items_rate = $('.add-invoice-items').find('.discount_items_rate');
    var discount_items_number = $('.add-invoice-items').find('.discount_items_number');
    var tableRowCount = 0;
    var checkDataInModal = null;
    var itemeditIndex = 0;

    const T1SubTypes = ['V001', 'V002', 'V003', 'V004', 'V005', 'V006', 'V007', 'V008', 'V009', 'V010'],
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

    // Search by product internal code in add item modal
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

    //  Add new item (open modal)
    $('.addNewItem').click(function (e) {
        $('.current-currency-text').text($('#currency').prev().text());
        $('#addItemsForm')[0].reset();

        // // if budget quantity is one
        if ($('#purchase-order-type').val() == 'budget') {
            $('.quantity').attr('readonly', true).val(1);
            $('#unit').attr('disabled', true).val('C62'); // C62 for lumsum unit type
        }

        tableRowCount = $('.tableForItems tbody tr').length;// 🚩
        items.push(new Item());
        $('#addline').data('checkData', 'null');
        checkDataInModal = $('#addline').data('checkData');
    });

    $('.currenty-type-select').on('change', function () {
        $('.quantity').trigger('keyup');
        $('.current-currency-text').text($(this).find(':selected').text());
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

        // Push new tax for specific item
        items[tableRowCount].tax_items.push(new Tax());

        resetTaxIds();
        taxesCounter($new);
        currentTaxRow = $new;
    });

    // remove tax row
    $(".delete_tax_row").click(function (e) { // delete tax row
        let taxType = $(this).parents('.tax-items').find('.tax-type').val();
        $('.tax-items').find(".tax-type option[value='" + taxType + "']").show();

        items[tableRowCount].tax_items.splice($(this).data('taxId'), 1);
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
    $('#addline .close').click(function () {
        if (checkDataInModal == 'null') {
            items.pop();
            $('.tax-items').not('.d-none').remove();
            $('#addline').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });
            $('#addItemsForm').validate().resetForm();
        } else {
            $('#addline').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });
            $('#addline').modal('hide');
            $('.tax-items').not('.d-none').remove();
        }
        $('.tax-type').find('option').show();
    });

    // disable submit in enter key
    $('#addItemsForm input').not('#internal_code').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });

    // form submit to add new item
    $("#addItemsForm").submit(function (event) {
        if ($('#purchase-order-type').val() == 'budget' && items.length == 1)
            $('.addNewItem').hide();

        event.preventDefault();
        if (items.length == 0)
            $('#items').val('');
        else
            $('#items').val(items.length);
        $('#items').trigger('keyup');

        if ($(this).valid()) {
            let product_id = $('#product_code').data('product-id');
            let product_code = $('#product_code').val();
            let product_name = $('#product_name').val();
            let description = $('#description').val();
            let quantity = $('.quantity').val();
            let unit = $('.unit').val();
            let currency = $('.currenty-type-select').val();
            let item_price = $('.item_price ').val();
            let sales_amount = $('.sales_amount').val();
            let discount_items_rate = $('.discount_items_rate').val();
            let discount_items_number = $('.discount_items_number').val();
            let taxable_fees = $('.taxable_fees').val();
            let differ_value = $('.differ_value').val();
            let items_discount = $('.itemsDiscount').val();
            let net_total = $('.net_total').val();
            let total_amount = $('.total_amount').val();

            if (checkDataInModal == 'null') {
                items[tableRowCount].setItem(product_id, product_code, product_name, description, quantity,
                    unit, currency, item_price, sales_amount, discount_items_rate, discount_items_number,
                    taxable_fees, differ_value, items_discount, net_total, total_amount);

                let taxCount = 0;
                let taxType = null;
                let subtype = null;
                let tax_rate = null;
                let row_total_tax = null;
                $('.tax-items').not('.d-none').find('.remove_new_row_tax').each(function (index, element) {
                    taxType = $(element).parents('.tax-items').find('.tax-type').val();
                    subtype = $(element).parents('.tax-items').find('.subtype').val();
                    tax_rate = $(element).parents('.tax-items').find('.tax_rate').val();
                    row_total_tax = $(element).parents('.tax-items').find('.row_total_tax').val();
                    taxCount = $(element).data('taxId');
                    items[tableRowCount].tax_items[taxCount].setTax(taxType, subtype, tax_rate, row_total_tax);
                });

                $('.tax-items').not('.d-none').remove();

                let markup = `<tr>
                    <th> ${tableRowCount + 1}</th>
                    <td> ${items[tableRowCount].product_code}</td>
                    <td>${items[tableRowCount].product_name}</td>
                    <td>${items[tableRowCount].quantity}</td>
                    <td>${items[tableRowCount].unit}</td>
                    <td>${parseFloat(items[tableRowCount].item_price).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${parseFloat(items[tableRowCount].sales_amount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${items[tableRowCount].discount_items_rate}</td>
                    <td>${items[tableRowCount].discount_items_number? parseFloat(items[tableRowCount].discount_items_number).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }): ''}</td>
                    <td>${items[tableRowCount].taxable_fees? parseFloat(items[tableRowCount].taxable_fees).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }): ''}</td>
                    <td>${items[tableRowCount].differ_value? parseFloat(items[tableRowCount].differ_value).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }): ''}</td>
                    <td>${items[tableRowCount].items_discount? parseFloat(items[tableRowCount].items_discount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }): ''}</td>
                    <td>${parseFloat(items[tableRowCount].net_total).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${parseFloat(items[tableRowCount].total_amount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>
                        <button type="button" class="btn btn-danger tableItemsBtn deleteItem" data-item-id="${tableRowCount}"><i class="fa fa-trash"></i></button>
                        <button type="button" data-toggle="modal" data-target="#addline" class="btn btn-warning tableItemsBtn editItem" data-item-id="${tableRowCount}"><i class="fa fa-edit"></i></button>
                    </td></tr>`;

                $(".tableForItems tbody").append(markup);


            } else {
                items[itemeditIndex].setItem(product_id, product_code, product_name, description, quantity,
                    unit, currency, item_price, sales_amount, discount_items_rate, discount_items_number,
                    taxable_fees, differ_value, items_discount, net_total, total_amount);

                let taxCount = 0;
                let taxType = null;
                let subtype = null;
                let tax_rate = null;
                let row_total_tax = null;
                $('.tax-items').not('.d-none').find('.remove_new_row_tax').each(function (index, element) {
                    taxType = $(element).parents('.tax-items').find('.tax-type').val();
                    subtype = $(element).parents('.tax-items').find('.subtype').val();
                    tax_rate = $(element).parents('.tax-items').find('.tax_rate').val();
                    row_total_tax = $(element).parents('.tax-items').find('.row_total_tax').val();
                    items[itemeditIndex].tax_items[index].setTax(taxType, subtype, tax_rate, row_total_tax);
                });
                $('.tax-items').not('.d-none').remove();
                const currentEditRow = $(".tableForItems tbody tr:nth-child(" + (itemeditIndex + 1) + ")"); // get current edit item (tr) in table
                let markup = `
                    <th>${itemeditIndex + 1}</th>
                    <td>${items[itemeditIndex].product_code}</td>
                    <td>${items[itemeditIndex].product_name}</td>
                    <td>${items[itemeditIndex].quantity}</td>
                    <td>${items[itemeditIndex].unit}</td>
                    <td>${parseFloat(items[itemeditIndex].item_price).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${parseFloat(items[itemeditIndex].sales_amount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${items[itemeditIndex].discount_items_rate}</td>
                    <td>${items[itemeditIndex].discount_items_number? parseFloat(items[itemeditIndex].discount_items_number).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''}</td>
                    <td>${items[itemeditIndex].taxable_fees? parseFloat(items[itemeditIndex].taxable_fees).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }): ''}</td>
                    <td>${items[itemeditIndex].differ_value? parseFloat(items[itemeditIndex].differ_value).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }): ''}</td>
                    <td>${items[itemeditIndex].items_discount? parseFloat(items[itemeditIndex].items_discount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }): ''}</td>
                    <td>${parseFloat(items[itemeditIndex].net_total).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${parseFloat(items[itemeditIndex].total_amount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>
                        <button type="button" class="btn btn-danger tableItemsBtn deleteItem" data-item-id="${itemeditIndex}"><i class="fa fa-trash"></i></button>
                        <button type="button" data-toggle="modal" data-target="#addline" class="btn btn-warning tableItemsBtn editItem" data-item-id="${itemeditIndex}"><i class="fa fa-edit"></i></button>
                    </td>`;
                currentEditRow.html(markup);
            }
            
            $('#addline').modal('hide');

            $('#addline').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });
        }
        $('.tax-type').find('option').show();

        total = sumOfTotalItemAmount() || 0;

        $('#purchase-order-total').val(total.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    });

    // reset tax items counter
    let taxCounter = 0;
    function taxesCounter(object) {
        var taxId = object.find('.remove_new_row_tax').data('taxId', taxCounter++);
    }

    // Reset Item counter
    // function resetItemCounter() {
    //     $('.tax-items').not('.d-none').find('.remove_new_row_tax')
    //         .each(function (index, element) {
    //             $(element).data('taxId', index).next().text(index);
    //         });
    //     taxCounter = $('.tax-items').not('.d-none').length - 1;
    // }

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

        if ($('#purchase-order-type').val() == 'budget')
            $('.addNewItem').show();

        if (items.length == 0)
            $('#items').val('');
        else
            $('#items').val(items.length);
        // $('#items').trigger('keyup');

        resetItemCounter();
        total = sumOfTotalItemAmount() || 0;

        $('#purchase-order-total').val(total.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    });

    //  edit item
    $('.tableForItems tbody').on("click", '.tableItemsBtn.editItem', function (e) {
        $('#addItemsForm')[0].reset();
        $('#addline').data('checkData', 'data');
        checkDataInModal = $('#addline').data('checkData');
        itemeditIndex = $(this).data('itemId');
        $('#product_code').data('product-id', items[itemeditIndex].product_id);
        $('#product_code').val(items[itemeditIndex].product_code);
        $('#product_name').val(items[itemeditIndex].product_name);
        $('#description').val(items[itemeditIndex].description);
        $('.quantity').val(items[itemeditIndex].quantity);
        $('.unit').val(items[itemeditIndex].unit);
        $('.currenty-type-select').val(items[itemeditIndex].currency);
        $('.currenty-type-select').trigger('change')
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

    $('.quantity').on('keyup input change', function () { // items quantity
        const that = $(this);
        let value = that.val().trim();

        if (value) {
            if (!(value.match(/^\d*\.\d{0,5}$/) || value.match(/^\d+$/))) { // if not integer or decimal with more than 5 digits after decimal point (.)
                value = roundNumberToNDigitsAfterDecimalPoint(value);
                that.val(value);
            }
        }

        calcItemsTotalSales(that);
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
            $('.price').parents().find('.sales_amount').val((parseFloat(quantity) * parseFloat(item_price)).toFixed(5));
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


    form.submit(function (e) {
        e.preventDefault();
    });

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
// });

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

function submitInvoice() {

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    // Send ajax for creating PO
    const purchaseOrder = {
        basicData: form.serializeArray(),
        items: items,
        fileStatus: $('#purchaseorder_document').val() ? true : false,
    }
    $(".actions a[href$='#finish']").css("pointer-events", "none");
    $(".actions a[href$='#finish']").text(language['send_data']);
    $.ajax({
        url: `${subFolderURL}/${urlLang}/purchaseorders`,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify(purchaseOrder),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (PO_id) {
            if (PO_id) { // If the purchase order is recorded
                if ($("input[name=purchaseorder_document]").val()) {
                    // Send ajax for uploading PO file
                    var files = $("input[name=purchaseorder_document]")[0].files;
                    var formObject = new FormData();
                    formObject.append('file', files[0])
                    formObject.append('PO_id', PO_id);
                    $.ajax({
                        url: `${subFolderURL}/${urlLang}/purchaseorders/fileStore`,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formObject,
                        dataType: 'JSON',
                        // cache: false,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        processData: false,
                        success: function (res) {
                            console.log(res);
                            $(".actions a[href$='#finish']").text(language['data_sent']);
                            window.location.href = url;
                        },
                        error: function (request, status, error) {
                            $(".actions a[href$='#finish']").text(language['send_data_error']);
                        },
                    });
                } else {
                    $(".actions a[href$='#finish']").text(language['data_sent']);
                    window.location.href = url;
                }

            } else {
                $(".actions a[href$='#finish']").css("pointer-events", "none");
                $(".actions a[href$='#finish']").text(language['send_data_error']);
            }

        },
        error: function (request, status, error) {
            $(".actions a[href$='#finish']").text(language['send_data_error']);
        }
    });

}

$(".actions a[href$='#next']").on('click', function () {
    // $('.purchase_order_reference_used_before_error').addClass('d-none');
})

$(".actions a[href='#previous']").on('click', function () {
    $(".actions a[href$='#finish']").css("pointer-events", "");
    $(".actions a[href$='#finish']").text(language['save']);
})

let purchaseOrderReferenceErrorMessage = '';
$('#purchase_order_reference').on('focusout', function () {
    const that = $(this);
    that.val(that.val().trim());

    const data = {
        'purchase_order_reference' : $(this).val(),
    }
    
    if (that.val().trim() == '')
        return;

    if (purchaseOrderReferenceErrorMessage == '') {
        purchaseOrderReferenceErrorMessage = that.parent().find('.purchase_order_reference_used_before_error').text().trim().split(' ');
    }
    $.ajax({
        type: 'post',
        url: `${subFolderURL}/${urlLang}/purchaseorders/check_purchase_order_reference`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        data: JSON.stringify(data),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',

        success: function (purchaseOrderReferenceCounter) {
            if (purchaseOrderReferenceCounter == 0) {
                that.parent().find('.purchase_order_reference_used_before_error').addClass('d-none');
            }
            else {
                let errorMessage = [...purchaseOrderReferenceErrorMessage];
                errorMessage.splice(3, 0, ` (${that.val()}) `); // add entered purchase order reference
                errorMessage = errorMessage.join(' ');
                that.val('');
                that.parent().find('.purchase_order_reference_used_before_error').text(errorMessage).removeClass('d-none');
            }
        }
    });

})

$('#purchase_order_reference').on('keyup', function () {
    $(this).parent().find('.purchase_order_reference_used_before_error').addClass('d-none');
})

$('#purchase-order-type').on('change', function () {
    // reset items
    items = [];
    $(".tableForItems tbody").html(''); // clear item in table
    $('#purchase-order-total').val(''); // sum of total purchase items
    $('#items').val(''); // number of addeditem

    if ($('#purchase-order-type').val() == 'budget') {
        $('.quantity').attr('readonly', true).val(1);
        $('#unit').attr('disabled', true).val('C62'); // C62 for lumsum unit type
    } else {
        $('.quantity').attr('readonly', false).val('');
        $('#unit').attr('disabled', false).prop('selectedIndex', 0);
        $('.addNewItem').show();
    }
})

// Reset Item counter
function resetItemCounter() {
    const addItemsInTable = $(".tableForItems tbody tr");
    addItemsInTable.each((index, item) => {
        $(item).find('.editItem').data('item-id', index); // change item-id edit button
        $(item).find('.deleteItem').data('item-id', index);  // change item-id delete button
        addItemsInTable.eq(index).find('th').text(index + 1); // Change item index in table
    });
    numberOfAddedItems = $('.tableForItems tbody tr').length;
}

function sumOfTotalItemAmount() {
    let total = 0;
    items.forEach(item => {
        total += parseFloat(item.total_amount);
    });
    return total;
}

function roundNumberToNDigitsAfterDecimalPoint(number) {
    var numberWith5Decimals = number.toString().match(/^\d+(?:\.\d{0,5})?/)[0]
    return numberWith5Decimals;
}
