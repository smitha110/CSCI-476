<?php
/** Link Google Calendar
Plugin Name: Link Google Calendar
Description: A plugin that allows administrator to set Google Calendar embedded link in admin backend and use shortcode 
to place on a page, post or sidebar. 
Version: 1.2.0 
Author: Darren Ladner 
Author URI: http://www.hyperdrivedesigns.com 
Requires at least: 3.0
Text Domain: link-google-calendar
Domain Path: /languages
*/ 

class LinkGoogleCalendar {

	public function __construct() {
		if (is_admin()) {
			add_action('admin_menu', array($this,'link_google_calendar_menu'));
			add_action('admin_init', array($this,'link_google_calendar_register_settings')); 
		}
		else 
		{
			add_shortcode('lgc', array($this,'link_google_calendar_section'));
		}
	}

	public function link_google_calendar_section() {
			$output = '';      
			$output .= '<div align="center">';
		    $output .= get_option('link_google_calendar_textarea'); 
		    $output .= '</div>';
		    return $output;
	}

	public function link_google_calendar_menu() {
		add_menu_page('Link Google Calendar Options', 'Link Google Calendar Options', 'manage_options', 'link_google_calendar_options.php', array($this, 'link_google_calendar_page'));
	}

	public function link_google_calendar_register_settings() {
	register_setting('link-google-calendar-settings-group','link_google_calendar_textarea');

	}

	public function link_google_calendar_page() {
		wp_nonce_field('link_google_calendar_options_nonce', 'link_google_calendar_nonce_field');
	  	?>
		<div class="gcl-admin-section">
			<div class="gcl-logo-section" style="background: #0074a2;color: #fff;border: 2px solid #fff;padding: 2em 0">
				<h1 style="color:#fff;padding-left: 10px;">Link Google Calendar Settings</h1>
			</div>
			<div class="gcl-admin-body-section">
				<form id="optionsForm" method="post" action="options.php">
	    		<?php settings_fields('link-google-calendar-settings-group'); 		
				?>
	    		<table class="form-table">
	    			<tr valign="top">
	    				<th scope="row"><span class="boldText">Link Google Calendar Link</span></th>
	    			</tr>	
				    <tr valign="top"> 
				       <td>
				        <textarea type="text" id="" rows="10" cols="80" name="link_google_calendar_textarea">
						<?php echo esc_html(get_option("link_google_calendar_textarea")); ?>
				        </textarea>
				       </td>
				      
				    </tr>
				    <tr valign="top">
				     	<td>
				       		<p>Options: Copy your Google Calendar embedded link from your Google Calendars account and paste into the textarea. 
				       			Then use the following shortcode <strong>[lgc]</strong> to place the calendar in a page or post.
				       		</p>
				        </td>
				    </tr>
				    <tr valign="top">
				       <td>        
				        <p class="submit">
				         <input type="submit" class="button-primary" name="Submit" value="<?php _e('Save Changes'); ?>" />
				        </p>
				       </td>
				    </tr>     
	    		</table>
	   			</form>
			</div>
		</div>
	 <?php
	}
}

$LinkGoogleCalendar = new LinkGoogleCalendar;