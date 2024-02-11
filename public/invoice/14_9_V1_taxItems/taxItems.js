// const { first } = require("lodash");

let items = [],
    form = $("#documentForm"),
    documentItemQuantities = 0, // Get used quantity of item in document
    documentItemPrices = 0, // Get used budget price of item in document
    POQuantities = 0, // Get quantity of item in purchase order
    POPrices = 0, // Get budget of item in purchase order
    POItemId = 0, // Contain (selected) item id in purchase oreder to get its data
    documentItemId = 0, // Contain saved item id in document to get edit it
    editDocumentMode = null, // Modal mode to check status add or edit
    documentId = null, // Contain document ID
    purchaseOrderTaxIds = [], // Contain all purchase oreder tax ids
    addNewItemInEditMode = false,
    documentType;


// Variables for language
language = [];
language['selectItemPlaceholder'] = null; // value of item select placeholder (@lang('site.choose_item'))

// Fire main.js content
(function ($) {
    var modal = $("#addItemsForm");
    var currencyModel = $("#addCurrency form");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: {
            purchase_order_id: {
                required: true,
            },
            company_id: {
                required: true,
            },
            client_type: {
                required: true,
            },
            client_id: {
                required: true,
            },
            type: {
                required: true,
            },
            version: {
                required: true,
            },
            items_counter: {
                required: true
            },
        },
        messages: {

            purchase_order_id: {
                required: 'PLease select perchase order <i class="zmdi zmdi-info"></i>'
            },
            company_id: {
                required: 'PLease select company <i class="zmdi zmdi-info"></i>'
            },
            client_type: {
                required: 'PLease select client_type <i class="zmdi zmdi-info"></i>'
            },
            client_id: {
                required: 'PLease select client_type <i class="zmdi zmdi-info"></i>'
            },
            type: {
                required: 'PLease select type <i class="zmdi zmdi-info"></i>'
            },
            version: {
                required: 'PLease select version <i class="zmdi zmdi-info"></i>'
            },
            items_counter: {
                required: 'Please enter item as items counter must be at least 1  <i class="zmdi zmdi-info"></i>'
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
            item_id: {
                required: true,
            },
            curreny_rate: {
                required: true,
            },
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
            // "tax_rate[]":{
            //     required:true
            // }
        },
        messages: {
            item_id: {
                required: 'PLease select Item <i class="zmdi zmdi-info"></i>'
            },
            curreny_rate: {
                required: 'PLease enter rate <i class="zmdi zmdi-info"></i>'
            },
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

// item_id in [class item]: contain id of item in document if in edit mode
// tax_items: contain basic tax data [class Tax] as (tax_type: 1, subtype: "V001", tax_rate: 10, row_total_tax: 315)
// temp_tax_items: contain actual data [class Temp_Tax] as (purchase_order_tax_id: 3, amount_tax: 105)
class Item {
    constructor(item_id = 0, product_code = 0, product_name = '', description = '', quantity = 0, currency = 0, rate = 0,
        unit = '', item_price = 0, sales_amount = 0, discount_items_rate = 0, discount_items_number = 0,
        taxable_fees = 0, differ_value = 0, items_discount = 0, net_total = 0, total_amount = 0, tax_items = [], temp_tax_items = []) {
        this.id = null;
        this.item_id = item_id;
        this.product_code = product_code;
        this.product_name = product_name;
        this.description = description;
        this.quantity = quantity;
        this.currency = currency;
        this.rate = rate;
        this.unit = unit;
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
        this.temp_tax_items = temp_tax_items;
    }

    setItem(item_id, product_code, product_name, description, quantity, currency, rate,
        unit, item_price, sales_amount, discount_items_rate, discount_items_number,
        taxable_fees, differ_value, items_discount, net_total, total_amount) {

        this.item_id = item_id;
        this.product_code = product_code;
        this.product_name = product_name;
        this.description = description;
        this.quantity = quantity;
        this.currency = currency;
        this.rate = rate;
        this.unit = unit;
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

class Temp_Tax {
    constructor(purchase_order_tax_id = 1, amount_tax = 1) {
        this.purchase_order_tax_id = purchase_order_tax_id;
        this.amount_tax = amount_tax;
    }

    setTemp_Tax(purchase_order_tax_id, amount_tax) {
        this.purchase_order_tax_id = purchase_order_tax_id;
        this.amount_tax = amount_tax;
    }
}

var itemInPurchaseOrder = []; // Contain items of specific purchase order
var purchaseOrderType = ''; // Contain purchase order type either 'budget' or 'quantity'
var global_net_total = $('.add-invoice-items').find('.net_total'); // Contain Net Total for specific item
var discount_items_rate = $('.add-invoice-items').find('.discount_items_rate'); // Contain Discount Rate for specific item
var discount_items_number = $('.add-invoice-items').find('.discount_items_number'); // Contain Discount Amount for specific item
var numberOfAddedItems = 0; // Contain number of added items
var checkDataInModal = null; // bool for check model opened for add or edit
var itemeditIndex = 0; // Contain the index of item in array item is gotten from item edit button data-item-id=.....

// Subtypes for each main tax type
const T1SubTypes = ['choose', 'V001', 'V002', 'V003', 'V004', 'V005', 'V006', 'V007', 'V008', 'V009', 'V010'],
    T2SubTypes = ['choose', 'Tbl01'],
    T3SubTypes = ['choose', 'Tbl02'],
    T4SubTypes = ['choose', 'W001', 'W002', 'W003', 'W004', 'W005', 'W006', 'W007', 'W008', 'W009', 'W010', 'W011', 'W012', 'W013', 'W014', 'W015', 'W016'],
    T5SubTypes = ['choose', 'ST01'],
    T6SubTypes = ['choose', 'ST02'],
    T7SubTypes = ['choose', 'Ent01', 'Ent02'],
    T8SubTypes = ['choose', 'RD01', 'RD02'],
    T9SubTypes = ['choose', 'SC01', 'SC02'],
    T10SubTypes = ['choose', 'Mn01', 'Mn02'],
    T11SubTypes = ['choose', 'MI01', 'MI02'],
    T12SubTypes = ['choose', 'OF01', 'OF02'],
    T13SubTypes = ['choose', 'ST03'],
    T14SubTypes = ['choose', 'ST04'],
    T15SubTypes = ['choose', 'Ent03', 'Ent04'],
    T16SubTypes = ['choose', 'RD03', 'RD04'],
    T17SubTypes = ['choose', 'SC03', 'SC04'],
    T18SubTypes = ['choose', 'Mn03', 'Mn04'],
    T19SubTypes = ['choose', 'MI03', 'MI04'],
    T20SubTypes = ['choose', 'OF03', 'OF04'];

var currency = ['EGY'];

$(window).on('load', function () {

    // Disable hide model when click outside model
    $('#addline').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });

    // Get all items in purchase order when select one of client pruchase order
    $('#select_purchase_order').on('change', function () {

        const data = {
            'purchase_order_reference': $(this).val(),
        }

        $('.addNewItem').addClass('d-none');
        $.ajax({
            type: 'post',
            url: `${subFolderURL}/${urlLang}/getProductFromPurchaseOrder`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            data: JSON.stringify(data),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',

            success: function (response) {
                const data = (response);
                itemInPurchaseOrder = data.items;
                purchaseOrderType = data.type;

                // Fill purchase order item in item select
                $('#item_select').prop('selectedIndex', 0);
                $('#item_select :not(:first)').remove();
                $('#item_select option').attr('disabled', true);

                for (let i = 0; i < itemInPurchaseOrder.length; i++) {
                    if (itemInPurchaseOrder[i].description)
                        $('#item_select').append(`<option value="${itemInPurchaseOrder[i].id}">${itemInPurchaseOrder[i].internal_code} - ${itemInPurchaseOrder[i].description}</option>`);
                    else
                        $('#item_select').append(`<option value="${itemInPurchaseOrder[i].id}">${itemInPurchaseOrder[i].internal_code}</option>`);
                }

                $('.addNewItem').removeClass('d-none');

                if (editDocumentMode) {
                    $(`#item_select option`).attr('selected', false);
                    $(`#item_select option[value=${items[itemeditIndex].item_id}]`).attr('selected', true);
                    if ($('#item_select').hasClass('select2-hidden-accessible')) {
                        $('#item_select').select2('destroy');
                        $('#item_select').prop('disabled', true);
                    } else {
                        $('#item_select').prop('disabled', true);
                    }
                    getDocumentPriceAndQuantity();

                    if (addNewItemInEditMode) {
                        $('#item_select').prop('selectedIndex', 0); // Reset selection to view placeholder
                        $('#item_select').prop('disabled', false);
                        $('#item_select').select2();
                        $('#item_select').select2({
                            placeholder: language['selectItemPlaceholder'],
                        });
                    }

                }
            },
            error: function () {
                $('.vaild-company-register-tax').text("{{ trans('site.error') }}");
            },
            complete: function () {
                $('.search-company.spinner-border').hide();
            }
        });
    });

    // Get data of selected items in modal
    $('#item_select').on('change', function (e) {
        POItemId = $(this).val(); // Get id of selected item

        // Reset modal inputs
        $('#quantity').attr('readonly', true);
        $('#description').attr('readonly', true);
        $('.rate').attr('readonly', true);
        $('.item_price').attr('readonly', true);

        // Reset modal old error
        $('.quantity').removeClass('error');
        $('#quantity-error').hide();

        $('.item_price').removeClass('error');
        $('#item_price-error').hide();

        $('#product_code').val('');
        $('#product_name').val('');
        $('#description').val('');
        $('#quantity').val('');
        $('#item_unit').val('');
        $('#item_price').val('');
        $('#sales_amount').val('');
        $('.discount_items_rate').val('');
        $('.discount_items_number').val('');
        $('#taxable_fees').val('');
        $('#differ_value').val('');
        $('#items_discount').val('');
        $('#net_total').val('');
        $('#total_amount').val('');
        e.preventDefault();

        $.ajax({
            type: 'GET',
            url: `${subFolderURL}/${urlLang}/getSelectedItemFromPurchaseOrder/` + POItemId,
            success: function (response) {
                // Reset previous validation errors
                $('.validate').addClass('d-none');
                $('.item_price').removeClass('error');
                $('.rate').removeClass('error');
                $('#quantity').removeClass('error');
                $('label.error').hide();

                $('#description').attr('readonly', false);


                // $('.currenty input:not(:first)').val('');
                var allItemData = JSON.parse(response);
                $('#addline').data('data-document-item-id', allItemData.item.id); // get current PO item ID

                // Assign currency from item data
                $('.currenty input').first().val(allItemData.item.currency);
                $('.current-currency-text').text($('.currency').val());

                // Check if currency is EGP disable rate and set it to 1
                var currencyType = $('.currenty input').first().val();
                if (currencyType == "EGP") {
                    $('.rate').attr('readonly', true)
                    $('.rate').val(1);
                } else {
                    $('.rate').attr('readonly', false)
                    $('.rate').val('');
                }

                $('#product_code').val(allItemData.product.product_code); // Assign product_code from item data
                $('#product_name').val(allItemData.product.product_name); // Assign product_name from item data
                $('#description').val(allItemData.item.description); // Assign description from item data
                $('#item_unit').val(allItemData.item.unit); // Assign item_unit from item data


                $('.discount_items_rate').val(allItemData.item.discount_item_rate); // Assign discount_items_rate from item data
                $('.discount_items_number').val(allItemData.item.discount_item_amount); // Assign discount_items_number from item data

                // Check if purchase order is budget or quantity
                if (purchaseOrderType == 'budget') {
                    $('.item_price').attr('readonly', false);
                    $('#quantity').attr('readonly', true);
                    $('#quantity').val(1);

                } else {
                    $('.item_price').attr('readonly', true);
                    $('#quantity').attr('readonly', false);
                    $('#quantity').val('');
                    $('#item_price').val(allItemData.item.item_price);
                }

                $('#differ_value').val(allItemData.item.value_difference); // Assign differ_value (value difference) from item data
                $('#items_discount').val(allItemData.item.items_discount); // Assign items_discount from item data

                documentItemQuantities = allItemData.documentItemQuantities;
                documentItemPrices = allItemData.documentItemPrices;

                POQuantities = allItemData.item.quantity;
                POPrices = allItemData.item.item_price;

                let taxTable = $('.tax-items-table');
                let tempTaxRow = taxTable.find('div.tax-items').first();
                let newTaxRow;

                taxTable.find('div.tax-items:not(:first)').remove(); // Remove old added tax rows (taxes)

                purchaseOrderTaxIds = []; // Reset array
                allItemData.taxes.forEach(tax => {
                    // Add tax row
                    newTaxRow = tempTaxRow.clone(true);
                    newTaxRow.removeClass('d-none');
                    taxTable.append(newTaxRow);

                    // Set tax type
                    newTaxRow.find('.tax-type option').each((index, option) => {
                        if ($(option).val() == tax.tax_type)
                            $(option).attr('selected', true);
                        else
                            $(option).remove();

                        appendSubType(tax.tax_type, (newTaxRow.find('.subtype')), newTaxRow.find('.typeName'));
                    });

                    // Set tax subtype
                    newTaxRow.find('.subtype option').each((index, option) => {
                        if ($(option).val() == tax.subtype)
                            $(option).attr('selected', true);
                        else
                            $(option).remove();
                    });

                    newTaxRow.find('.tax_rate').val(tax.tax_rate).attr('readonly', true); // Set tax rate

                    newTaxRow.find('.row_total_tax').val((tax.tax_type == 3 || tax.tax_type == 6) ? tax.amount_tax : '').attr('readonly', true);  // Set amount tax (row total tax)

                    purchaseOrderTaxIds.push(tax.id);
                    //     let temp_Tax = new Temp_Tax();
                    //     temp_Tax.setTemp_Tax(tax.id, 0);
                    //     items[numberOfAddedItems].temp_tax_items.push(temp_Tax);
                });
            }
        });
    });

    //  Add new item button (To open modal)
    $('.addNewItem').click(function (e) {
        // Reset modal inputs
        $('#quantity').attr('readonly', true);
        $('#description').attr('readonly', true);
        $('.rate').attr('readonly', true);
        $('.item_price').attr('readonly', true);

        // Reset modal old error
        $('.quantity').removeClass('error');
        $('#quantity-error').hide();

        $('.item_price').removeClass('error');
        $('#item_price-error').hide();

        addNewItemInEditMode = false;
        // $('.current-currency-text').text($('.currency').prev().text());

        const modelTitle = $('#exampleModalLongTitle'); // Get modal title
        modelTitle.text(modelTitle.data('add-item')); // Change modal title to be (Add new item) as in add mode
        modelTitle.data('add-status', '1'); // // Change modal data-add-status to be (1) as in add mode

        // Add placeholder for item select
        $('#item_select').prop('selectedIndex', 0); // Reset selection to view placeholder
        $('#item_select').prop('disabled', false);
        $('#item_select').select2();
        $('#item_select').select2({
            placeholder: language['selectItemPlaceholder'],
        });

        // $('.validate').addClass('d-none');

        if (editDocumentMode) { // As in edit we don't select purchase order to get its items
            addNewItemInEditMode = true;
            $("#select_purchase_order").trigger('change');
        }

        numberOfAddedItems = $('.tableForItems tbody tr').length;
        items.push(new Item());
        $('#addline').data('checkData', 'null');

        checkDataInModal = $('#addline').data('checkData');
    });

    //  Close modal button new (To cancel item addition)
    $('#addline .close').click(function () {
        $('.validate').addClass('d-none');
        if (checkDataInModal == 'null') {
            items.pop();
            $('#addItemsForm').validate().resetForm(); // Reset old validation error
        } else {
            $('#addline').modal('hide');
        }

        $('.tax-items').not('.d-none').remove(); // Remove old tax rows
        $('#addline').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });

        $('.tax-type').find('option').show(); // show all main tax type, because some of them hidden as they used before
    });

    // Disable submit modal form while entering key
    $('#addItemsForm input').not('#por_item').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });

    // Submit modal (To save item data)
    $("#addItemsForm").submit(function (event) {
        event.preventDefault();
        if (items.length == 0)
            $('#items').val('');
        else
            $('#items').val(items.length);
        $('#items').trigger('keyup');

        if ($(this).valid()) {
            let product_code = $('#product_code').val();
            let product_name = $('#product_name').val();
            let description = $('#description').val();
            let quantity = $('.quantity').val();
            let currency = $('.currency').val();
            let rate = $('.rate').val();
            let unit = $('#item_unit').val();
            let item_price = $('.item_price').val();
            let sales_amount = $('.sales_amount').val();
            let discount_items_rate = $('.discount_items_rate').val();
            let discount_items_number = $('.discount_items_number').val();
            let taxable_fees = $('.taxable_fees').val();
            let differ_value = $('.differ_value').val();
            let items_discount = $('.itemsDiscount').val();
            let net_total = $('.net_total').val();
            let total_amount = $('.total_amount').val();
            $('#item_select').trigger('change');

            // Add new item
            if (checkDataInModal == 'null') {
                items[numberOfAddedItems].setItem(POItemId, product_code, product_name, description, quantity, currency, rate,
                    unit, item_price, sales_amount, discount_items_rate, discount_items_number,
                    taxable_fees, differ_value, items_discount, net_total, total_amount);

                let taxType = null;
                let subtype = null;
                let tax_rate = null;
                let row_total_tax = null;
                $('.tax-items').not('.d-none').each(function (index, element) {
                    taxType = $(element).find('.tax-type').val();
                    subtype = $(element).find('.subtype').val();
                    tax_rate = $(element).find('.tax_rate').val();
                    row_total_tax = $(element).find('.row_total_tax').val();

                    let tax = new Tax();
                    tax.setTax(taxType, subtype, tax_rate, row_total_tax);
                    items[numberOfAddedItems].tax_items[index] = tax;

                    let temp_Tax = new Temp_Tax();
                    temp_Tax.setTemp_Tax(purchaseOrderTaxIds[index], row_total_tax);
                    items[numberOfAddedItems].temp_tax_items.push(temp_Tax);

                    // items[numberOfAddedItems].temp_tax_items[index] = temp_Tax;
                });

                $('.tax-items').not('.d-none').remove();
                let markup = `<tr><th> ${numberOfAddedItems + 1}</th>
                    <td> ${items[numberOfAddedItems].product_code}</td>
                    <td>${items[numberOfAddedItems].product_name}</td>
                    <td class='new-added-quantity'>${items[numberOfAddedItems].quantity}</td>
                    <td>${items[numberOfAddedItems].unit}</td>
                    <td class='new-added-itemPrice'>${parseFloat(items[numberOfAddedItems].item_price).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${parseFloat(items[numberOfAddedItems].sales_amount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${items[numberOfAddedItems].discount_items_rate}</td>
                    <td>${parseFloat(items[numberOfAddedItems].discount_items_number).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${parseFloat(items[numberOfAddedItems].taxable_fees || 0).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${parseFloat(items[numberOfAddedItems].differ_value || 0).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td> <td>${parseFloat(items[numberOfAddedItems].items_discount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${parseFloat(items[numberOfAddedItems].net_total).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>${parseFloat(items[numberOfAddedItems].total_amount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>
                        <button type="button" class="btn btn-danger tableItemsBtn deleteItem" data-item-id="${numberOfAddedItems}"><i class="fa fa-trash-alt"></i></button>
                        <button type="button" data-toggle="modal" data-target="#addline" class="btn btn-warning tableItemsBtn editItem" data-item-id="${numberOfAddedItems}"><i class="fa fa-edit"></i></button>
                    </td></tr>`;

                $(".tableForItems tbody").append(markup);

            } else { // Edit item
                items[itemeditIndex].setItem(items[itemeditIndex].item_id, product_code, product_name, description, quantity, currency, rate,
                    unit, item_price, sales_amount, discount_items_rate, discount_items_number,
                    taxable_fees, differ_value, items_discount, net_total, total_amount);

                let taxType = null;
                let subtype = null;
                let tax_rate = null;
                let row_total_tax = null;

                $('.tax-items').not('.d-none').each(function (index, element) {
                    taxType = $(element).find('.tax-type').val();
                    subtype = $(element).find('.subtype').val();
                    tax_rate = $(element).find('.tax_rate').val();
                    row_total_tax = $(element).find('.row_total_tax').val();

                    items[itemeditIndex].tax_items[index].setTax(taxType, subtype, tax_rate, row_total_tax);
                    items[itemeditIndex].temp_tax_items[index].setTemp_Tax(items[itemeditIndex].temp_tax_items[index].purchase_order_tax_id, row_total_tax);
                });

                $('.tax-items').not('.d-none').remove();

                // Edit row in table view item
                let markup = `<th> ${itemeditIndex + 1}</th>
                <td> ${items[itemeditIndex].product_code}</td>
                <td>${items[itemeditIndex].product_name}</td>`;
                if ($(".tableForItems tbody tr:nth-child(" + (itemeditIndex + 1) + ")").find('.new-added-quantity'))
                    markup += `<td class='new-added-quantity'>${items[itemeditIndex].quantity}</td>`;
                else
                    markup += `<td>${items[itemeditIndex].quantity}</td>`;

                markup += `<td>${items[itemeditIndex].unit}</td>`;

                if ($(".tableForItems tbody tr:nth-child(" + (itemeditIndex + 1) + ")").find('.new-added-itemPrice'))
                    markup += `<td class='new-added-itemPrice'>${parseFloat(items[itemeditIndex].item_price).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>`;
                else
                    markup += `<td>${parseFloat(items[itemeditIndex].item_price).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>`;

                markup += `<td>${parseFloat(items[itemeditIndex].sales_amount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${items[itemeditIndex].discount_items_rate}</td>
                <td>${parseFloat(items[itemeditIndex].discount_items_number).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${parseFloat(items[itemeditIndex].taxable_fees || 0).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${parseFloat(items[itemeditIndex].differ_value || 0).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${parseFloat(items[itemeditIndex].items_discount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${parseFloat(items[itemeditIndex].net_total).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${parseFloat(items[itemeditIndex].total_amount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td>
                        <button type="button" class="btn btn-danger tableItemsBtn deleteItem" data-item-id="${itemeditIndex}"><i class="fa fa-trash"></i></button>
                        <button type="button" data-toggle="modal" data-target="#addline" class="btn btn-warning tableItemsBtn editItem" data-item-id="${itemeditIndex}"><i class="fa fa-edit"></i></button>
                    </td>`;
                $(".tableForItems tbody tr:nth-child(" + (itemeditIndex + 1) + ")").html(markup);
            }

            $('#addline').modal('hide');

            $('#addline').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });
        }
        $('.tax-type').find('option').show();

        $('#invoice-discount').trigger('change');
    });

    // Delete item button
    $('.tableForItems tbody').on('click', '.tableItemsBtn.deleteItem', function () {
        let itemIndex = $(this).data('itemId');
        items.splice(itemIndex, 1);
        $(this).parents('tr').remove();
        $('#invoice-discount').trigger('change');
        if (items.length == 0)
            $('#items').val('');
        else
            $('#items').val(items.length);
        $('#items').trigger('keyup');


        resetItemCounter();
    });

    //  Edit item button
    $('.tableForItems tbody').on("click", '.tableItemsBtn.editItem', function (e) {
        // Reset modal inputs
        $('#quantity').attr('readonly', true);
        $('#description').attr('readonly', true);
        $('.rate').attr('readonly', true);
        $('.item_price').attr('readonly', true);

        // Reset modal old error
        $('.quantity').removeClass('error');
        $('#quantity-error').hide();

        $('.item_price').removeClass('error');
        $('#item_price-error').hide();

        addNewItemInEditMode = false;
        const modelTitle = $('#exampleModalLongTitle'); // Get modal title
        modelTitle.text(modelTitle.data('edit-item')); // Change modal title to be (Edit new item) as in edit mode
        modelTitle.data('add-status', '0'); // // Change modal data-add-status to be (0) as in edit mode

        // $('.validate').addClass('d-none');

        $('#addline').data('checkData', 'data');
        checkDataInModal = $('#addline').data('checkData');
        itemeditIndex = $(this).data('itemId');

        $('#addline').data('item-id', itemeditIndex);

        $('#product_code').val(items[itemeditIndex].product_code);
        $('#product_name').val(items[itemeditIndex].product_name);
        $('#description').val(items[itemeditIndex].description);
        $('.quantity').val(items[itemeditIndex].quantity);
        $('#item_unit').val(items[itemeditIndex].unit);
        $('.rate').val(items[itemeditIndex].rate).attr('readonly', false);
        $('.currenty input').first().val(items[itemeditIndex].currency);
        $('.current-currency-text').text($('.currency').val());
        $('.item_price ').val(items[itemeditIndex].item_price);
        $('.sales_amount').val(items[itemeditIndex].sales_amount);
        $('.discount_items_rate').val(items[itemeditIndex].discount_items_rate);
        $('.discount_items_number').val(items[itemeditIndex].discount_items_number);
        $('.taxable_fees').val(items[itemeditIndex].taxable_fees);
        $('.differ_value').val(items[itemeditIndex].differ_value);
        $('.itemsDiscount').val(items[itemeditIndex].items_discount);
        $('.net_total').val(items[itemeditIndex].net_total);
        $('.total_amount').val(items[itemeditIndex].total_amount);

        $('#description').attr('readonly', false);

        // Check if currency is EGP disable rate and set it to 1
        var currencyType = $('.currenty input').first().val();
        if (currencyType == "EGP") {
            $('.rate').attr('readonly', true)
        } else {
            $('.rate').attr('readonly', false)
        }

        // Check if purchase order is budget or quantity
        if (purchaseOrderType == 'budget') {
            $('.item_price').attr('readonly', false);
            $('.item_price').val(items[itemeditIndex].item_price);
            $('#quantity').attr('readonly', true);
            $('#quantity').val(1);

        } else {
            $('.item_price').attr('readonly', true);
            $('#quantity').attr('readonly', false);
            $('#quantity').val(items[itemeditIndex].quantity);
            $('#item_price').val(items[itemeditIndex].item_price);
        }


        let taxItemsCount = items[itemeditIndex].tax_items.length;
        let taxTable = '';
        let tempTaxRow = '';
        let newTaxRow = '';
        let dataSelected = 0;
        let typeSelected = [];

        $('.tax-items').not('.d-none').remove();
        for (var i = 0; i < taxItemsCount; i++) {
            taxTable = $('.tax-items-table');
            tempTaxRow = taxTable.find('div.tax-items').first();
            newTaxRow = tempTaxRow.clone(true);
            newTaxRow.removeClass('d-none');
            taxTable.append(newTaxRow);
            newTaxRow.find('input[type=text]').val('');
            newTaxRow.find('input[type=number]').val('');

            newTaxRow.find('.tax-type option[value=' + items[itemeditIndex].tax_items[i].tax_type + ']').attr('selected', 'selected');

            typeSelected.push(newTaxRow.find('.tax-type').find("option[value='" + items[itemeditIndex].tax_items[i].tax_type + "']").val());


            if (![3, 6].includes(items[itemeditIndex].tax_items[i].tax_type))
                newTaxRow.find('.tax_rate').val(items[itemeditIndex].tax_items[i].tax_rate);

            newTaxRow.find('.row_total_tax').val(items[itemeditIndex].temp_tax_items[i].amount_tax);

            // here function to change sub type
            dataSelected = newTaxRow.find('.tax-type').val();
            appendSubType(dataSelected, (newTaxRow.find('.subtype')), newTaxRow.find('.typeName'));

            newTaxRow.find('.subtype option[value="' + items[itemeditIndex].tax_items[i].subtype + '"]').attr('selected', 'selected');
            if (dataSelected == 3 || dataSelected == 6) {
                newTaxRow.find('.tax_rate').prop('readonly', true);
                newTaxRow.find('.tax_rate').val('');
            }
        }

        $('#invoice-discount').trigger('change');

        // Change selected item
        $(`#item_select option`).attr('selected', false);
        $(`#item_select option[value=${items[itemeditIndex].item_id}]`).attr('selected', true);
        if ($('#item_select').hasClass('select2-hidden-accessible')) {
            $('#item_select').select2('destroy');
            $('#item_select').prop('disabled', true);
        } else {
            $('#item_select').prop('disabled', true);
        }
        if (editDocumentMode) { // As in edit we don't select purchase order to get its items
            const selectPurchaseOrder = $("#select_purchase_order");
            if ((selectPurchaseOrder.find("option").length == 1))
                selectPurchaseOrder.trigger('change');
        }
    });

    let availableQuantitiesOverflowErrorMessage = '';

    $('.rate').on('keyup', function () {
        $('.quantity').trigger('change');
    });

    $('.quantity').on('keyup change', function () { // items quantity
        let addedItemQuantities = 0,
            addedNewItemsQuantityInEditMode = 0,
            availableQuantities = 0;

        const targetErrorTag = $('#validate-quantity-overflow'),
            that = $(this);

        let value = that.val().trim();

        if (value) {
            if (!(value.match(/^-?\d*\.\d{0,5}$/) || value.match(/^\d+$/))) { // if not integer or decimal with more than 5 digits after decimal point (.)
                value = roundNumberToNDigitsAfterDecimalPoint(value);
                that.val(value);
            }
        }

        for (let index = 0; index < items.length; index++) { // sum of all quantites saved items in items array in frontend
            if (items[index].item_id == $('#item_select').val())
                addedItemQuantities += fixedTo20(items[index].quantity);
        }

        // Sum of all added items quantity
        if (editDocumentMode) {
            $('.new-added-quantity').each(function (index, element) {
                if ($('#product_code').val() == $(element).prev().prev().text().trim())
                    addedNewItemsQuantityInEditMode += fixedTo20($(element).text());
            });
        }

        if (editDocumentMode)
            availableQuantities = fixedTo20(fixedTo20(POQuantities) - fixedTo20(documentItemQuantities + addedNewItemsQuantityInEditMode)); // get available quantity -> subtract all used quantites from purchase order quantity + old used in item quantity in another documents
        else
            availableQuantities = fixedTo20(fixedTo20(POQuantities) - fixedTo20(documentItemQuantities + addedItemQuantities)); // get available quantity -> subtract all used quantites from purchase order quantity + old used in item quantity in another documents

        // check if in edit subtract active item quantity
        const modelTitle = $('#exampleModalLongTitle');
        modelTitle.data('add-status');
        if (modelTitle.data('add-status') == '0') {
            availableQuantities += fixedTo20(items[itemeditIndex].quantity);
        }

        if(!editDocumentMode)
            documentType = $('select[name="type"]').val();
        
        if (documentType == 'I' || documentType == 'D') { // Invoice or Debit
            if (availableQuantitiesOverflowErrorMessage == '') {
                availableQuantitiesOverflowErrorMessage = targetErrorTag.text().trim().split('()');
            }

            if (value > availableQuantities && !that.attr('readonly')) {
                let errorMessage = [...availableQuantitiesOverflowErrorMessage];
                errorMessage.splice(1, 0, ` (${value}) `); // add item used quantity in documents
                errorMessage = errorMessage.join(' ');
                targetErrorTag.removeClass('d-none').text(`${errorMessage} (${availableQuantities})`);
                that.val('');
            }
            else {
                targetErrorTag.addClass('d-none');
                calcItemsTotalSales(that);
                netTotalByQuantity();
            }
        } else if (documentType == 'C') { // Credit
            if (availableQuantitiesOverflowErrorMessage == '') {
                availableQuantitiesOverflowErrorMessage = targetErrorTag.next().text().trim().split('()');
            }

            if (value > documentItemQuantities && !that.attr('readonly')) {
                let errorMessage = [...availableQuantitiesOverflowErrorMessage];
                errorMessage.splice(1, 0, ` (${value}) `); // add item used quantity in documents
                errorMessage = errorMessage.join(' ');
                targetErrorTag.removeClass('d-none').text(`${errorMessage} (${documentItemQuantities})`);
                that.val('');
            }
            else {
                targetErrorTag.addClass('d-none');
                calcItemsTotalSales(that);
                netTotalByQuantity();
            }
        }

    });

    let availablePricesOverflowErrorMessage = '';

    $('.item_price').on('keyup change', function () { // price of item
        let addedItemPrices = 0,
            addedNewItemsPriceInEditMode = 0,
            availablePrices = 0

        const targetErrorTag = $('#validate-price-overflow'),
            that = $(this),
            value = that.val().trim();

        if (value <= 0) {
            that.val('');
            return;
        }

        for (let index = 0; index < items.length; index++) {
            if (items[index].item_id == $('#item_select').val())
                addedItemPrices += fixedTo20(items[index].item_price);
        }

        // Sum of all added items price
        if (editDocumentMode) {
            $('.new-added-itemPrice').each(function (index, element) {
                if ($('#product_code').val() == $(element).prev().prev().prev().prev().text().trim())
                    addedNewItemsPriceInEditMode += fixedTo20($(element).text().replace(/,/g, ''));
                // replace(/,/g, '') To parse a string with a comma thousand separator to a number
            });
        }

        if (editDocumentMode)
            availablePrices = fixedTo20(fixedTo20(POPrices) - fixedTo20(documentItemPrices + addedNewItemsPriceInEditMode)); // get available item_price -> subtract all used item_prices from purchase order budget + old used in item budget in another documents
        else
            availablePrices = fixedTo20(fixedTo20(POPrices) - fixedTo20(documentItemPrices - addedItemPrices)); // get available item_price -> subtract all used item_prices from purchase order budget + old used in item budget in another documents

        // check if in edit
        const modelTitle = $('#exampleModalLongTitle');
        modelTitle.data('add-status');
        if (modelTitle.data('add-status') == '0') {
            availablePrices += fixedTo20(items[itemeditIndex].item_price);
        }

        const documentType = $('select[name="type"]').val();

        if (documentType == 'I' || documentType == 'D') { // Invoice or Debit
            if (availablePricesOverflowErrorMessage == '') {
                availablePricesOverflowErrorMessage = targetErrorTag.text().trim().split('()');
            }

            if (value > availablePrices && !that.attr('readonly')) {
                let errorMessage = [...availablePricesOverflowErrorMessage];
                errorMessage.splice(1, 0, ` (${value}) `); // add item used price in documents
                errorMessage = errorMessage.join(' ');
                targetErrorTag.removeClass('d-none').text(`${errorMessage} (${availablePrices})`);
                that.val('');
            }
            else {
                targetErrorTag.addClass('d-none');
                calcItemsTotalSales(that);
                netTotalByQuantity();
            }
        } else if (documentType == 'C') { // Credit
            if (availablePricesOverflowErrorMessage == '') {
                availablePricesOverflowErrorMessage = targetErrorTag.next().text().trim().split('()');
            }

            if (value > documentItemPrices && !that.attr('readonly')) {
                let errorMessage = [...availableQuantitiesOverflowErrorMessage];
                errorMessage.splice(1, 0, ` (${value}) `); // add item used quantity in documents
                errorMessage = errorMessage.join(' ');
                targetErrorTag.removeClass('d-none').text(`${errorMessage} (${documentItemPrices})`);
                that.val('');
            }
            else {
                targetErrorTag.addClass('d-none');
                calcItemsTotalSales(that);
                netTotalByQuantity();
            }
        }


    });

    function calcItemsTotalSales(row) {  // calc total salse of items => quantity * item price * currency Rate
        let quantity = row.parents().find('.quantity').val();
        let item_price = row.parents().find('.item_price').val();
        let currentyRate = $('.currenty input:not(:first)').val()
        if ((item_price) != '' && (quantity) != '' && (currentyRate) != '') {
            $('.price').parents().find('.sales_amount').val((parseFloat(quantity) * parseFloat(item_price) * parseFloat(currentyRate)).toFixed(5));
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

                // $(this).find('.row_total_tax').prop('readonly', true);
                // $(this).find('.tax_rate').prop('readonly', false);

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
        // row.find('.tax_rate').prop('readonly', true);
        // row.find('.row_total_tax').prop('readonly', false);
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

        rowSubType.html('');
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

    let discountInvoiceOverflowErrorMessage = '';

    function sumOfTotalItemAmount() {
        let total = 0;
        items.forEach(item => {
            total += parseFloat(item.total_amount);
        });
        return total;
    }


    $('#invoice-discount').on('change', function () {
        const that = $(this),
            discount = (parseFloat($(this).val()) || 0),
            total = sumOfTotalItemAmount() || 0,
            totalAfterDiscount = total - discount;

        $(this).val(discount);

        if (discountInvoiceOverflowErrorMessage == '') {
            discountInvoiceOverflowErrorMessage = that.next().text().trim().split('()');
        }

        if (discount < total && total > 0) {
            that.next().addClass('d-none');
            $('#invoice-total').val(totalAfterDiscount.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        }
        else {
            let errorMessage = [...discountInvoiceOverflowErrorMessage];
            errorMessage.splice(1, 0, ` (${discount}) `); // add item used quantity in documents
            errorMessage = errorMessage.join(' ');
            that.val('0');
            $('#invoice-total').val(total.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            that.next().removeClass('d-none').text(`${errorMessage} (${total})`);
        }


    })
});

function submitInvoice() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    for (let index = 0; index < items.length; index++) {
        delete items[index].tax_items;
    }

    var invoice = {
        basicData: form.serializeArray(),
        items: items,
    }

    let ajaxURL = documentId ? `${subFolderURL}/${urlLang}/documents/${documentId}` : `${subFolderURL}/${urlLang}/documents`;
    let ajaxMethod = documentId ? 'put' : 'post';

    $(".actions a[href$='#finish']").css("pointer-events", "none");
    $(".actions a[href$='#finish']").text(language['send_data']);
    $.ajax({
        url: ajaxURL,
        type: ajaxMethod,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify(invoice),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (res) {
            if (res.status == 'success1') {
                
                $(".actions a[href$='#finish']").text(language['data_sent']);
                window.location.href = `${subFolderURL}/${urlLang}/documents/index_document_request`;
            }
            if (res.status == 'success') {
                $(".actions a[href$='#finish']").text(language['data_sent']);
                window.location.href = `${subFolderURL}/${urlLang}/documents`;
            } 
            if (res.status != 'success' && res.status != 'success1') {
                $(".actions a[href$='#finish']").text(language['send_data_error']);
            }
        },
        error: function (request, status, error) {
            $(".actions a[href$='#finish']").text(language['send_data_error']);
        }
    });
}

// get collection of item and add these item in items array
function setItemForEdit(_items) {
    numberOfAddedItems = Object.keys(_items).length; // Set number of added items
    for (let itemIndex = 0; itemIndex < _items.length; itemIndex++) {
        let item = new Item();
        // Assign item values
        for (const key in _items[itemIndex]) {
            if (_items[itemIndex].hasOwnProperty.call(_items[itemIndex], key) && !['tax_items', 'temp_tax_items'].includes(key)) {
                item[key] = _items[itemIndex][key];
            }
        }


        // Add taxes
        for (let taxIndex = 0; taxIndex < _items[itemIndex]['tax_items'].length; taxIndex++) {
            let tax = new Tax();

            // Assign tax values
            for (const key in _items[itemIndex]['tax_items'][taxIndex]) {
                if (_items[itemIndex]['tax_items'][taxIndex].hasOwnProperty.call(_items[itemIndex]['tax_items'][taxIndex], key)) {
                    tax[key] = _items[itemIndex]['tax_items'][taxIndex][key];
                }
            }
            item.tax_items.push(tax);

            let tempTax = new Temp_Tax();

            // Assign document taxes data (temp_tax_items) values
            for (const key in _items[itemIndex]['temp_tax_items'][taxIndex]) {
                if (_items[itemIndex]['temp_tax_items'][taxIndex].hasOwnProperty.call(_items[itemIndex]['temp_tax_items'][taxIndex], key)) {
                    tempTax[key] = _items[itemIndex]['temp_tax_items'][taxIndex][key];
                }
            }
            item.temp_tax_items.push(tempTax);
        }
        items.push(item)
    }

    const data = {
        'purchase_order_reference': $('#select_purchase_order').val(),
    }


    $.ajax({
        type: 'post',
        url: `${subFolderURL}/${urlLang}/getProductFromPurchaseOrder`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        data: JSON.stringify(data),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',

        success: function (response) {
            const data = (response);

            itemInPurchaseOrder = data.items;
            purchaseOrderType = data.type;

            for (let i = 0; i < itemInPurchaseOrder.length; i++) {
                if (itemInPurchaseOrder[i].description)
                    $('#item_select').append(`<option value="${itemInPurchaseOrder[i].id}">${itemInPurchaseOrder[i].internal_code} - ${itemInPurchaseOrder[i].description}</option>`);
                else
                    $('#item_select').append(`<option value="${itemInPurchaseOrder[i].id}">${itemInPurchaseOrder[i].internal_code}</option>`);
            }
        },
        error: function () {
            $('.vaild-company-register-tax').text("{{ trans('site.error') }}");
        },
        complete: function () {
            $('.search-company.spinner-border').hide();
        }
    });
}

// Get document price and quantity
function getDocumentPriceAndQuantity() {
    const item_id = $('#item_select').val();
    $.ajax({
        type: 'GET',
        url: `${subFolderURL}/${urlLang}/getSelectedItemFromPurchaseOrder/` + item_id,
        success: function (response) {
            var responses = JSON.parse(response);
            documentItemQuantities = responses.documentItemQuantities;
            documentItemPrices = responses.documentItemPrices;

            POQuantities = responses.item.quantity;
            POPrices = responses.item.item_price;
        }
    });
}

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

let documentNumberErrorMessage = '';
$('#document_number').on('focusout', function () {
    const that = $(this);
    that.val(that.val().trim());

    const data = {
        'document_number': $(this).val(),
        'id' : documentId,
    }

    if (that.val().trim() == '')
        return;

    if (documentNumberErrorMessage == '') {
        documentNumberErrorMessage = that.parent().find('.document_number_used_before_error').text().trim().split(' ');
    }
    $.ajax({
        type: 'post',
        url: `${subFolderURL}/${urlLang}/documents/check_document_number`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        data: JSON.stringify(data),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (documentNumberCounter) {
            if (documentNumberCounter == 0) {
                that.parent().find('.document_number_used_before_error').addClass('d-none');
            }
            else {
                let errorMessage = [...documentNumberErrorMessage];
                errorMessage.splice(2, 0, ` (${that.val()}) `); // add entered purchase order reference
                errorMessage = errorMessage.join(' ');
                that.val('');
                that.parent().find('.document_number_used_before_error').text(errorMessage).removeClass('d-none');
            }
        }
    });

})


function roundNumberToNDigitsAfterDecimalPoint(number) {
    var numberWith5Decimals = number.toString().match(/^-?\d*(?:\.\d{0,5})?/)[0]
    return numberWith5Decimals;
}


function fixedTo20($number) {
    return +(Number($number).toFixed(20));
}