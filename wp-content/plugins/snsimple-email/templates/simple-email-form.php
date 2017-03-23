<form id="sef-form">
  <?php if ( (int)simple_email_form::get_option( 'display-email' ) === 1 ) : ?>
    <p><?php printf( __( 'Alternatively you can contact me at %s.', 'snsimple-email' ), antispambot( simple_email_form::get_option( 'email' ) ) ) ?></p>
  <?php endif; ?>
  <div id="sef-message"></div>
  <div class="sef-form-group">
    <label for="sef-name"><?php _e( 'Your Name', 'snsimple-email' ) ?></label>
    <input type="text" name="sef-name" value="" required />
  </div>
  <div class="sef-form-group">
    <label for="sef-email"><?php _e( 'Your Email', 'snsimple-email' ) ?></label>
    <input type="email" name="sef-email" value="" required />
  </div>
  <div class="sef-form-group">
    <label for="sef-message"><?php _e( 'Message', 'snsimple-email' ) ?></label>
    <textarea name="sef-message" required></textarea>
  </div>
  <?php if ( (int)simple_email_form::get_option( 'recaptcha' ) === 1 && simple_email_form::get_option( 'recaptcha-site-key' ) ) : ?>
    <div class="sef-form-group">
      <div class="g-recaptcha" data-sitekey="<?php echo simple_email_form::get_option( 'recaptcha-site-key' ) ?>"></div>
    </div>
  <?php endif; ?>
  <button type="submit"><?php _e( 'Submit' , 'snsimple-email' ) ?></button>
</form>
