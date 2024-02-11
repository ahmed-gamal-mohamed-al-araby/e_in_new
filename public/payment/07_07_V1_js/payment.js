let deductions = [],
    documentPage = false,
    ajaxURL = '',
    ajaxMethod = '',
    paymentId = null,
    sumOfDeductionValues = 0,
    newAddedValues = 0,
    deletedValues = 0,
    paymentTable = null,
    editMode = false,
    availableAmout = 0,
    oldValuesInEditMode = 0; // contain old added values for this payment
class Deduction {
    constructor(record_id = 0, deduction_id = 0, deduction_name = '', deduction_value = 0) {
        this.record_id = record_id;
        this.deduction_id = deduction_id;
        this.deduction_name = deduction_name;
        this.deduction_value = deduction_value;
    }

    setDeduction(record_id, deduction_id, deduction_name, deduction_value) {
        this.record_id = record_id;
        this.deduction_id = deduction_id;
        this.deduction_name = deduction_name;
        this.deduction_value = deduction_value;
    }
}

var form = $("#PaymentForm");
// Fire main.js content
(function ($) {
    var modal = $("#adddeductionsForm");
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
            purchaseorder_id: {
                required: true
            },
            document_id: {
                required: true
            },
            bank_id: {
                required: true
            },
            deduction_counter: {
                required: true
            },
            payment_method: {
                required: true
            },
            payment_date: {
                required: true
            },
            total_money: {
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
            purchaseorder_id: {
                required: validationMessages['PO_id']
            },
            document_id: {
                required: validationMessages['document_id']
            },
            bank_id: {
                required: validationMessages['bank_id']
            },
            deduction_counter: {
                required: validationMessages['deduction_counter']
            },
            payment_method: {
                required: validationMessages['payment_method']
            },
            payment_date: {
                required: validationMessages['payment_date']
            },
            total_money: {
                required: validationMessages['value']
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
            return form.valid();
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
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


$('#foreigner-client').on('change', function() {
    $('#client_id').val($(this).val());
    $('#client_id').trigger('change');
    
    var searchContent = $(this).val();
    that = $(this);
    $.ajax({
        type: 'GET',
        url: `${subFolderURL}/${urlLang}/clients/getDocumentForeignerPurchaseOrder/` + searchContent,
        success: function(_purchaseOrders) {
            var purchaseOrders = JSON.parse(_purchaseOrders);

            $('#select_purchase_order :not(:first)').remove();

            if (purchaseOrders.length != 0) {
                $('#purchaseorder_id_container').removeClass('d-none')
                for (let i = 0; i < purchaseOrders.length; i++) {
                    $('#select_purchase_order').append(
                        `<option value="${purchaseOrders[i].id}">${purchaseOrders[i].purchase_order_reference}</option>`
                    );
                }
            } else {
                $('#client_id').val('').parent().next().removeClass('d-none').text(language['client_PO_empty']);
            }
        },
        error: function() {
            $('.vaild-company-register-tax').text("{{ trans('site.error') }}");
        },
        complete: function() {
            $('.search-company.spinner-border').hide();
        }
    });

});

// Client type change
$('#client_type').on('change', function () {
    $('#purchaseorder_id_container').addClass('d-none')
    $('#client_id').val('');

    $('.client-details .text-danger').addClass('d-none');
    $('#tax_id_number_or_national_id').val('');
    $('#client_name').val('');
    $('#client_address').val('');

    let selectValue = $(this).val();
    // check selector value
    if (selectValue == 'b' || selectValue == 'p') {
        $('.client-details').removeClass('d-none');
        $('.select-foreigner-client').addClass('d-none');
        $('#client-id-container').insertBefore(".client-details .row .text-danger");
        let labelOrInputParent = $('#tax_id_number_or_national_id').parent();
        // get data(label and name) value in option selected
        let dataLabel = $(this).find('option:selected').data('label');
        //change label text and input name
        labelOrInputParent.find('label').text(dataLabel);
    } else {
        $('.select-foreigner-client').removeClass('d-none');
        $('.client-details').addClass('d-none');
        $('#client-id-container').insertBefore(".select-foreigner-client .row .text-danger");
    }
    let targetSelector = $('#foreigner-client');
    if (targetSelector != '') {
        const urlInputId = $(this).val();
        const url = `${subFolderURL}/${urlLang}/clients/getClientsFromclientType`;
        if ($(this).val() == 'f')
            sendAjax('GET', url, urlInputId, targetSelector, getForeignerClient)
    }

});

function sendAjax(method, url, urlInputId, targetSelector, successFunction) {
    $('#purchaseorder_id_container').addClass('d-none');
    targetSelector.attr('disabled', true);
    $.ajax({
        type: method,
        url: `${url}`,
        success: function (response) {
            successFunction(response, targetSelector);
        }
    });
}

function getForeignerClient(response, targetSelector) {
    $('#select_purchase_order :not(:first)').remove();

    targetSelector.attr('disabled', false);
    var response = JSON.parse(response);
    targetSelector.empty();
    targetSelector.append(language['select_client_type']);
    for (const key in response) {
        if (response.hasOwnProperty.call(response, key)) {
            targetSelector.append(
                `<option value="${key}">${response[key]}</option>`
            );
        }
    }
    // targetSelector.find(`option[value="${clientId}"]`).attr('selected', true);

    // if (client.purchaseOrders.length != 0) {
    //     $('#purchaseorder_id_container').removeClass('d-none');
    //     $('#client_id').val(client.basic.id);
    //     $('#client_id').trigger('keyup');
    //     // $('#client_id').val(that.val()).parent().next().addClass('d-none').trigger('change');
    //     for (let i = 0; i < client.purchaseOrders.length; i++) {
    //         $('#select_purchase_order').append(
    //             `<option value="${client.purchaseOrders[i].id}">${client.purchaseOrders[i].purchase_order_reference}</option>`
    //         );
    //     }
    // } else {
    //     $('#client_id').val('').parent().next().removeClass('d-none').text(language['client_PO_empty']);
    // }

}

function setDocumentOfPurchaseOrder(response, targetSelector) {
    targetSelector.attr('disabled', false);
    var response = JSON.parse(response);
    targetSelector.empty();
    targetSelector.append(language['select_document']);
    for (const key in response) {
        if (response.hasOwnProperty.call(response, key)) {
            targetSelector.append(
                `<option value="${key}">${response[key]}</option>`
            );
        }
    }
}

function getBusinessOrPersonClientData(client) {

    if (client.length != 0) {
        $('#client_id').val(client.basic.id);
        $('#client_id').trigger('keyup');
        $('#client_name').val(client.basic.name);
        $('#client_address').val(client.basic.address);
        $('#select_purchase_order :not(:first)').remove();

        if (client.purchaseOrders.length != 0) {
            $('#purchaseorder_id_container').removeClass('d-none');
            $('#client_id').val(client.basic.id);
            $('#client_id').trigger('keyup');
            // $('#client_id').val(that.val()).parent().next().addClass('d-none').trigger('change');
            for (let i = 0; i < client.purchaseOrders.length; i++) {
                $('#select_purchase_order').append(
                    `<option value="${client.purchaseOrders[i].id}">${client.purchaseOrders[i].purchase_order_reference}</option>`
                );
            }
        } else {
            $('#client_id').val('').parent().next().removeClass('d-none').text(language['client_PO_empty']);
        }

    } else {
        $('.client-details .text-danger').removeClass('d-none').text(language['no_data']);
        $('#client_name').val('');
        $('#client_address').val('');
    }
    $('.search-bank.spinner-border').hide();
}

// Tax id or national id 
$('#tax_id_number_or_national_id').keydown(function (e) {
    $('#available-payment-value').parent().addClass('d-none');
    let key = e.which;
    if (key == 13) {
        $('#purchaseorder_id_container').addClass('d-none');
        $('#client_id').val('');
        let clientType = $('#client_type').val(),
            searchContent = $(this).val().trim(),
            valid = false,
            sendData = {
                clientType: clientType,
                searchContent: searchContent,
            };

        const validateError = $('#client_type').find('option:selected').data('validate'),
            taxIdNumberRegex = /^[\d]{3}-[\d]{3}-[\d]{3}$/,
            //nationalIdRegex = /^(2|3)[0-9][1-9][0-1][1-9][0-3][1-9](01|02|03|04|11|12|13|14|15|16|17|18|19|21|22|23|24|25|26|27|28|29|31|32|33|34|35|88)\d\d\d\d\d$/;
            nationalIdRegex = /^[0-9]{14}$/;
        if (clientType == 'b') { // Validate Tax Id Number
            if (taxIdNumberRegex.test(searchContent)) { // valid
                $('.client-details .text-danger').addClass('d-none')
                valid = true;
            } else {
                $('.client-details .text-danger').removeClass('d-none').text(validateError);

            }
        } else if (clientType == 'p') { // Validate National Id Number
            if (nationalIdRegex.test(searchContent)) { // valid
                $('.client-details .text-danger').addClass('d-none');
                valid = true;
            } else {
                $('.client-details .text-danger').removeClass('d-none').text(validateError);
                $('#client_name').text('');
                $('#client_address').text('');
            }
        }
        if (valid) { // If Valid for
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $('.search-bank.spinner-border').show();
            $.ajax({
                type: 'POST',
                url: `${subFolderURL}/${urlLang}/clients/getDocumentBusinessOrPersonClientData`,
                data: sendData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function (client) {
                    // reset items
                    items = [];
                    $('#items').val(''); // number of addeditem
                    $('#invoice-discount').val('');
                    $('#invoice-total').val('');
                    $(".deductions-table tbody").html(''); // clear item in table
                    $('#select_purchase_order').prop('selectedIndex', 0);
                    $('#select_purchase_order :not(:first)').remove();
                    getBusinessOrPersonClientData(client);
                }
            });
        }
    }
});

// Add new deduction button
$('#_addNewDeductionBtn').on('click', function () {
    // Reset model data
    $('#addline').on('hidden.bs.modal', function () {
        $("#adddeductionsForm")[0].reset();
    });

    $('#adddeductionsForm label.error').remove();
    $('#adddeductionsForm input.error').removeClass('error');
})

// Purchase order change
$('#select_purchase_order').on('change', function () {
    $('#available-payment-value').parent().addClass('d-none');

    const data = {
        'id': $(this).val(),
    }

    if (documentPage) {
        $('#document_id_container').addClass('d-none');

        const targetSelector = $('#purchaseorder_id_container');
        targetSelector.removeClass('d-none');
        $.ajax({
            url: `${subFolderURL}/${urlLang}/getDocumentFromPurchaseOrderByPOID`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(data),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (PO_documents) {
                if (PO_documents.length > 0) {
                    $('.no_documents').addClass('d-none');
                    $('#document_id_container').removeClass('d-none');
                    $('#select_document :not(:first)').remove();
                    for (let i = 0; i < PO_documents.length; i++) {
                        $('#select_document').append(
                            `<option value="${PO_documents[i].id}">${PO_documents[i].document_number}</option>`
                        );
                    }
                    $('#select_document').prop('selectedIndex', 0);
                } else {
                    $('#select_document :not(:first)').remove();
                    $('#document_id_container').removeClass('d-none');
                    $('#select_document').append(`<option selected disabled value="">${$('.no_documents div').text()}</option>`);
                }
                $('#available-payment-value').parent().addClass('d-none');
            }
        });
    } else {
        $.ajax({
            url: `${subFolderURL}/${urlLang}/payment/purchaseorder/payment-details`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(data),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (POPaymentDetails) {
                totalAmount = POPaymentDetails.totalAmount;
                totalPayments = POPaymentDetails.totalPayments;
                changeAvailableAmout();
                $('#available-payment-value').text((availableAmout).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 8 })).parent().removeClass('d-none');
            }
        });
    }
})

// Document change
$('#select_document').on('change', function () {
    $('#available-payment-value').parent().addClass('d-none');

    const data = {
        'id': $(this).val(),
    }

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
            changeAvailableAmout();
            $('#available-payment-value').text((availableAmout).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 8 })).parent().removeClass('d-none');
        }
    });
});

// Submit modal (To save item data)
$("#adddeductionsForm").submit(function (event) {
    event.preventDefault();

    if ($(this).valid()) {
        let deduction_id = $('#deduction').val();
        let deduction_name = $("#deduction option:selected").text();;
        let deduction_value = $('#deduction_value').val();

        let newDeduction = new Deduction(null, deduction_id, deduction_name, parseFloat(deduction_value));
        deductions.push(newDeduction);

        let markup = `<tr><th> ${deductions.length}</th>
                        <td> ${newDeduction.deduction_name}</td>
                        <td>${parseFloat(newDeduction.deduction_value).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 8 })}</td>
                        <td>
                            <button type="button" class="btn btn-danger tableItemsBtn deleteDeduction" data-deduction-index="${deductions.length - 1}"><i class="fa fa-trash-alt"></i></button>
                        </td></tr>`;

        $(".deductions-table tbody").append(markup);

        $('#addline').modal('hide');

        sumOfDeductions();

        if (deductions.length == 0)
            $('#deduction_counter').val('');
        else
            $('#deduction_counter').val(deductions.length);

        $('#deduction_counter').removeClass('error');
        $('#deduction_counter-error').remove();

        changeAvailableAmout();
        $('#available-payment-value').text((availableAmout).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 8 }));
    }
});

// Delete deduction button
$('.deductions-table tbody').on('click', '.tableItemsBtn.deleteDeduction', function () {
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

    sumOfDeductions();
    resetItemCounter();

    changeAvailableAmout();
    $('#available-payment-value').text((availableAmout).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 8 }));
});

// Reset Item counter
function resetItemCounter() {
    const addDeductionsInTable = $(".deductions-table tbody tr");
    addDeductionsInTable.each((index, item) => {
        $(item).find('.deleteDeduction').data('deduction-index', index);  // change deduction-index delete button
        addDeductionsInTable.eq(index).find('th').text(index + 1); // Change item index in table
    });
    numberOfAddedDeductions = $('.deductions-table tbody tr').length;
}

// Submit data
function submitPayment() {
    for (let index = 0; index < deductions.length; index++) {
        delete deductions[index].deduction_name;
    }

    var payment = {
        basicData: form.serializeArray(),
        deductions: deductions,
    }

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
            if (res.status == 1) {
                $(".actions a[href$='#finish']").text(language['data_sent']);
                window.location.href = `${subFolderURL}/${urlLang}/payment`;
            } else {
                $(".actions a[href$='#finish']").text(language['send_data_error']);
            }
            $('.loader-container').fadeOut(250);
        },
        error: function (request, status, error) {
            $(".actions a[href$='#finish']").text(language['send_data_error']);
            $('.loader-container').fadeOut(250);
        }
    });
}

$(".actions a[href$='#finish']").on('click', function () {
    $('#payment_method-error').insertAfter('#payment_option_error');
});

// Set deductions for edit mode and payment method is deduction
function setDeductionsForEdit(_deductions, _deductionNames) {
    for (let i = 0; i < _deductions.length; i++) {
        deductions.push(new Deduction(_deductions[i].id, _deductions[i].deduction_id, _deductionNames[i], parseFloat(_deductions[i].value)));
    }
    sumOfDeductions();
    oldValuesInEditMode = sumOfDeductionValues;
}

// Get sum of all added deductions
function sumOfDeductions() {
    sumOfDeductionValues = 0;
    for (let index = 0; index < deductions.length; index++) {
        sumOfDeductionValues += deductions[index].deduction_value;
    }
    $('#deduction-total').val(parseFloat(sumOfDeductionValues).toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 8 }));
    $('#deduction-total').next().val(sumOfDeductionValues);
}

// Validate on added values for deduction
let availableAmoutOverflowErrorMessage = '';
$('#deduction_value').on('change keyup', function () {
    let targetErrorTag = null;

    if (paymentTable == 'PO') {
        targetErrorTag = $('#validate-payment_purchase_order-overflow');
    } else {
        targetErrorTag = $('#validate-payment_document-overflow');
    }

    const that = $(this);

    let value = that.val().trim();

    if (availableAmoutOverflowErrorMessage == '') {
        availableAmoutOverflowErrorMessage = targetErrorTag.text().trim().split('()');
    }

    changeAvailableAmout();

    if (value > availableAmout) {
        let errorMessage = [...availableAmoutOverflowErrorMessage];
        errorMessage.splice(1, 0, ` (${value}) `);
        errorMessage = errorMessage.join(' ');
        targetErrorTag.removeClass('d-none').text(`${errorMessage} (${availableAmout})`);
        that.val('');
    } else {
        targetErrorTag.addClass('d-none');
        sumOfDeductions();
    }
});

// Validate on added values for cheque, bank transfer or cashe
$('input[name="total_money"]').on('change keyup', function () {
    let targetErrorTag = null;

    if (paymentTable == 'PO') {
        targetErrorTag = $('#validate-payment_purchase_order-overflow');
    } else {
        targetErrorTag = $('#validate-payment_document-overflow');
    }

    const that = $(this);

    let value = that.val().trim();

    if (availableAmoutOverflowErrorMessage == '') {
        availableAmoutOverflowErrorMessage = targetErrorTag.text().trim().split('()');
    }

    changeAvailableAmout();

    if (value > availableAmout) {
        let errorMessage = [...availableAmoutOverflowErrorMessage];
        errorMessage.splice(1, 0, ` (${value}) `);
        errorMessage = errorMessage.join(' ');
        targetErrorTag.removeClass('d-none').text(`${errorMessage} (${availableAmout})`);
        that.val('');
    } else {
        targetErrorTag.addClass('d-none');
        sumOfDeductions();
    }
})

function fixedTo8($number) {
    return +(Number($number).toFixed(8));
}

// Change Available Amout
function changeAvailableAmout() {
    if (editMode)
        availableAmout = fixedTo8((totalAmount) - (totalPayments + sumOfDeductionValues)) + (oldValuesInEditMode); // available = totalAmount - (allPayment + currentValues) + oldValues
    else
        availableAmout = fixedTo8((totalAmount) - (totalPayments + sumOfDeductionValues)); // available = totalAmount - (allPayment + currentValues)
}
