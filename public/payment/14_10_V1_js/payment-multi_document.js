
let deductions = [],
    documents = [],
    documentPage = false,
    ajaxURL = '',
    ajaxMethod = '',
    paymentId = null,
    sumOfDeductionValues = 0,
    newAddedValues = 0,
    deletedValues = 0,
    paymentTable = null,
    availableAmout = 0,
    usedDocumentIds = [];
class Deduction {
    constructor(document_id = 0, deduction_id = 0, deduction_name = '', deduction_value = 0) {
        this.document_id = document_id;
        this.deduction_id = deduction_id;
        this.deduction_name = deduction_name;
        this.deduction_value = deduction_value;
    }

    setDeduction(document_id, deduction_id, deduction_name, deduction_value) {
        this.document_id = document_id;
        this.deduction_id = deduction_id;
        this.deduction_name = deduction_name;
        this.deduction_value = deduction_value;
    }
}

class Document {
    constructor(record_id = null, totalAmount = 0, totalPayments = 0, currentPayment = 0, sumOfDeductionValues = 0) {
        this.record_id = record_id;
        this.totalAmount = totalAmount;
        this.totalPayments = totalPayments;
        this.currentPayment = currentPayment;
        this.sumOfDeductionValues = sumOfDeductionValues;
    }

    reset(record_id = null, totalAmount = 0, totalPayments = 0, currentPayment = 0, sumOfDeductionValues = 0) {
        this.record_id = record_id;
        this.totalAmount = totalAmount;
        this.totalPayments = totalPayments;
        this.currentPayment = currentPayment;
        this.sumOfDeductionValues = sumOfDeductionValues;
    }
}

documents.push(new Document());

var form = $("#PaymentForm");
// Fire main.js content
(function ($) {
    var modal = $("#adddeductionsForm");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: {
            client_type: {
                required: true,
            },
            client_name: {
                required: true,
            },
            tax_id_number_or_national_id_or_vat_id: {
                required: true,
            },
            validate_documents: {
                required: true,
            },
            // "purchaseorder_id[]": {
            //     required: true
            // },
            // "document_id[]": {
            //     required: true
            // },
            bank_id: {
                required: true
            },
            // deduction_counter: {
            //     required: true
            // },
            payment_method: {
                required: true
            },
            payment_date: {
                required: true
            },
            // "total_money[]": {
            //     required: true
            // },
        },
        messages: {
            client_type: {
                required: validationMessages['client_type']
            },
            client_name: {
                required: validationMessages['client_name']
            },
            tax_id_number_or_national_id_or_vat_id: {
                required: validationMessages['client_id']
            },
            validate_documents: {
                required: 'required',
            },
            // "purchaseorder_id[]": {
            //     required: validationMessages['PO_id']
            // },
            // "document_id[]": {
            //     required: validationMessages['document_id']
            // },
            bank_id: {
                required: validationMessages['bank_id']
            },
            // deduction_counter: {
            //     required: validationMessages['deduction_counter']
            // },
            payment_method: {
                required: validationMessages['payment_method']
            },
            payment_date: {
                required: validationMessages['payment_date']
            },
            // "total_money[]": {
            //     required: validationMessages['value']
            // },
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
            deduction: {
                required: true,
            },
            value: {
                required: true,
            },
        },
        messages: {
            deduction: {
                required: validationMessages['deduction'],
            },
            value: {
                required: validationMessages['value'],
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
            // var validator = jQuery('#PaymentForm').validate();
            // if (!jQuery('#PaymentForm').valid()) {
            //     var submitErrorsList = new Object();
            //     for (var i = 0; i < validator.errorList.length; i++) {
            //         submitErrorsList[validator.errorList[i].element.name] = validator.errorList[i].message;
            //     }
            // }
            // console.log("Submit Errors", submitErrorsList);
            return form.valid();
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            // console.log(form.valid());
            // var validator = jQuery('#PaymentForm').validate();
            // if (!jQuery('#PaymentForm').valid()) {
            //     var submitErrorsList = new Object();
            //     for (var i = 0; i < validator.errorList.length; i++) {
            //         submitErrorsList[validator.errorList[i].element.name] = validator.errorList[i].message;
            //     }
            // }
            // console.log("Submit Errors", submitErrorsList);
            validateDocuments();
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            $("select").prop("disabled", false);
            submitPayment();
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

$(".actions a[href$='#next']").text(language['next']);
$(".actions a[href$='#previous']").text(language['prev']);
$(".actions a[href$='#finish']").text(language['save']);

$('.search-bank.spinner-border').hide();
$('.search-product.spinner-border').hide();

// Set Clients Data To Select
function SetClientDataToSelect(_clients, targetName) {
    targetName.attr('disabled', false);
    let clients = JSON.parse(_clients);
    targetName.empty();
    targetName.append(language['select_client_name']);
    clients.forEach(client => {
        targetName.append(
            `<option value="${client.id}" data-reference = "${client.reference}">${client.name} (${client.reference})</option>`
        );
    });
    $('.loader-container').fadeOut(250);
}

// Set Purchase Orders For Clients
function setPurchaseOrdersForClient(response, targetName) {
    if (response.length == 0) {
        $('#tax_id_number_or_national_id_or_vat_id').val('');
        $('.client-has-no-purchaseOrders').removeClass('d-none');
        $('.loader-container').fadeOut(250);
        return;
    }
    $('.client-has-no-purchaseOrders').addClass('d-none');
    targetName.attr('disabled', false);
    targetName.empty();
    targetName.append(language['select_purchaseOrder']);
    for (const key in response) {
        if (response.hasOwnProperty.call(response, key)) {
            targetName.append(
                `<option value="${key}">${response[key]}</option>`
            );
        }
    }
    $('.loader-container').fadeOut(250);
}

// Set Documents For Purchase Orders
function setDocumentsForPurchaseOrders(response, targetName) {
    targetName.attr('disabled', false);
    targetName.empty();
    targetName.append(language['select_document']);
    for (const key in response) {
        if (response.hasOwnProperty.call(response, key)) {
            if (usedDocumentIds.indexOf(key) == -1)
                targetName.append(
                    `<option value="${key}">${response[key]}</option>`
                ); else
                targetName.append(
                    `<option disabled value="${key}">${response[key]}</option>`
                );
        }
    }
    targetName.prop('selectedIndex', 0);
    getAllUsedDocumentIds();
    $('.loader-container').fadeOut(250);
}

// General function to send Ajax request and call success function with target tag
function sendAjax(method, url, target, successFunction, data) {
    if (method.toLowerCase() == 'get') {
        $('.loader-container').fadeIn();
        target.attr('disabled', true);
        $.ajax({
            type: method,
            data: data,
            url: `${url}`,
            success: function (response) {
                successFunction(response, target);
            }
        });
    } else if (method.toLowerCase() == 'post') {
        $('.loader-container').fadeIn();
        target.attr('disabled', true);
        $.ajax({
            type: method,
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'JSON',
            url: `${url}`,
            success: function (response) {
                successFunction(response, target);
            }
        });
    }
}

// Handle Client Type Event (Get all client with this type)
$('#client_type').on('change', function () {
    resetAllpayments();
    $('.client-has-no-purchaseOrders').addClass('d-none');
    $('#client_type').val() ? $('.client_type_error').addClass('d-none') : $('.client_type_error')
        .removeClass('d-none');

    if ($('#client_type').val()) {
        if ($('#client_type-error').length != 0) {
            $('#client_type-error').hide();
        }
    }

    $('#client_id').val('');
    $('#tax_id_number_or_national_id_or_vat_id').val('');
    $('#purchase_order').empty();

    let SelectedClientType = $(this).val();
    // check Selected type
    if (SelectedClientType == 'b' || SelectedClientType == 'p' || SelectedClientType == 'f') {
        $('.client-details').removeClass('d-none');
        let labelOrInputParent = $('#tax_id_number_or_national_id_or_vat_id').parent();
        // get data(label and name) value in option selected
        let dataLabel = $(this).find('option:selected').data('label');
        // change label text and input name
        labelOrInputParent.find('label').text(dataLabel);

        let targetName = $('#client_name');

        if (targetName != '') {
            const urlInputType = SelectedClientType;
            const url = `${subFolderURL}/${urlLang}/reports/getALLClientsViaClientType`;
            sendAjax('GET', url, targetName, SetClientDataToSelect, {
                clientType: urlInputType,
            })
        }
    }
});

// Event To Handle Client Name (Get related purchase order for this client)
$('#client_name').change(function (e) {
    resetAllpayments();
    $('#client_name').val() ? $('.client_name_error').addClass('d-none') : $('.client_name_error')
        .removeClass('d-none');

    if ($('#client_name').val()) {
        if ($('#client_name-error').length != 0) {
            $('#client_name-error').hide();
            $('#tax_id_number_or_national_id_or_vat_id-error').hide();
            $('#tax_id_number_or_national_id_or_vat_id').removeClass('error');
        }
    }

    $('#client_id').val('');
    $('#tax_id_number_or_national_id_or_vat_id').val('');

    $('#tax_id_number_or_national_id_or_vat_id').val($(this).find(':selected').data(
        'reference')); // get reference from selected option data-reference

    $('.client-details .text-danger').addClass('d-none');

    const targetSelector = $('.purchaseorder_id:first');
    const urlInputId = $(this).val();
    const clientType = $('#client_type').val();
    const url = `${subFolderURL}/${urlLang}/reports/getPurchaseOrdersForClient`;
    if ($(this).val()) {
        sendAjax('POST', url, targetSelector, setPurchaseOrdersForClient, {
            clientType: clientType,
            urlInputId: urlInputId
        });
    }
});

// Set bank
$('#bank_code').keydown(function (e) {
    var key = e.which;
    var searchContent = $(this).val();
    if (key == 13) {
        $('#bank_id').val('');
        e.preventDefault();
        $('.search-bank.spinner-border').show();
        $('.vaild-client-register-tax').text("");
        $.ajax({
            type: 'GET',
            url: `${subFolderURL}/${urlLang}/getBankData/` + searchContent,
            success: function (response) {
                var responses = JSON.parse(response);
                if (responses.length > 0) {
                    responses.forEach(element => {
                        $('#bank_id').val(element['id']);
                        $('#bank_id').trigger('keyup');
                        $('#bank_name').val(element['bank_name']);
                        $('#bank_account_number').val(element['bank_account_number']);
                        $('#bank_currency').val(element['currency']);
                    });
                } else {
                    $('#bank_id').val('');
                    $('#bank_id').val('')
                    $('#bank_name').val('');
                    $('#bank_account_number').val('');
                    $('#bank_currency').val('');
                    $('.vaild-client-register-tax').text(
                        "check again");
                }
            },
            error: function () {
                $('.vaild-client-register-tax').text(language['error']);
            },
            complete: function () {
                $('.search-bank.spinner-border').hide();
            }
        });
    }
});

// Get document when select purchaseorder
$('#documents-container').on('change', '.purchaseorder_id', function () {
    const documentIndex = $(this).data('documentIndex');
    resetDocumentOrPO($(this), documentIndex);

    if ($(this).val()) {
        $(this).select2();
        $(this).select2();

        $(this).siblings('.validation-label').addClass('d-none');
        const targetSelector = $(this).parent().next().find('.document_id'); //$('.document_id');
        const purchaseOrder_id = $(this).val();
        const url = `${subFolderURL}/${urlLang}/reports/get-documents-belong-to-purchaseOrder`;
        if ($(this).val() != '') {
            sendAjax('POST', url, targetSelector, setDocumentsForPurchaseOrders, {
                purchaseOrder_id: purchaseOrder_id,
            });
        }
    } else {
        $(this).siblings('.validation-label').removeClass('d-none');
        $(this).parents('document-container').find('.document_id').attr('disabled', true);
    }
})

// Document change
$('#documents-container').on('change', '.document_id', function () {
    $('#available-payment-value').parent().addClass('d-none');
    const documentIndex = $(this).data('documentIndex');
    $(this).parents('document-container').find('.total_money').val('');

    const data = {
        'id': $(this).val(),
    }

    resetDocumentOrPO($(this), documentIndex);

    if ($(this).val()) {
        $(this).parents('.document-container').find('.total_money').attr('readonly', false);

        $(this).siblings('.validation-label').addClass('d-none');

        $.ajax({
            url: `${subFolderURL}/${urlLang}/payment/document/payment-details`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(data),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (documentPaymentDetails) {
                totalAmount = documentPaymentDetails.totalAmount;
                totalPayments = documentPaymentDetails.totalPayments;

                documents[documentIndex - 1].record_id = data.id;
                documents[documentIndex - 1].totalPayments = totalPayments;
                documents[documentIndex - 1].totalAmount = totalAmount;
                documents[documentIndex - 1].sumOfDeductionValues = 0;

                changeAvailableAmout(documents[documentIndex - 1]);
                getAllUsedDocumentIds();
            }
        });
    } else {
        $(this).siblings('.validation-label').removeClass('d-none');
        $(this).parents('.document-container').find('.total_money').attr('readonly', true);
        getAllUsedDocumentIds();
    }
});

// Add new document to this payment
$('#add-new-document').on('click', function () {
    documents.push(new Document());
    $('.delete-document').removeClass('d-none');

    const new_document = $(`
                    <div class="row align-content-center justify-content-center document-container p-3 m-0">
                    <h2 class="text-muted col-12 pt-0 pb-1">${language['document']} <span class="document-counter">${documents.length}</span></h2>
                    <hr style="flex: 0 0 100%;">
                    <!-- Purchase Orders -->
                    <div class="col-md-5 purchase_orders_container">
                        <label class="form-label d-block w-100 textDirection">${language['select_document_placeholder']}</label>
                        <select name="purchaseorder_id[]" class="form-control require purchaseorder_id" data-document-index = "${documents.length}">
                        </select>
                        <label class="my-error d-none validation-label">${validationMessages['PO_id']}</label>
                    </div>
                    <!-- documents -->
                    <div class="col-md-6 documents_container">
                        <label class="form-label d-block w-100 textDirection">${language['select_purchaseOrder__placeholder']}</label>
                        <select  name="document_id[]" class="form-control require document_id" disabled data-document-index = "${documents.length}">
                            <option selected disabled class="placeholder-option" value="">
                            ${language['select_purchaseOrder__placeholder']}
                            </option>
                        </select>
                        <label class="my-error d-none validation-label">${validationMessages['document_id']}</label>
                    </div>
                    <div class="col-md-1 px-0 text-center remove-document">
                        <span class="btn btn-danger delete-document" data-document-index = "${documents.length}" style="font-size: 12px;padding: 3px 7px;margin-top: 29.5px;"><i class="fa fa-trash-alt m-0"></i></span>
                    </div>
                    <!-- total_money -->
                    <div class=" col-md-6 my-2">
                        <label for="type" class="form-label textDirection">${language['amount']}</label>
                        <input type="number" name="total_money[]" class="form-control total_money" required data-document-index = "${documents.length}"
                            placeholder="${language['value_placeholder']}" readonly  min="0"
                            oninvalid="this.setCustomValidity('@lang('site.please') ${language['value_placeholder']}')"
                            oninput="setCustomValidity('')">
                        <label class="my-error d-none validation-label">${validationMessages['value']}</label>

                            <p class="text-danger text-bold text-center d-none validate-payment_document-overflow">
                            ${language['payment_document_overflow_error']}</p>
                    </div>

                    <div class="col-md-6">
                        <a href="#" data-toggle="modal" data-target="#addline" class="addNewDeduction" data-document-index = "${documents.length}"
                            style="margin-top: 44px;display: inline-block;" id="_addNewDeductionBtn"><i
                                class="fa fa-plus"></i> ${language['add_deduction']}</a>
                    </div>

                    <div class="col-12 mt-3">
                        <!-- Show deductions -->
                        <div class="table-responsive">
                            <!-- Table for view addded items -->
                            <table
                                class="table table-bordered table-striped table-hover justify-content-center text-center m-0 deductions-table d-none0" data-document-index = "${documents.length}">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            #
                                        </th>
                                        <th scope="col">
                                            ${language['deduction']}
                                        </th>
                                        <th scope="col">
                                            ${language['value']}
                                        </th>
                                        <th scope="col">
                                            ${language['actions']}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <th>
                                        ${language['total']}
                                    </th>
                                    <th colspan="3">
                                    </th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
        `);

    firstRowPurchaseorderData = $('.document-container:first .purchaseorder_id  > option');

    new_document.find('.purchaseorder_id').append(firstRowPurchaseorderData.clone(true));
    new_document.insertBefore($('#add-new-document').parent());

    new_document.find('.purchaseorder_id').prop('selectedIndex', 0);

    $(new_document.find('.purchaseorder_id')).select2();
    $(new_document.find('.purchaseorder_id')).select2({
        placeholder: language['select_purchaseOrder__placeholder'],
    });

    $(new_document.find('.document_id')).select2();
    $(new_document.find('.document_id')).select2({
        placeholder: language['select_document_placeholder'],
    });

    getAllUsedDocumentIds();
});

// Delete document from this payment
$('#documents-container').on('click', '.delete-document', function () {
    const documentIndex = $(this).data('documentIndex');
    resetDocumentOrPO($(this).parents('.document-container').find('.document_id'), documentIndex); // delete all deductions from deductions []
    documents.splice(documentIndex - 1, 1);// delete document from documents []
    $(this).parents('.document-container').remove(); // from view
    if (documents.length <= 1) {
        $('.delete-document').addClass('d-none');
    } else {
        $('.delete-document').removeClass('d-none');
    }
    resetDocumentsCounter();
    getAllUsedDocumentIds();
});

// Validate on added values for cheque, bank transfer or cashe
$('#documents-container').on('change keyup focus', '.total_money', function () {
    const documentIndex = $(this).data('documentIndex');

    if ($(this).val()) {
        $(this).siblings('.validation-label').addClass('d-none');
        $(this).removeClass('error');
    } else {
        $(this).siblings('.validation-label').removeClass('d-none');
        $(this).addClass('error');
    }

    targetErrorTag = $(this).siblings('.validate-payment_document-overflow');

    const that = $(this);

    let value = that.val().trim();

    if (availableAmoutOverflowErrorMessage == '') {
        availableAmoutOverflowErrorMessage = targetErrorTag.text().trim().split('()');
    }

    availableAmout = fixedTo20((documents[documentIndex - 1].totalAmount) - (documents[documentIndex - 1].totalPayments + documents[documentIndex - 1].sumOfDeductionValues));

    if (value > availableAmout) {
        let errorMessage = [...availableAmoutOverflowErrorMessage];
        errorMessage.splice(1, 0, ` (${parseFloat(value).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })}) `);
        errorMessage = errorMessage.join(' ');
        targetErrorTag.removeClass('d-none').text(`${errorMessage} (${availableAmout.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })})`);
        that.val('');
        documents[documentIndex - 1].currentPayment = 0;
    } else {
        targetErrorTag.addClass('d-none');
        documents[documentIndex - 1].currentPayment = fixedTo20(value);
        let targetTable = $(`.deductions-table[data-document-index=${documentIndex}]`);
        getSumOfAddedDeductionsForDocument(documents[documentIndex - 1], targetTable);
    }
    changeAvailableAmout(documents[documentIndex - 1]);
    changeSumOfPaymentsValues();
})


// Add new deduction button
$('#documents-container').on('click', '#_addNewDeductionBtn', function (e) {

    // get current document to deduction
    const documentIndex = $(this).data('documentIndex');
    $('#deduction_value').data('documentIndex', documentIndex);

    let valid = true;
    const __allDocument = $('.document-container'); // jQuery
    const allDocument = document.querySelectorAll('.document-container'); // Native js

    // Validate PurchaseOrder
    if (allDocument[documentIndex - 1].querySelectorAll('.purchaseorder_id')[0].value) {
        $(__allDocument[documentIndex - 1]).find('.purchaseorder_id').siblings('.validation-label').addClass('d-none');
    } else {
        $(__allDocument[documentIndex - 1]).find('.purchaseorder_id').siblings('.validation-label').removeClass('d-none');
        valid = false;
    }

    // Validate Document
    if (allDocument[documentIndex - 1].querySelectorAll('.document_id')[0].value) {
        $(__allDocument[documentIndex - 1]).find('.document_id').siblings('.validation-label').addClass('d-none');
    } else {
        $(__allDocument[documentIndex - 1]).find('.document_id').siblings('.validation-label').removeClass('d-none');
        valid = false;
    }

    // Prevent add deduction if document and purchaseOrder not selected
    if (!valid) {
        e.stopPropagation();
        return;
    }

    // Reset model data
    $('#addline').on('hidden.bs.modal', function () {
        $("#adddeductionsForm")[0].reset();
    });

    $('#adddeductionsForm label.error').remove();
    $('#adddeductionsForm input.error').removeClass('error');

    $('#validate-payment_document-overflow-deduction').addClass('d-none');
    $('#validate-payment_document-overflow-deduction').addClass('d-none');
    $('#deduction_value-error').remove();
    $('#deduction_value').removeClass('.error');

    $('#deduction').prop('selectedIndex', 0);
    $('#deduction').select2();
})

// Submit modal (To save deduction data)
$("#adddeductionsForm").submit(function (event) {
    event.preventDefault();
    const documentIndex = $('#deduction_value').data('documentIndex');
    if ($(this).valid()) {
        let deduction_id = $('#deduction').val();
        let deduction_name = $("#deduction option:selected").text();
        let deduction_value = $('#deduction_value').val();
        let targetTable = $(`.deductions-table[data-document-index=${documentIndex}]`);

        let newDeduction = new Deduction(documents[documentIndex - 1].record_id, deduction_id, deduction_name, parseFloat(deduction_value));
        deductions.push(newDeduction);

        let currentDocument = documents[documentIndex - 1];
        let filteredDeductions = deductions.filter(deduction => deduction.document_id == currentDocument.record_id);

        let markup = `<tr><th> ${filteredDeductions.length}</th>
                    <td> ${newDeduction.deduction_name}</td>
                    <td>${parseFloat(newDeduction.deduction_value).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })}</td>
                    <td>
                        <button type="button" class="btn btn-danger tableItemsBtn deleteDeduction" data-deduction-index="${deductions.length - 1}"><i class="fa fa-trash-alt"></i></button>
                    </td></tr>`;

        targetTable.find('tbody').append(markup);
        // $(".deductions-table tbody").append(markup);

        $('#addline').modal('hide');

        getSumOfAddedDeductionsForDocument(documents[documentIndex - 1], targetTable);

        changeAvailableAmout(documents[documentIndex - 1]);
        changeSumOfPaymentsValues();
    }
});

// Delete deduction button
$('#documents-container ').on('click', '.deleteDeduction', function () {
    const documentIndex = $(this).parents('.deductions-table').data('documentIndex');
    let targetTable = $(`.deductions-table[data-document-index=${documentIndex}]`);
    let deductionIndex = $(this).data('deductionIndex');
    deductions.splice(deductionIndex, 1);
    $(this).parents('tr').remove();

    if (deductions.length == 0)
        $('#deduction_counter').val('');
    else
        $('#deduction_counter').val(deductions.length);

    // Reset model data
    $('#addline').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });

    resetItemCounter();
    getSumOfAddedDeductionsForDocument(documents[documentIndex - 1], targetTable);
    changeAvailableAmout(documents[documentIndex - 1]);
    changeSumOfPaymentsValues();
    $('#available-payment-value').text((availableAmout).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 }));
});

// Validate on added values for deduction
let availableAmoutOverflowErrorMessage = '';
$('#deduction_value').on('change keyup', function () {
    const documentIndex = $(this).data('documentIndex');
    let targetErrorTag = null;

    if (paymentTable == 'PO') {
        targetErrorTag = $('#validate-payment_purchase_order-overflow');
    } else {
        targetErrorTag = $('#validate-payment_document-overflow-deduction');
    }

    const that = $(this);

    let value = that.val().trim();

    if (availableAmoutOverflowErrorMessage == '') {
        availableAmoutOverflowErrorMessage = targetErrorTag.text().trim().split('()');
    }
    changeAvailableAmout(documents[documentIndex - 1]);

    if (value > availableAmout) {
        let errorMessage = [...availableAmoutOverflowErrorMessage];
        errorMessage.splice(1, 0, ` (${parseFloat(value).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })}) `);
        errorMessage = errorMessage.join(' ');
        targetErrorTag.removeClass('d-none').text(`${errorMessage} (${availableAmout.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })})`);
        that.val('');
    } else {
        targetErrorTag.addClass('d-none');
        sumOfDeductions();
    }
});

// Reset Item counter deduction
function resetItemCounter() {
    const addDeductionsInTable = $(".deductions-table tbody tr");
    addDeductionsInTable.each((index, item) => {
        $(item).find('.deleteDeduction').data('deduction-index', index);  // change deduction-index delete button
        addDeductionsInTable.eq(index).find('th').text(index + 1); // Change item index in table
    });

    const deductionsTables = $(".deductions-table tbody");
    deductionsTables.each((index, table) => {
        $(table).find('tr').each((index, item) => {
            $(item).find('th').text(index + 1); // Change item index in table
        });
    });


    numberOfAddedDeductions = $('.deductions-table tbody tr').length;
}

// Submit data
function submitPayment() {

    for (let index = 0; index < deductions.length; index++) {
        delete deductions[index].deduction_name;
    }

    const basicData = form.serializeArray();
    let filteredBasicData = [];

    const deletedElements = ["_token", "tax_id_number_or_national_id_or_vat_id", "validate_documents", "purchaseorder_id[]", "document_id[]", "total_money[]"];
    for (let index = 0; index < basicData.length; index++) {
        const element = basicData[index];
        if (deletedElements.indexOf(element.name) == -1) {
            filteredBasicData.push(element)
        }
    }

    var payment = {
        basicData: filteredBasicData,
        deductions: deductions,
        documents: documents,
    }

    // Start solving sending data to server as all number are automatically rounded
    payment.documents.forEach(document => {
        document.totalAmount = '' + document.totalAmount;
        document.totalPayments = '' + document.totalPayments;
        document.currentPayment = '' + document.currentPayment;
        document.sumOfDeductionValues = '' + document.sumOfDeductionValues;
    });

    payment.deductions.forEach(deduction => {
        deduction.deduction_value = '' + deduction.deduction_value;
    });
    // End solving sending data to server as all number are automatically rounded

    $(".actions a[href$='#finish']").css("pointer-events", "none");
    $(".actions a[href$='#finish']").text(language['send_data']);
    $('.loader-container').fadeIn();
    $.ajax({
        url: ajaxURL,
        type: ajaxMethod,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify(payment),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (res) {
            if (res.ids != []) {
                if ($("input[name=file]").val()) { // if file is set
                    // Send ajax for uploading PO file
                    const files = $("input[name=file]")[0].files;
                    const formObject = new FormData();
                    formObject.append('file', files[0])
                    formObject.append('payment_ids', JSON.stringify(res.ids))
                    $.ajax({
                        url: `${subFolderURL}/${urlLang}/payment/fileStore`,
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
                            $(".actions a[href$='#finish']").text(language['data_sent']);
                            window.location.href = `${subFolderURL}/${urlLang}/payment`;
                        },
                        error: function (request, status, error) {
                            $(".actions a[href$='#finish']").text(language['send_data_error']);
                            $(".actions a[href$='#finish']").css("pointer-events", "");
                            $('.loader-container').fadeOut(250);
                            // window.location.href = `${subFolderURL}/${urlLang}/payment`;
                        },
                    });
                } else { // if file not set
                    $(".actions a[href$='#finish']").text(language['data_sent']);
                    window.location.href = `${subFolderURL}/${urlLang}/payment`;
                }
            } else {
                $(".actions a[href$='#finish']").text(language['send_data_error']);
                $(".actions a[href$='#finish']").css("pointer-events", "");
            }
        },
        error: function (request, status, error) {
            $(".actions a[href$='#finish']").text(language['send_data_error']);
            $(".actions a[href$='#finish']").css("pointer-events", "");
            $('.loader-container').fadeOut(250);
        }
    });
}

// Get sum of all added deductions
function sumOfDeductions() {
    sumOfDeductionValues = 0;
    for (let index = 0; index < deductions.length; index++) {
        sumOfDeductionValues += deductions[index].deduction_value;
    }
    $('#deduction-total').val(parseFloat(sumOfDeductionValues).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 }));
    $('#deduction-total').next().val(sumOfDeductionValues);
}

function fixedTo20($number) {
    return +(Number($number).toFixed(20));
}

// Change Available Amout
function changeAvailableAmout(document) {
    availableAmout = fixedTo20((document.totalAmount) - (document.totalPayments + document.sumOfDeductionValues + document.currentPayment)); // available = totalAmount - (allPayment + currentValues)
    $('#available-payment-value').text((availableAmout).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })).parent().removeClass('d-none');
    return availableAmout;
}

function validateDocuments() {
    let valid = true;
    const __allDocument = $('.document-container'); // jQuery
    const allDocument = document.querySelectorAll('.document-container'); // Native js
    for (let i = 0, len = allDocument.length; i < len; i++) {
        // const documentIndex = $(allDocument[i]).find('.purchaseorder_id').data('documentIndex');
        const documentIndex = allDocument[i].querySelectorAll('.purchaseorder_id')[0].dataset.documentIndex;

        // Validate PurchaseOrder
        if (allDocument[i].querySelectorAll('.purchaseorder_id')[0].value) {
            $(__allDocument[i]).find('.purchaseorder_id').siblings('.validation-label').addClass('d-none');
        } else {
            $(__allDocument[i]).find('.purchaseorder_id').siblings('.validation-label').removeClass('d-none');
            valid = false;
        }

        // Validate Document
        if (allDocument[i].querySelectorAll('.document_id')[0].value) {
            $(__allDocument[i]).find('.document_id').siblings('.validation-label').addClass('d-none');
        } else {
            $(__allDocument[i]).find('.document_id').siblings('.validation-label').removeClass('d-none');
            valid = false;
        }

        // Validate Total Money
        if ($(allDocument[i]).find('.total_money').val()) {
            $(allDocument[i]).find('.total_money').siblings('.validation-label').addClass('d-none');
            $(allDocument[i]).find('.total_money').removeClass('error');
        } else {
            $(allDocument[i]).find('.total_money').siblings('.validation-label').removeClass('d-none');
            $(allDocument[i]).find('.total_money').addClass('error');
            valid = false;
        }
    }

    if (valid) {
        $('[name="validate_documents"]').val('valid');
    } else {
        $('[name="validate_documents"]').val('');
    }
}

// Reset Item counter documents
function resetDocumentsCounter() {
    let addedDocuments = $('.document-container');

    for (let index = 0; index < addedDocuments.length; index++) {
        const element = $(addedDocuments[index]);
        element.find('.document-counter').text(index + 1);
        element.find('.purchaseorder_id').data('documentIndex', index + 1);
        element.find('.document_id').data('documentIndex', index + 1);
        element.find('.delete-document').data('documentIndex', index + 1);
        element.find('.total_money').data('documentIndex');
        element.find('.addNewDeduction').data('documentIndex', index + 1);
    }
}

// Get all added deduction to specific document
function getSumOfAddedDeductionsForDocument(document, table) {
    let filteredDeductions = deductions.filter(deduction => deduction.document_id == document.record_id);
    for (let index = 0; index < deductions.length; index++) {
        const element = deductions[index];
    }

    let sum = filteredDeductions.reduce(function (total, deduction) {
        return total + deduction.deduction_value;
    }, 0);

    document.sumOfDeductionValues = sum;
    table.find('tfoot th:last').text(parseFloat(sum).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 }));
    return sum;
}

// Reset document when change document or PO select
function resetDocumentOrPO(documentOrPOSelect, documentIndex) {
    documents[documentIndex - 1].reset();
    const deletedBtns = documentOrPOSelect.parents('.document-container').find('.deductions-table tbody tr deleteDeduction');
    const totalMoney = documentOrPOSelect.parents('.document-container').find('.total_money');

    for (let index = 0; index < deletedBtns.length; index++) {
        const element = deletedBtns[index];
        let deductionIndex = $(this).data('deductionIndex');
        deductions.splice(deductionIndex, 1);
        $(this).parents('tr').remove();
    }

    documentOrPOSelect.parents('.document-container').find('.deductions-table tbody tr').remove();
    documentOrPOSelect.parents('.document-container').find('.deductions-table tfoot th:last').text('');

    totalMoney.val('');
    changeSumOfPaymentsValues();
}

function resetAllpayments() {
    $('.document-container:not(:first)').remove();
    documents = [documents[0]];
    documents[0].reset();
    deductions = [];
    $('.total_money').val('');
    $('.document_id option').remove();
    $('.document_id').prop('selectedIndex', 0).attr('disabled', true);
    $('.purchaseorder_id').prop('selectedIndex', 0);
    $('.deductions-table tbody').html('');
    $('#available-payment-value').parent().addClass('d-none');
    $('#sum-of-payment-value').parent().addClass('d-none');
    $('#sum-of-deduction-value').parent().addClass('d-none');
    $('.deductions-table').find('tfoot th:last').text('');
    // $('.deductions-table').addClass('d-none');
    changeSumOfPaymentsValues();
}

function changeSumOfPaymentsValues() {

    let sum = documents.reduce(function (total, document) {
        return total + document.currentPayment;
    }, 0);

    let deductionSum = deductions.reduce(function (total, deduction) {
        return total + deduction.deduction_value;
    }, 0);

    if (sum != 0) {
        $('#sum-of-payment-value').text(parseFloat(sum).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 }));
        $('#sum-of-payment-value').parent().removeClass('d-none');
    }
    else
        $('#sum-of-payment-value').parent().addClass('d-none');

    if (deductionSum != 0) {
        $('#sum-of-deduction-value').text(parseFloat(deductionSum).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 }));
        $('#sum-of-deduction-value').parent().removeClass('d-none');
    }
    else
        $('#sum-of-deduction-value').parent().addClass('d-none');
}

function getAllUsedDocumentIds() {
    usedDocumentIds = [];
    for (let index = 0; index < documents.length; index++) {
        if (documents[index].record_id) // not allow null
            usedDocumentIds.push(documents[index].record_id);
    }

    // Show documents if this document is deleted
    $('.document_id option:not(.placeholder-option)').each((index, option) => {
        if (usedDocumentIds.indexOf($(option).val()) != -1) {
            $(option).prop('disabled', true);
        } else {
            $(option).prop('disabled', false);
        }
        $(option).parent().select2();
    });
}

function removeExponential(x) {
    const sign = (x < 0) ? '-' : '';
    if (Math.abs(x) < 1.0) {
        var e = parseInt(x.toString().split('e-')[1]);
        if (e) {
            x *= Math.pow(10, e - 1);
            x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
            const index = x.indexOf('.', (2));
            x = x.substring(0, index) + "" + x.substring(index + 1);
        }
    } else {
        var e = parseInt(x.toString().split('+')[1]);
        if (e > 20) {
            e -= 20;
            x /= Math.pow(10, e);
            x += (new Array(e + 1)).join('0');
        }
    }
    if (String(x)[0] != '-')
        return sign + x;
    else
        return x;
}

$('#set-available-payment-value').on('click', function () {
    $('#deduction_value').val(removeExponential(availableAmout));
})