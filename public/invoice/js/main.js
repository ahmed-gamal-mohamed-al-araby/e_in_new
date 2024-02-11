(function($) {

    var form = $("#documentForm");
    var modal = $("#addItemsForm");
    var currencyModel = $("#addCurrency");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: {
            from_company_name: {
                required: true,
            },
            to_company_name: {
                required: true,
            },
            category_id: {
                required: true
            }
        },
        messages: {
            email: {
                email: 'Not a valid email address <i class="zmdi zmdi-info"></i>'
            },
            from_company_name: {
                required: 'PLease enter register number to show company name'
            },
            to_company_name: {
                required: 'PLease enter register number to show client name'
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
            // "tax_rate[]":{
            //     required:true
            // }
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
            currencyRate:{
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
        onStepChanging: function(event, currentIndex, newIndex) {
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function(event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function(event, currentIndex) {
            $("select").prop("disabled", false);
            submitInvoice();

            // $("#invoiceForm")[0].submit();
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


    $.dobPicker({
        daySelector: '#expiry_date',
        monthSelector: '#expiry_month',
        yearSelector: '#expiry_year',
        dayDefault: 'DD',
        yearDefault: 'YYYY',
        minimumAge: 0,
        maximumAge: 120
    });

    // $('#password').pwstrength();

    $('#button').click(function () {
        $("input[type='file']").trigger('click');
    })

    $("input[type='file']").change(function () {
        $('#val').text(this.value.replace(/C:\\fakepath\\/i, ''))
    })

})(jQuery);
