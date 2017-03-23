<?php
/*
Plugin Name: Simple Email Form
Plugin URI: http://seannewby.ca#plugins
Description: Easily add a simple email contact form to your WordPress site.
Version: 2.0.3
Author: Sean Newby
Author URI: http://seannewby.ca
Text Domain: snsimple-email
Domain Path: /languages
License: GPL2

Copyright 2016 Sean Newby (email : sean@seannewby.ca)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'simple_email_form' ) ) {
  class simple_email_form{
    public $plugin_uri;
    public $version;
		public static $options;

    public function __construct() {
      // set class variables
      $this->plugin_uri = WP_PLUGIN_URL . DIRECTORY_SEPARATOR . basename( dirname( __FILE__ ) );
      $this->version = '2.0.3';

      // handle localisation
      add_action( 'plugins_loaded', array( &$this, 'load_plugin_textdomain' ) );
      // hooks (admin)
      add_action( 'admin_init', array( &$this, 'upgrade' ) );
      add_action( 'admin_init', array( &$this, 'register_plugin_settings' ) );
      add_action( 'admin_menu', array( &$this, 'register_settings_page' ) );
      add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
      add_action( 'wp_ajax_simple-email-form-submission', array( &$this, 'form_submission_ajax' ) );
      add_action( 'wp_ajax_nopriv_simple-email-form-submission', array( &$this, 'form_submission_ajax' ) );
      // hooks (front-end)
      add_action( 'wp_enqueue_scripts', array( &$this, 'register_scripts_and_styles' ) );

      // shortcodes/actions
      add_shortcode( 'sn-simple-email' , array( &$this, 'simple_email_form_shortcode_deprecated' ) );
      add_shortcode( 'simple-email-form' , array( &$this, 'simple_email_form_shortcode' ) );
      add_action( 'sn-simple-email', array( &$this, 'simple_email_form_action_deprecated' ) );
      add_action( 'simple-email-form', array( &$this, 'simple_email_form_action' ) );
    }

    // load plugin text domain
    public function load_plugin_textdomain() {
      load_plugin_textdomain( 'snsimple-email', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    // upgrade plugin
    public function upgrade() {
      // get current plugin version
      $version = get_option( 'simple-email-form-version' );
      if ( version_compare( $version, $this->version, '<' ) ) {
        // check for old options
        $old_options = get_option( 'se-options' );
        if ( is_array( $old_options ) ) {
          // set new settings using old settings
          update_option( 'simple-email-form-options', array(
            'email'         => ( array_key_exists( 'your-email', $old_options ) ? $old_options['your-email'] : null ),
            'display-email' => ( array_key_exists( 'display-email', $old_options ) ? (int)$old_options['display-email'] : null ),
            'recaptcha'     => 0
          ) );

          // delete old options
          delete_option( 'se-options' );
        }

        // update plugin version
        update_option( 'simple-email-form-version', $this->version );
      }
    }

    // register plugin settings
    public function register_plugin_settings() {
      register_setting( 'simple-email-form-options-group' , 'simple-email-form-options', array( &$this, 'sanitize_settings' ) );
    }

    // register settings page
    public function register_settings_page() {
      add_options_page( __( 'Simple Email Form', 'snsimple-email' ), __( 'Simple Email Form', 'snsimple-email' ), 'manage_options', 'simple-email-form', array( &$this, 'options_page' ) );
    }

    // options page
    public function options_page() {
      // double check
			if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.' , 'snsimple-email' ) );
      }
?>
<div class="wrap">
  <h1><?php _e( 'Simple Email Form', 'snsimple-email' ) ?></h1>
  <form method="post" action="options.php">
    <?php settings_fields( 'simple-email-form-options-group' ) ?>
    <?php do_settings_sections( 'simple-email-form-options-group' ) ?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row"><?php _e( 'Your Email', 'snsimple-email' ) ?></th>
        <td><input type="email" name="simple-email-form-options[email]" class="regular-text" value="<?php echo self::get_option( 'email' ) ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e( 'Display your Email', 'snsimple-email' ) ?></th>
        <td>
          <label>
            <input type="checkbox" name="simple-email-form-options[display-email]" value="1"<?php if ( (int)self::get_option( 'display-email' ) === 1 ) : ?> checked="checked"<?php endif; ?> />
            <span class="description"><?php _e( 'Your email address with be converted to HTML entities', 'snsimple-email' ) ?></span>
          </label>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e( 'Google reCAPTCHA', 'snsimple-email' ) ?></th>
        <td>
          <label>
            <input type="checkbox" name="simple-email-form-options[recaptcha]" id="sef-recaptcha" value="1"<?php if ( (int)self::get_option( 'recaptcha' ) === 1 ) : ?> checked="checked"<?php endif; ?> />
            <span class="description"><?php printf( __( 'Enable Google reCAPTCHA.  You will need a site key and a secret key which you can get %shere%s.', 'snsimple-email' ), '<a href="https://www.google.com/recaptcha/admin" target="_blank">', '</a>' ) ?></span>
          </label>
        </td>
      </tr>
      <tr valign="top" class="sef-recaptcha-field" style="display: none">
        <th scope="row"><?php _e( 'Site Key', 'snsimple-email' ) ?></th>
        <td><input type="text" name="simple-email-form-options[recaptcha-site-key]" class="regular-text" value="<?php echo self::get_option( 'recaptcha-site-key' ) ?>" /></td>
      </tr>
      <tr valign="top" class="sef-recaptcha-field" style="display: none">
        <th scope="row"><?php _e( 'Secret Key', 'snsimple-email' ) ?></th>
        <td>
          <input type="password" name="simple-email-form-options[recaptcha-secret-key]" class="regular-text" id="sef-recaptcha-secret-key" value="<?php echo self::get_option( 'recaptcha-secret-key' ) ?>" />
          <a id="sef-toggle-recaptcha-secret-key" class="button-secondary"><?php _e( 'Show', 'snsimple-email' ) ?></a>
        </td>
      </tr>
      <tr valign="top" class="sef-recaptcha-field" style="display: none">
        <th scope="row"><?php _e( 'Language Code', 'snsimple-email' ) ?></th>
        <td>
          <input type="text" name="simple-email-form-options[recaptcha-language-code]" class="regular-text" id="sef-recaptcha-language-code" value="<?php echo self::get_option( 'recaptcha-language-code' ) ?>" /><br />
          <span class="description"><?php printf( __( 'Click %shere%s to see available language codes.', 'snsimple-email' ), '<a href="https://developers.google.com/recaptcha/docs/language" target="_blank">', '</a>' ) ?></span>
        </td>
      </tr>
    </table>
    <?php submit_button() ?>
  </form>
</div>
<?php
    }

    // sanitize settings
    public function sanitize_settings( $input ) {
      // sanitize
      $input['email'] = sanitize_email( $input['email'] );
      $input['display-email'] = (int)$input['display-email'];
      $input['recaptcha'] = (int)$input['recaptcha'];
      $input['recaptcha-site-key'] = esc_attr( trim( $input['recaptcha-site-key'] ) );
      $input['recaptcha-secrect-key'] = esc_attr( trim( $input['recaptcha-site-key'] ) );
      $input['recaptcha-language-code'] = esc_attr( trim( $input['recaptcha-language-code'] ) );

      // validation
      if ( ! is_email( $input['email'] ) ) {
        add_settings_error( 'simple-email-form-settings', esc_attr( 'settings_updated' ), __( 'Please enter a valid email address.', 'snsimple-email' ), 'error' );
        $input['email'] = null;
      }
      if ( (int)$input['recaptcha'] === 1 && ( empty( $input['recaptcha-site-key'] ) || empty( $input['recaptcha-secret-key'] ) ) ) {
        add_settings_error( 'simple-email-form-settings', esc_attr( 'settings_updated' ), __( 'Please enter your site and secret key or disable Google ReCAPTCHA.', 'snsimple-email' ), 'error' );
      }

      return $input;
    }

    // admin enqueue scripts
    public function admin_enqueue_scripts( $hook ) {
      if ( $hook == 'settings_page_simple-email-form' ) {
        wp_enqueue_script( 'simple-email-form-admin', $this->plugin_uri . '/assets/scripts/admin.' . ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ? 'min.' : '' ) . 'js', array( 'jquery' ), $this->version, true );
        wp_localize_script( 'simple-email-form-admin', 'simple_email_form_obj', array(
          'hide'  => __( 'Hide', 'snsimple-email' ),
          'show'  => __( 'Show', 'snsimple-email' )
        ) );
      }
    }

    // register scripts and styles
    public function register_scripts_and_styles() {
      wp_register_script( 'simple-email-form', $this->plugin_uri . '/assets/scripts/simple-email-form.' . ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ? 'min.' : '' ) . 'js', array( 'jquery' ), $this->version, true );
      wp_register_style( 'simple-email-form', $this->plugin_uri . '/assets/styles/simple-email-form.' . ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ? 'min.' : '' ) . 'css', array(), $this->version );
      // google recaptcha
      $recaptcha_language_code = self::get_option( 'recaptcha-language-code' );
      wp_register_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js' . ( ! empty( $recaptcha_language_code ) ? '?hl=' . $recaptcha_language_code : '' ) );
    }

    // enqueue scripts and styles
    public function enqueue_scripts_and_styles() {
      // enqueue
      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'simple-email-form' );
      wp_enqueue_style( 'simple-email-form' );
      if ( (int)self::get_option( 'recaptcha') === 1 ) {
        wp_enqueue_script( 'google-recaptcha' );
      }

      // localize
      wp_localize_script( 'simple-email-form', 'simple_email_form_obj', array(
        'required_fields' => __( 'Please fill in all required fields.', 'snsimple-email' ),
        'robot'           => __( 'Please confirm you are not a robot.', 'snsimple-email' ),
        'error'           => __( 'Oops, something went wrong.  Please try again later.', 'snsimple-email' ),
        'success'         => __( 'Thank you, your message was sent.', 'snsimple-email' ),
        'ajax_url'        => admin_url( 'admin-ajax.php' )
      ) );
    }

    // simple email shortcode
    public function simple_email_form_shortcode() {
      // enqueue scripts and styles
      $this->enqueue_scripts_and_styles();

      // return form
      return $this->get_simple_email_form();
    }

    // simple email shortcode (deprecated)
    public function simple_email_form_shortcode_deprecated() {
      // deprecated notice
      _deprecated_argument( __FUNCTION__, '2.0', sprintf(__( '"%s" shortcode is deprecated, please use "%s" instead.', 'snsimple-email' ), 'sn-simple-email', 'simple-email-form' ) );
      return $this->simple_email_form_shortcode();
    }

    // simple email action
    public function simple_email_form_action() {
      // enqueue scripts and styles
      $this->enqueue_scripts_and_styles();
      echo $this->get_simple_email_form();
    }

    // simple email action (deprecated)
    public function simple_email_form_action_deprecated() {
      // deprecated notice
      _deprecated_argument( __FUNCTION__, '2.0', sprintf(__( '"%s" action is deprecated, please use "%s" instead.', 'snsimple-email' ), 'sn-simple-email', 'simple-email-form' ) );
      $this->simple_email_form_action();
    }

    // get simple email form
    public function get_simple_email_form() {
      // check theme for template
      $template = locate_template( 'simple-email-form.php' );
      if ( ! $template ) {
        // use plugin template
        $template = dirname( __FILE__ ) . '/templates/simple-email-form.php';
      }

      // load template
      ob_start();
      load_template( $template );
      return ob_get_clean();
    }

    // form submission (AJAX)
    public function form_submission_ajax() {
      // get form data
      $name = ( isset( $_POST['name'] ) && trim( $_POST['name'] ) ? trim( stripslashes( $_POST['name'] ) ) : false );
      $email = ( isset( $_POST['email'] ) && trim( $_POST['email'] ) ? trim( $_POST['email'] ) : false );
      $message = ( isset( $_POST['message'] ) && trim( $_POST['message'] ) ? trim( stripslashes( $_POST['message'] ) ) : false );
      $recaptcha = ( isset( $_POST['recaptcha'] ) && trim( $_POST['recaptcha'] ) ? trim( $_POST['recaptcha'] ) : false );

      // check form data
      if ( ! $name || ! $email || ! $message ) {
        wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'snsimple-email' ) ) );
      } elseif ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'snsimple-email' ) ) );
      } elseif ( (int)self::get_option( 'recaptcha' ) === 1 && ! $recaptcha ) {
        wp_send_json_error( array( 'message' => __( 'Please confirm you are not a robot.', 'snsimple-email' ) ) );
      }

      // check recaptcha
      if ( (int)self::get_option( 'recaptcha' ) === 1 ) {
        $response = wp_safe_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
          'body' => array(
            'secret'    => self::get_option( 'recaptcha-secret-key' ),
            'response'  => $recaptcha
          )
        ) );
        $response = wp_remote_retrieve_body($response);
        $response = json_decode( $response, true );
        if ( ! is_array( $response ) || ! array_key_exists( 'success', $response ) || ! $response['success'] ) {
          wp_send_json_error( array( 'message' => __( 'Unfortunately we cannot confirm that you are not a robot.', 'snsimple-email' ) ) );
        }
      }

      if ( $this->send_email( $name, $email, $message ) ) {
        wp_send_json_success();
      } else {
        wp_send_json_error( array( 'message' => __( 'Oops, something went wrong.  Please try again later.', 'snsimple-email' ) ) );
      }
    }

    // send email
    public function send_email( $name, $email, $message ) {
      // prepare email contents
      $subject = sprintf( __( 'You have a new message from %s, sent from %s', 'snsimple-email' ), $name, get_bloginfo( 'blogname' ) );
      // encode subject (utf-8)
      $subject = '=?utf-8?B?' . base64_encode( $subject ) . '?=';

      // set message
      $message = sprintf( __( '%s (%s) wrote:', 'snsimple-email' ), $name, $email ) . '<br /><br />' . strip_tags( nl2br( $message ), '<br>' );

      // headers
      $headers = array();
      $headers[] = 'Reply-To: ' . $email;
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html;charset=utf-8';
      $headers[] = 'X-Mailer: PHP/' . phpversion();
      $headers = apply_filters( 'simple-email-form-headers', $headers, $name, $email, $message );

      // send mail
      $status = wp_mail( self::get_option( 'email' ), $subject, $message, $headers );
      return $status;
    }

    // get option
    public static function get_option( $option ) {
      // check that options are loaded
      if ( ! is_array( self::$options ) ) {
        self::$options = (array)get_option( 'simple-email-form-options' );
      }

      return ( array_key_exists( $option, self::$options ) ? esc_attr( self::$options[$option] ) : null );
    }
  }

  // instantiate
  $simple_email_form = new simple_email_form;
}
