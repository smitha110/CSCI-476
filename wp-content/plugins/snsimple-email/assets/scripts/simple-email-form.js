jQuery(document).ready(function($) {
  $('#sef-form button').click(function(e) {
    e.preventDefault();

    // get form data
    data = {};
    data['name'] = $('#sef-form input[name=sef-name]').val().trim();
    data['email'] = $('#sef-form input[name=sef-email]').val().trim();
    data['message'] = $('#sef-form textarea[name=sef-message]').val().trim();
    data['recaptcha'] = (typeof(grecaptcha) !== 'undefined' ? grecaptcha.getResponse() : null);
    data['action'] = 'simple-email-form-submission';

    // form validation
    form_valid = true;
    $.each(['name', 'email', 'message'], function(key, val) {
      if (data[val] === '') {
        form_valid = false;
        $('#sef-form [name=sef-'+val+']').parent().addClass('has-error');
      } else {
        $('#sef-form [name=sef-'+val+']').parent().removeClass('has-error');
      }
    });
    if (form_valid === false) {
      $('#sef-message')
        .removeClass('success')
        .addClass('error')
        .html(simple_email_form_obj.required_fields)
        .show();

      return;
    }
    // check recaptcha
    if (data['recaptcha'] === '') {
      $('#sef-message')
        .removeClass('success')
        .addClass('error')
        .html(simple_email_form_obj.robot)
        .show();

      return;
    }

    // hide errors
    $('#sef-message').hide();
    // disable submit
    $('#sef-form button').attr('disabled', 'disabled');

    // submit form
    $.ajax({
      url: simple_email_form_obj.ajax_url,
      method: 'POST',
      dataType: 'json',
      data: data,
      success: function(resp) {
        if (resp.success) {
          $('#sef-message')
            .removeClass('error')
            .addClass('success')
            .html(simple_email_form_obj.success)
            .show();

          // reset form
          $('#sef-form').trigger('reset');
          if (typeof(grecaptcha) !== 'undefined') {
            grecaptcha.reset();
          }
        } else {
          $('#sef-message')
            .removeClass('success')
            .addClass('error')
            .html(resp.data.message)
            .show();
        }

        $('#sef-form button').removeAttr('disabled');
      },
      error: function(resp) {
        // prompt user
        $('#sef-message')
          .removeClass('success')
          .addClass('error')
          .html(simple_email_form_obj.error)
          .show();

        $('#sef-form button').removeAttr('disabled');
      }
    });
  });
  $('#sef-form input[name=sef-name], #sef-form input[name=sef-email], #sef-form textarea[name=sef-message]').change(function(ele) {
    if ($(this).val().trim() === '' ) {
      $(this).parent().addClass('has-error');
    } else {
      $(this).parent().removeClass('has-error');
    }
  });
});
