jQuery(document).ready(function($) {
  sefToggleRecaptchaSecretKey();

  $(document)
    .on('change', '#sef-recaptcha', function() {
      sefToggleRecaptchaSecretKey();
    })
    .on('click', '#sef-toggle-recaptcha-secret-key', function() {
      if ($('#sef-recaptcha-secret-key').attr('type') == 'password') {
        $('#sef-recaptcha-secret-key').attr('type', 'text');
        $('#sef-toggle-recaptcha-secret-key').html(simple_email_form_obj.hide);
      } else {
        $('#sef-recaptcha-secret-key').attr('type', 'password');
        $('#sef-toggle-recaptcha-secret-key').html(simple_email_form_obj.show);
      }
    });

  function sefToggleRecaptchaSecretKey(){
    if ($('#sef-recaptcha').is(':checked')) {
      $('.sef-recaptcha-field').show();
    } else {
      $('.sef-recaptcha-field').hide();
    }
  }
});
