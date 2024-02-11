
var currentTab = 0; // Current tab is set to be the first tab (0)
function showTab(n, nextBtn, submitBtn) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = submitBtn;
  } else {
    document.getElementById("nextBtn").innerHTML = nextBtn;
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n, nextBtn, submitBtn) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");

  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;

  // Hide the current tab:
  if ((currentTab != x.length - 1) || (currentTab == x.length - 1 && n == -1)) // hide tab in not the last tap
    x[currentTab].style.display = "none";

  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;

  // submit form if you have reached the end of the form
  if (currentTab == x.length) {
    // ... the form gets submitted:
    $('#nextBtn').css("pointer-events", "none");
    document.getElementById("regForm").submit();
    return false;
  }

  // Otherwise, display the correct tab:
  showTab(currentTab, nextBtn, submitBtn);
}

// person phone
$(".phone-require").on('keyup', function () {
  var phone1_id = $('#phone_id').val();

  var tel = $(this).val();
  if (phone1_id == +20 && tel != '') {

    var regex = /^[1]{1}[1]{1}[0-9]{8}$/;
    var regex1 = /^[1]{1}[2]{1}[0-9]{8}$/;
    var regex2 = /^[1]{1}[5]{1}[0-9]{8}$/;
    var regex3 = /^[1]{1}[0]{1}[0-9]{8}$/;

    if (regex.test(tel) || regex1.test(tel) || regex2.test(tel) || regex3.test(tel)) {
      $(this).parents().children('.tab .form-phone-invalid').css("display", "none");
    } else {
      $(this).parents().children('.tab .form-phone-invalid').css("display", "block");
    }
  } else {
    $(this).parents().children('.tab .form-phone-invalid').css("display", "none");
  }
});

// whats
$(".whats-require").on('keyup', function () {
  var phone_id = $('#phone_id').val();
  var tel = $(this).val();
  if (phone_id == +20 && tel != '') {

    var regex = /^[1]{1}[1]{1}[0-9]{8}$/;
    var regex1 = /^[1]{1}[2]{1}[0-9]{8}$/;
    var regex2 = /^[1]{1}[5]{1}[0-9]{8}$/;
    var regex3 = /^[1]{1}[0]{1}[0-9]{8}$/;


    if (regex.test(tel) || regex1.test(tel) || regex2.test(tel) || regex3.test(tel)) {
      $(this).parents().children('.tab .form-phone-invalid').css("display", "none");
    } else {
      $(this).parents().children('.tab .form-phone-invalid').css("display", "block");
    }
  } else {
    $(this).parents().children('.tab .form-phone-invalid').css("display", "none");
  }

});


// company phone
$("#company_mobile").on('keyup', function () {
  var phone_id = $('#phone_id').val();
  var tel = $(this).val();
  if (phone_id == +20 && tel != '') {
    var regex = /^[1]{1}[1]{1}[0-9]{8}$/;
    var regex1 = /^[1]{1}[2]{1}[0-9]{8}$/;
    var regex2 = /^[1]{1}[5]{1}[0-9]{8}$/;
    var regex3 = /^[1]{1}[0]{1}[0-9]{8}$/;
    var regex4 = '';

    if (regex.test(tel) || regex1.test(tel) || regex2.test(tel) || regex3.test(tel)) {
      $('.phone_vaild').css("display", "none");

    } else {
      $('.phone_vaild').css("display", "block");
    }
  } else {
    $('.phone_vaild').css("display", "none");
  }

});

function validatePaymentMethod() {
  if ($('#cash_check_id').prop("checked") == true ||
    $('#cheque_check_id').prop("checked") == true ||
    $('#bank_transfer_check_id').prop("checked") == true) {
    $('#payment_option_error').addClass('d-none');
    return true;
  } else { // If no option is checked
    $('#payment_option_error').removeClass('d-none');
    return false;
  }
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByClassName("require");
  let requireMultipleSelect = $(x[currentTab]).find('.required-multiple-select');

  // Handle Supplier financial entity data
  if (currentTab >= 4) {
    // If minimum one option is checked
    if (!validatePaymentMethod())
      return false;
  }

  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if ($(y[i]).attr('type') != 'file') // as no trim on file input value for that except file inputs
      y[i].value = y[i].value.trim();

    if (y[i].value == "") { // input is empty
      // add an "invalid" class to the field:
      $(y[i]).addClass("invalid is-invalid");
      // and set the current valid status to false
      valid = false;
    }
    else if ($(y[i]).hasClass('validate-email')) { // if input is email
      if (!validateEmail($(y[i])))
        valid = false;
    }
    else if ($(y[i]).hasClass('validate-url')) { // if input is URL
      if (!validateURL($(y[i])))
        valid = false;
    }
    else if ($(y[i]).hasClass('validate-mobile')) { // if input is mobile
      if (!validateMobile($(y[i])))
        valid = false;
    }
    else {
      $(y[i]).removeClass("invalid is-invalid");
    }
  }

  // A loop that checks every multible select field in the current tab which must at least select one item
  for (let i = 0; i < requireMultipleSelect.length; i++) {
    if ($(requireMultipleSelect[i]).find('option:selected').length == 0) {
      $(requireMultipleSelect).addClass("invalid is-invalid");
      valid = false;
    }
  }

  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    $('.step').eq(currentTab).addClass("finish").html("<i class='fa fa-check'> </i>");
    if ($(".checkout  select.invalid")[0]) {
      $('.tab .form-service-invalid').css("display", "none")
    }
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}

// validate email
$('.validate-email').on('change', function () {
  validateEmail($(this));
});

// validate url
$('.validate-url').on('change', function () {
  validateURL($(this));
});

// validate email
$('.validate-mobile').on('change', function () {
  validateMobile($(this));
});

// validate Tax id number and value add registeration number
$('.validate-Tax-id-number-and-value-add-registeration-number').on('focusout', function (e) {
  validateTax_id_numberAndValue_add_registeration_number($(this))
});

// validate commercial registeration number
$('.validate_commercial_registeration_number').on('focusout', function (e) {
  validate_commercial_registeration_number($(this));
});
function validate(object, regex, className) {
  const element = object.val().trim();
  let havRequire = false; // detect if the input is reuired firstly
  if (object.hasClass('require'))
    havRequire = true;
  if (element != '') { // element has value
    object.addClass('require');
    if (regex.test(element)) { // element match
      object.parent().parent().find(`.validate-${className}-error`).addClass('d-none');
      object.removeClass("invalid is-invalid require");
      return true;
    }
    else {
      object.parent().parent().find(`.validate-${className}-error`).removeClass('d-none');
      object.addClass("invalid is-invalid");
      return false;
    }
  }
  else { // is empty
    if (!havRequire) // if the input in the first is not required
      object.removeClass('require');
    object.parent().parent().find(`.validate-${className}-error`).addClass('d-none');
    object.removeClass("invalid is-invalid");
    return true;
  }
}

function validateEmail(that) {
  return validate(that, /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/, 'email');
}

function validateURL(that) {
  return validate(that, /[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/i, 'url');
}

function validateMobile(that) {
  return validate(that, /^[0-9]{4,16}$/, 'mobile');
}

function validateTax_id_numberAndValue_add_registeration_number(that) {
  return validate(that, /^[\d]{3}-[\d]{3}-[\d]{3}$/, 'Tax-id-number-and-value-add-registeration-number');
}

function validate_commercial_registeration_number(that) {
  return validate(that, /^[\d]{4,7}$/, 'commercial-registeration-number');
}

// remove invalid is-invalid for multiple select on change
$('.required-multiple-select').on('change', function () {
  if ($(this).find('option:selected').length == 0)
    $(this).removeClass("invalid is-invalid");
})

// remove invalid is-invalid for multiple select on change
$('.required-multiple-select').on('change', function () {
  if ($(this).find('option:selected').length >= 1)
    $(this).removeClass("invalid is-invalid");
})

// remove invalid is-invalid for require on change
$('.require').on('change', function () {
  if ($(this).val() != '')
    $(this).removeClass("invalid is-invalid");
})
