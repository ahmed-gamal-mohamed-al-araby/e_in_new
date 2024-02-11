var startTime, endTime;

// Handling to read excel file

$('#purchase-order-type').on('change', function () {
    if ($('#purchase-order-type').val() == 'budget')
        $('#_items_excel_button_saveBtn').addClass('d-none');
});

const currencies = ['EGP', 'USD', 'Euro', 'JPY', 'GBP', 'CHF', 'CAD', 'AUD/NZD', 'ZAR'],
    availableTaxTypes = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12', 'T13', 'T14', 'T15', 'T16', 'T17', 'T18', 'T19', 'T20'],
    availableTaxSubTypes = ['V001', 'V002', 'V003', 'V004', 'V005', 'V006', 'V007', 'V008', 'V009', 'V010', 'Tbl01', 'Tbl02', 'W001', 'W002', 'W003', 'W004', 'W005', 'W006', 'W007', 'W008', 'W009', 'W010', 'W011', 'W012', 'W013', 'W014', 'W015', 'W016', 'ST01', 'ST02', 'Ent01', 'Ent02', 'RD01', 'RD02', 'SC01', 'SC02', 'Mn01', 'Mn02', 'MI01', 'MI02', 'OF01', 'OF02', 'ST03', 'ST04', 'Ent03', 'Ent04', 'RD03', 'RD04', 'SC03', 'SC04', 'Mn03', 'Mn04', 'MI03', 'MI04', 'OF03', 'OF04'];

// change label for input file
$('input[type=file]').on('change', function () {
    $(this).next().text($(this).val());
})

$('#items_excel_id').on('change', function () {
    const current = $(this);
    if (current.val() != '') {
        current.removeClass('is-invalid').addClass('is-valid');
    } else {
        current.addClass('is-invalid');
    }
})

let selectedFile;
document.getElementById('items_excel_id').addEventListener("change", (event) => {
    selectedFile = event.target.files[0];
})

let data = [{
    "name": "jayanth",
    "data": "scd",
    "abc": "sdef"
}]

let excelSheetItems = [];
let excelSheetItemsIndex = 0;

$('#items_excel_button_save').on("click", () => {
    const excelFile = $('#items_excel_id')

    // If file is set
    if (excelFile.val() != '') {
        excelFile.removeClass('is-invalid').addClass('is-valid');
        $('.items-from-excel-sheet-loader').fadeIn();
        XLSX.utils.json_to_sheet(data, 'out.xlsx');
        if (selectedFile) {
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(selectedFile);
            fileReader.onload = (event) => {
                let data = event.target.result;
                let workbook = XLSX.read(data, { type: "binary" });
                workbook.SheetNames.forEach(sheet => {
                    excelSheetItems = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
                });
                startTime = new Date();
                setItemViaExcel(excelSheetItems[excelSheetItemsIndex]); // Set first item
            }
        }

    } else {
        excelFile.addClass('is-invalid');
    }
});

function setItemViaExcel(item) {

    // -------------------------------------------- Validate fields are set -------------------------------------------- //

    // internal_code
    if (!item.hasOwnProperty('internal_code')) {
        resetItem(excelSheetItemsIndex + 1, 'internal_code not founded');
        return 0;
    }

    // description
    if (!item.hasOwnProperty('description')) {
        resetItem(excelSheetItemsIndex + 1, 'description not founded');
        return 0;
    }

    // currency
    if (!item.hasOwnProperty('currency')) {
        resetItem(excelSheetItemsIndex + 1, 'currency not founded');
        return 0;
    }

    // unit
    if (!item.hasOwnProperty('unit')) {
        resetItem(excelSheetItemsIndex + 1, 'unit not founded');
        return 0;
    }

    // item_price
    if (!item.hasOwnProperty('item_price')) {
        resetItem(excelSheetItemsIndex + 1, 'item_price not founded');
        return 0;
    }

    // quantity
    if (!item.hasOwnProperty('quantity')) {
        resetItem(excelSheetItemsIndex + 1, 'quantity not founded');
        return 0;
    }

    // discount_item_rate
    if (!item.hasOwnProperty('discount_item_rate')) {
        resetItem(excelSheetItemsIndex + 1, 'discount_item_rate not founded');
        return 0;
    }

    // discount_item_amount
    if (!item.hasOwnProperty('discount_item_amount')) {
        resetItem(excelSheetItemsIndex + 1, 'discount_item_amount not founded');
        return 0;
    }

    // value_difference
    if (!item.hasOwnProperty('value_difference')) {
        resetItem(excelSheetItemsIndex + 1, 'value_difference not founded');
        return 0;
    }

    // items_discount
    if (!item.hasOwnProperty('items_discount')) {
        resetItem(excelSheetItemsIndex + 1, 'items_discount not founded');
        return 0;
    }

    // tax_type
    if (!item.hasOwnProperty('tax_type')) {
        resetItem(excelSheetItemsIndex + 1, 'tax_type not founded');
        return 0;
    }

    // subType
    if (!item.hasOwnProperty('subType')) {
        resetItem(excelSheetItemsIndex + 1, 'subType not founded');
        return 0;
    }

    // tax_rate
    if (!item.hasOwnProperty('tax_rate')) {
        resetItem(excelSheetItemsIndex + 1, 'tax_rate not founded');
        return 0;
    }

    // tax_amount
    if (!item.hasOwnProperty('tax_amount')) {
        resetItem(excelSheetItemsIndex + 1, 'tax_amount not founded');
        return 0;
    }

    // --------------------------------------------End of Validate fields are set -------------------------------------------- //

    // After all data is valid add new empty item
    addNewEmptyItem();

    // internal_code
    if (item.internal_code && item.internal_code != '_' && !isNaN(item.internal_code)) {
        $('#internal_code').val(item.internal_code != '_' ? item.internal_code : '');

    } else {
        resetItem(excelSheetItemsIndex + 1, `internal_code, value= ${item.internal_code}`);
        return 0;
    }

    // description
    if (item.description && item.description != '_') {
        $('#description').val(item.description);
    } else {
        resetItem(excelSheetItemsIndex + 1, `description, value= ${item.description}`);
        return 0;
    }

    // currency
    if (item.currency && item.currency != '_' && currencies.indexOf(item.currency) != -1) {
        $(`#currency option[value="${item.currency}"]`).prop('selected', true);
    } else {
        resetItem(excelSheetItemsIndex + 1, `currency, value= ${item.currency} is not in available currencies`);
        return 0;
    }


    // unit
    if (item.unit && item.unit != '_' && productUnits.indexOf(item.unit) != -1) {
        $(`#unit option[value="${item.unit}"]`).prop('selected', true);
    } else {
        resetItem(excelSheetItemsIndex + 1, 'unit, value= ${item.unit} is not in available units');
        return 0;
    }

    // item_price
    if (item.item_price && item.item_price != '_' && !isNaN(item.item_price)) {
        $('#item-price').val(item.item_price);
    } else {
        resetItem(excelSheetItemsIndex + 1, `item_price, value= ${item.item_price}`);
        return 0;
    }

    // quantity
    if (item.quantity && item.quantity != '_' && !isNaN(item.quantity)) {
        $('#item-quantity').val(item.quantity);
    } else {
        resetItem(excelSheetItemsIndex + 1, `quantity, value= ${item.quantity}`);
        return 0;
    }

    // discount_item_amount
    if (!item.discount_item_amount) {
        resetItem(excelSheetItemsIndex + 1, `discount_item_amount, value= ${item.discount_item_amount}`);
        return 0;
    }

    // discount_item_rate
    if (!item.discount_item_rate) {
        resetItem(excelSheetItemsIndex + 1, `discount_item_rate, value= ${item.discount_item_rate}`);
        return 0;
    }

    // tax_rate || tax_amount
    if (item.discount_item_amount && item.discount_item_amount != '_' && !isNaN(item.discount_item_amount)) {
        $('.discount_items_number').val(item.discount_item_amount);
    } else if (item.discount_item_rate && item.discount_item_rate != '_' && !isNaN(item.discount_item_rate)) {
        $('.discount_items_rate').val(item.discount_item_rate)
    }

    // value_difference
    if (item.value_difference != '_' && !isNaN(item.value_difference)) {
        $('.differ_value').val(item.value_difference);
    } else if (item.value_difference == '_') {
        $('.differ_value').val('');
    } else {
        resetItem(excelSheetItemsIndex + 1, `value_difference, value= ${item.value_difference}`);
        return 0;
    }

    // items_discount
    if (item.items_discount != '_' && !isNaN(item.items_discount)) {
        $('.itemsDiscount').val(item.items_discount);
    } else if (item.items_discount == '_') {
        $('.itemsDiscount').val('');
    } else {
        resetItem(excelSheetItemsIndex + 1, `items_discount, value= ${item.items_discount}`);
        return 0;
    }

    $('.quantity').trigger('change');

    // Get data from internal code
    getDataOfProductFromInternalCode(item.internal_code);
}



function getDataOfProductFromInternalCode(searchContent) {
    try {
        searchContent = searchContent.toString();
    } catch (error) {
        resetItem(excelSheetItemsIndex + 1, `internal_code, value= ${searchContent}`);
        return 0;
    }

    if (searchContent.replace(/^\s+|\s+$/g, "").length != 0) {
        $('.search-product.spinner-border').show();
        $('#product_name').val('');
        $('#product_code').val('');
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
                        $('.search-product.spinner-border').hide();
                        if ($('#product_code').val().length != 0) {
                            $('#product_code').valid();
                        }
                        if ($('#product_name').val().length != 0) {
                            $('#product_name').valid();
                        }
                    });
                    let taxTypes = [], subTypes = [], taxRates = [], taxAmounts = [];
                    // Taxes
                    if (excelSheetItems[excelSheetItemsIndex].tax_type.indexOf("[") != -1) { // Is Array

                        // tax_type
                        try {
                            taxTypes = JSON.parse(excelSheetItems[excelSheetItemsIndex].tax_type);
                        } catch (error) {
                            resetItem(excelSheetItemsIndex + 1, 'tax_type');
                            return 0;
                        }

                        // subType
                        try {
                            subTypes = JSON.parse(excelSheetItems[excelSheetItemsIndex].subType);
                        } catch (error) {
                            resetItem(excelSheetItemsIndex + 1, 'subType');
                            return 0;
                        }

                        // tax_rate || tax_amount
                        try {
                            taxRates = JSON.parse(excelSheetItems[excelSheetItemsIndex].tax_rate);
                        } catch (error) {
                            resetItem(excelSheetItemsIndex + 1, 'tax_rate');
                            return 0;
                        }

                        // tax_amount
                        try {
                            taxAmounts = JSON.parse(excelSheetItems[excelSheetItemsIndex].tax_amount);
                        } catch (error) {
                            resetItem(excelSheetItemsIndex + 1, 'tax_amount');
                            return 0;
                        }

                        const lengthAverage = (taxTypes.length + subTypes.length + taxRates.length) / 3;

                        if (numOfTaxes == -1) {
                            numOfTaxes = lengthAverage;
                        }

                        // Check if number of added taxes is not matched
                        if (lengthAverage != numOfTaxes) {
                            resetItem(excelSheetItemsIndex + 1, '', true, 'Taxes length not matched');
                            return 0;
                        }

                        // check if taxes arrays length not matched 
                        if (taxTypes.length != lengthAverage || subTypes.length != lengthAverage || taxRates.length != lengthAverage) {
                            resetItem(excelSheetItemsIndex + 1, '', true, 'Taxes columns [tax_type, subType, tax_rate] length not matched');
                            return 0;
                        }

                        // Set taxes
                        for (let index = 0; index < lengthAverage; index++) {
                            $("#add_tax_row").click();

                            // tax_type
                            if (availableTaxTypes.indexOf(taxTypes[index]) != -1)
                                currentTaxRow.find(`.tax-type option[value=${taxTypes[index].substring(1)}]`).prop('selected', true);
                            else {
                                resetItem(excelSheetItemsIndex + 1, `tax_type, value= ${taxTypes[index]} is not in available Tax types`);
                                return 0;
                            }

                            currentTaxRow.find(`.tax-type`).trigger('change');

                            // subType
                            if (availableTaxSubTypes.indexOf(subTypes[index]) != -1)
                                currentTaxRow.find('.tax-type').parents('.tax-items').find(`.subtype option[value=${subTypes[index]}]`).prop('selected', true);
                            else {
                                resetItem(excelSheetItemsIndex + 1, `subType, value= ${subTypes[index]} is not in available Tax Subtypes`);
                                return 0;
                            }

                            // tax_rate || tax_amount
                            if (taxTypes[index] == 'T3' || taxTypes[index] == 'T6')
                                currentTaxRow.find('.tax-type').parents('.tax-items').find('.row_total_tax').val(taxAmounts[index]);
                            else {
                                currentTaxRow.find('.tax-type').parents('.tax-items').find('.tax_rate').val(taxRates[index]);
                            }
                        }

                    } else {
                        if (numOfTaxes == -1) {
                            numOfTaxes = 1;
                        }

                        // Check if number of added taxes is not matched
                        if (numOfTaxes != 1) {
                            resetItem(excelSheetItemsIndex + 1, '', true, 'Taxes length not matched');
                            return 0;
                        }

                        // Set tax
                        $("#add_tax_row").click();
                        currentTaxRow.find(`.tax-type option[value=${excelSheetItems[excelSheetItemsIndex].tax_type.substring(1)}]`).prop('selected', true)
                        currentTaxRow.find(`.tax-type`).trigger('change');
                        currentTaxRow.find('.tax-type').parents('.tax-items').find(`.subtype option[value=${excelSheetItems[excelSheetItemsIndex].subType}]`).prop('selected', true);

                        if (excelSheetItems[excelSheetItemsIndex].tax_type == 'T3' || excelSheetItems[excelSheetItemsIndex].tax_type == 'T6')
                            currentTaxRow.find('.tax-type').parents('.tax-items').find('.row_total_tax').val(excelSheetItems[excelSheetItemsIndex].tax_amount);
                        else {
                            currentTaxRow.find('.tax-type').parents('.tax-items').find('.tax_rate').val(excelSheetItems[excelSheetItemsIndex].tax_rate);
                        }
                    }

                    submitDataToNewItem();

                    if (excelSheetItemsIndex < excelSheetItems.length - 1) { // Not reach to end of items
                        $('#addItemsViaExcel').modal('hide');
                        $('#addItemsForm')[0].reset();
                        setItemViaExcel(excelSheetItems[++excelSheetItemsIndex]);
                    } else { // At the last item                        
                        $('.items-from-excel-sheet-loader').fadeOut(250);
                        $('#_items_excel_button_saveBtn').addClass('d-none');
                        excelSheetItemsIndex = 0;
                        endTime = new Date();

                        var loadFromExcelTime = endTime - startTime; //in ms
                        // strip the ms
                        loadFromExcelTime /= 1000;
                        console.log(loadFromExcelTime + " second" + (loadFromExcelTime > 1? 's': ''));
                    }

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
    }
}

function addNewEmptyItem() {
    $('.current-currency-text').text($('#currency').prev().text());
    $('#addItemsForm')[0].reset();

    // if budget quantity is one
    if ($('#purchase-order-type').val() == 'budget') {
        $('.quantity').attr('readonly', true).val(1);
        $('#unit').attr('disabled', true).val('C62'); // C62 for lumsum unit type
    }

    tableRowCount = $('.tableForItems tbody tr').length;// 🚩
    items.push(new Item());
    $('#addline').data('checkData', 'null');
    checkDataInModal = $('#addline').data('checkData');
}

function submitDataToNewItem(params) {
    calcTotalForm();

    if (items.length == 0)
        $('#items').val('');
    else
        $('#items').val(items.length);

    $('#items').trigger('keyup');

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
        <td>${parseFloat(items[tableRowCount].item_price).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 5 })}</td>
        <td>${parseFloat(items[tableRowCount].sales_amount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
        <td>${items[tableRowCount].discount_items_rate}</td>
        <td>${items[tableRowCount].discount_items_number ? parseFloat(items[tableRowCount].discount_items_number).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''}</td>
        <td>${items[tableRowCount].taxable_fees ? parseFloat(items[tableRowCount].taxable_fees).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''}</td>
        <td>${items[tableRowCount].differ_value ? parseFloat(items[tableRowCount].differ_value).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''}</td>
        <td>${items[tableRowCount].items_discount ? parseFloat(items[tableRowCount].items_discount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''}</td>
        <td>${parseFloat(items[tableRowCount].net_total).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
        <td>${parseFloat(items[tableRowCount].total_amount).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
        <td>
            <button type="button" class="btn btn-danger tableItemsBtn deleteItem" data-item-id="${tableRowCount}"><i class="fa fa-trash"></i></button>
            <button type="button" data-toggle="modal" data-target="#addline" class="btn btn-warning tableItemsBtn editItem" data-item-id="${tableRowCount}"><i class="fa fa-edit"></i></button>
        </td></tr>`;

    $(".tableForItems tbody").append(markup);

    $('.tax-type').find('option').show();

    total = sumOfTotalItemAmount() || 0;

    $('#purchase-order-total').val(total.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
}

function resetItem(rowIndex, columnName, taxesError = false, errorMessage) {
    items = [];
    $(".tableForItems tbody").html('');
    $('#purchase-order-total').val('');
    $('.items-from-excel-sheet-loader').fadeOut(250);
    excelSheetItemsIndex = 0;

    $("#items_excel_id").val('');
    $("#items_excel_id").removeClass('is-valid valid');
    $('#addItemsViaExcel label').text(filePlaceHolder);
    if (taxesError)
        alert(`Check your excel sheet format in row ${rowIndex}, ${errorMessage}`);
    else
        alert(`Check your excel sheet format in row ${rowIndex}, column ${columnName}`);
}
