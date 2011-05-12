<?php
/*
Plugin Name: Simplr User Registration Form
Version: 0.1.7
Description: This a simple plugin for adding a custom user registration form to any post or page using shortcode.
Author: Mike Van Winkle
Author URI: http://www.mikevanwinkle.com
Plugin URI: http://www.mikevanwinkle.com/wordpress/how-to/custom-wordpress-registration-page/
License: GPL
*/
?>
<?php
define('WP_DEBUG',true);

//validate
global $wp_version;
$exit_msg = "Dude, upgrade your stinkin Wordpress Installation.";
if(version_compare($wp_version, "2.8", "<")) { exit($exit_msg); }

//constants
define("SIMPLR_DIR", WP_PLUGIN_URL . '/simplr-registration-form/' );

//API
add_action('wp_print_styles','simplr_reg_styles');
add_action('admin_init','simplr_admin_style');
add_action('admin_menu','simplr_reg_menu');

add_shortcode('register', 'sreg_figure');
add_shortcode('Register', 'sreg_figure');
add_action('admin_init','action_admin_init');
add_action('admin_init','simplr_reg_scripts');

//functions

function simplr_reg_set() {
$profile_fields = get_option('simplr_profile_fields');
?>
	<div class="wrap">
		  <h2>Registration Form Settings</h2>
			  <form method="post" action="options.php" id="simplr-settings">
				  <table class="form-table">
				  <tr valign="top">
				  <th scope="row">Set Default FROM Email:</th>
				  <td><input type="text" name="sreg_admin_email" value="<?php echo get_option('sreg_admin_email'); ?>" class="field text wide"/></td>
				  </tr>
				  <tr valign="top">
				  <th scope="row">Set Defult Confirmation Message:<br>
				  <small></small>
				  </th>
				  <td>
				  <textarea id="sreg_email" name="sreg_email" style="width:500px;height:200px; padding:3px;" class="sreg_email"><?php echo get_option('sreg_email'); ?></textarea></td>
				  </tr>
				  <tr valign="top">
				  <th scope="row">Stylesheet Override</th>
				  <td>
				  <input type="text" name="sreg_style" value="<?php echo get_option('sreg_style'); ?>" class="field text wide" />
				  <p><small>Enter the URL of the stylesheet you would prefer to use. Leave blank to stick with default.</small></p>
				  </td>
				  </tr>
				   <tr valign="top">
				  <th scope="row">Profile Fields<br />
				    <small>Here you can setup default fields to include in your registration form. These can be overwritten on a form by form basis. </small>
				  </th>
						<td>
						<div class="left"><label for="aim">AIM</label></div>
						<div class="right">
						<input type="checkbox" name="simplr_profile_fields[aim][name]" value="aim" class="field checkbox" <?php $aim = $profile_fields[aim]; if($aim[name] == true) { echo "checked";} ?>>  
						Label: <input type="text" name="simplr_profile_fields[aim][label]" value="<?php echo $aim[label]; ?>" /><br/></div>
						<div class="left"><label for="aim">Yahoo ID</label></div>
						<div class="right">
						<input type="checkbox" name="simplr_profile_fields[yim][name]" value="yim" class="field checkbox" <?php $yim = $profile_fields[yim]; if($yim[name] == true) { echo "checked";} ?>>  
						Label: <input type="text" name="simplr_profile_fields[yim][label]" value="<?php echo $yim[label]; ?>" /><br/></div>
						<div class="left"><label for="aim">Website</label></div>
						<div class="right">
						<input type="checkbox" name="simplr_profile_fields[url][name]" value="url" class="field checkbox" <?php $url = $profile_fields[url]; if($url[name] == true) { echo "checked";} ?>>  
						Label: <input type="text" name="simplr_profile_fields[url][label]" value="<?php echo $url[label]; ?>" /><br/></div>
						<div class="left"><label for="aim">Nickname</label></div>
						<div class="right">
						<input type="checkbox" name="simplr_profile_fields[nickname][name]" value="nickname" class="field checkbox" <?php $nickname = $profile_fields[nickname]; if($nickname[name] == true) { echo "checked";} ?>>  
						Label: <input type="text" name="simplr_profile_fields[nickname][label]" value="<?php echo $nickname[label]; ?>" /><br/></div>
						</td>
				  </tr>
				  </table>
			  <?php settings_fields('simplr_reg_options'); ?>
			  <p class="submit">
			  <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			  </p>
			  </form>
		<div id="instructions">
			<h2>How to use</h2>
			<p>The goal of this plugin is to give developers and advanced Wordpress users a simple way to create role-specific registration forms for their wordpress website. For instance, you might be running an education based site in which you wanted both teachers and students to particape. This plugin enables you to create distinct registration forms for each type of registrant.</p>
			<p>Because the focus is on seperating registrants, I have not focused on creating a highly customizable form like <a href="http://wordpress.org/extend/plugins/register-plus/" title="Register Plus">Register Plus</a>. </p>
			<h3>Usage</h3>
			<p>To use this plugin simplr employ the shortcode <code>[Register]</code> on any Wordpress post or page. The default role is "subscriber". To apply another role to the registration simply use the the role parameter, for instance: <code>[Regsiter role="editor"]</code>. If you have created custom roles you may use them as well. </p>
			<p>You can also use shortcode so specify a custom confirmation message for each form: <br>
			<code>[Register role="teacher" <b>message="Thank you for registering for my site. If you would like to encourage your students to register, please direct them to http://www.domain.com/students"</b>]</code></p>
			<p>Finally, you can specify emails to be notified when a new user is registered. By defualt site admins will receive notice but to notify other simply use the notify parameter:
			<code>[Register role="teacher" message="Thank you for registering for my site. If you would like to encourage your students to register, please direct them to http://www.domain.com/students" <b>notify="email1@email.com,email2@email.com"</b>]</code>
		<p>
		</div>
	  </div>
  <?php
}//End Function

function simplr_reg_menu() {
	$page = add_submenu_page('options-general.php','Registration Forms', 'Registration Forms','manage_options','simplr_reg_set', 'simplr_reg_set');
	add_action('admin_print_styles-' . $page, 'simplr_admin_style');
	register_setting ('simplr_reg_options', 'sreg_admin_email', '');
	register_setting ('simplr_reg_options', 'sreg_email', '');
	register_setting ('simplr_reg_options', 'sreg_style', '');
	register_setting ('simplr_reg_options', 'simplr_profile_fields', 'simplr_fields_settings_process');
}

function simplr_fields_settings_process($input) {
	if($input[aim][name] && $input[aim][label] == '') {$input[aim][label] = 'AIM';}
	if($input[yim][name] && $input[yim][label] == '') {$input[yim][label] = 'YIM';}
	if($input[website][name] && $input[website][label] == '') {$input[website][label] = 'Website';}	
	if($input[nickname][name] && $input[nickname][label] == '') {$input[nickname][label] = 'Nickname';}
	return $input;
}

function simplr_reg_styles() {
	global $options;
	$style = get_option('sreg_style');
	if(!$style) {
		$src = SIMPLR_DIR .'simplr_reg.css';
		wp_register_style('simplr-forms-style',$src);
		wp_enqueue_style('simplr-forms-style');
	} else {
		$src = $style;
		wp_register_style('simplr-forms-custom-style',$src);
		wp_enqueue_style('simplr-forms-custom-style');
	}
//End Function
}

function simplr_admin_style() {
	$src = SIMPLR_DIR . 'simplr_admin.css';
	wp_register_style('simplr-admin-style',$src); 
	wp_enqueue_style('simplr-admin-style');
}

//Register Menu Item for Admin Page
function simplr_reg_admin_page() {
	add_submenu_page('options-general.php','Registration Page Settings', 'Registration Page','manage_options','simplr_reg_page', 'simplr_reg_admin');
	}	


function simplr_validate($data) {
	require_once(ABSPATH . WPINC . '/registration.php' );
	require_once(ABSPATH . WPINC . '/pluggable.php' );
	$errors = array();
	
	// Make sure passwords match
	if($data['password'] != $data['password_confirm']) {
		$errors[] = __('The passwords you entered do not match.', 'simplr-reg');
	}
	
	// Validate username
	if(!$data['username']) { 
		$errors[] = __("You must enter a username.",'simplr-reg'); 
		} else {
			// check whether username is valid
			$user_test = validate_username($data['username']);
				if($user_test != true) {
						$errors[] .= __('Invalid Username.','simplr-reg');
					}
			// check whether username already exists
			$user_id = username_exists( $data['username'] );
				if($user_id) {
						$errors[] .= __('This username already exists.','simplr-reg');
					}
		} //end username validation
		
		
	// Validate email
	if(!$data['email']) { 
		$errors[] = __("You must enter an email.",'simplr-reg'); 
	} elseif($data['email'] !== $data['email_confirm']) {
		$errors[] = __("The emails you entered do not match.",'simplr-reg'); 
	} else {
		$email_test = email_exists($data['email']);
		if($email_test != false) {
				$errors[] .= __('An account with this email has already been registered.','simplr-reg');
			}
		if( !is_email($data['email']) ) {
				$errors[] .= __("Please enter a valid email.",'simplr-reg');
			}	
		} // end email validation
		
	
	//use this filter to apply custom validation rules.
	$errors = apply_filters('simplr_validate_form', $errors); 
	return $errors;
}

function sreg_process_form($atts) {
	//security check
	if (!wp_verify_nonce($_POST['simplr_nonce'], 'simplr_nonce') ) { die('Security check'); } 
	$errors = simplr_validate($_POST);
	if( $errors ==  true ) :
		 $message = $errors;
	endif; 
	if (!$message) {			
		$output = simplr_setup_user($atts,$_POST);
		return $output;
	} else { 		
		//Print the appropriate message
		if(is_array($message)) {
			$out = '';
			foreach($message as $mes) {
				$out .= '<div class="simplr-message error">'.$mes .'</div>';
			}
		} else {
			$out = '<div class="simplr-message error">'.$message .'</div>';
		}
		//rebuild the form
		$form = simplr_build_form($_POST,$atts);
		$output = $out . $form;
		//return shortcode output
		return $output;
	}
//END FUNCTION
}


function simplr_setup_user($atts,$data) {
	//check options
	global $options;
	$admin_email = $atts['from'];
	$emessage = $atts['message'];
	$role = $atts['role']; 
		if('' == $role) { $role = 'subscriber'; }
		if('administrator' == $role) { wp_die('Do not use this form to register administrators'); }
	require_once(ABSPATH . WPINC . '/registration.php' );
	require_once(ABSPATH . WPINC . '/pluggable.php' );
	
	//Assign POST variables
	$user_name = $data['username'];
	$fname = $data['fname'];
	$lname = $data['lname'];
	$user_name = sanitize_user($user_name, true);
	$email = $data['email'];
	$user_url = $data['url'];
	
	
	//This part actually generates the account
	if(isset($data['password'])) {
		$passw = $data['password'];
	} else {
		$passw = wp_generate_password( 12, false );
	}
	
	$userdata = array(
	'user_login' => $user_name,
	'first_name' => $fname,
	'last_name' => $lname,
	'user_pass' => $passw,
	'user_email' => $email,
	'user_url' => $user_url,
	'role' => $role
	);
	
	// create user	
	$user_id = wp_insert_user( $userdata );
	
	//multisite support add user to registration log and associate with current site
		if(WP_MULTISITE === true) { 
			global $wpdb;
			$ip = getenv('REMOTE_ADDR');
			$site = get_current_site();
			$sid = $site->id;
			$query = $wpdb->prepare("
				INSERT INTO $wpdb->registration_log
				(ID, email, IP, blog_ID, date_registered)
				VALUES ($user_id, $email, $ip, $sid, NOW() )
				");
			$results = $wpdb->query($query);
		}
	
	//Process additional fields 
	$pro_fields = get_option('simplr_profile_fields');
		if($pro_fields) {
				foreach($pro_fields as $field) {
				$key = $field['name'];
				$val = $data[$key];
				if(isset( $val )) { add_user_meta($user_id,$key,$val); }
			}
		}
	
	//Save Hook for custom profile fields
	do_action('simplr_profile_save_meta', $user_id);
	
	// if password was auto generated, add flag for the user to change their auto-generated password
	if(!$data['password']) {
		$update = update_user_option($user_id, 'default_password_nag', true, true);
	}
	
	//notify admin of new user
	simplr_send_notifications($atts,$data, $passw);

	$extra = "Please check your email for confirmation.";
	$extra = apply_filters('simplr_extra_message', __($extra,'simplr-reg') );
	$confirm = '<div class="simplr-message success">Your Registration was successful. '.$extra .'</div>';
	
	//Use this hook for multistage registrations
	do_action('simplr_reg_next_action', array($data, $user_id, $confirm));
	
	//return confirmation message. 
	return apply_filters('simplr_reg_confirmation', $confirm);
}

function simplr_send_notifications($atts, $data, $passw) {
	$site = get_option('siteurl');
	$name = get_option('blogname');
	$user_name = $data['username'];
	$email = $data['email'];
	$notify = $atts['notify'];
	$emessage = apply_filters('simplr_email_confirmation_message', __("Your registration was successful.".$atts['message']), 'simplr-reg');
	$headers = "From: $name" . ' <' .get_option('admin_email') .'> ' ."\r\n\\";
	wp_mail($notify, "A new user registered for $name", "A new user has registered for $name.\rUsername: $user_name\r Email: $email \r",$headers);
	$emessage = $emessage . "\r\r---\r";
		if(!isset($data['password'])) {
		$emessage .= "You should login and change your password as soon as possible.\r\r";
		}
	$emessage .= "Username: $user_name\rPassword: $passw\rLogin: $site/wp-login.php";
	wp_mail($data['email'],"$name - Registration Confirmation", $emessage, $headers);
	
}

function simplr_build_form($data,$atts) {
			
	$label_first = apply_filters('simplr_label_fname', __('First Name:', 'simplr-reg') );
	$label_last = apply_filters('simplr_label_lname', __('Last Name:','simplr-reg') );
	$label_email = apply_filters('simplr_label_email', __('Email Address:','simplr-reg') );
	$label_confirm_email = apply_filters('simplr_label_confirm_email', __('Confirm Email:','simplr-reg') );
	$label_username = apply_filters('simplr_label_username', __('Your Username:','simplr-reg') );
	$label_pass = apply_filters('simplr_label_password', __('Choose a Password','simpr-reg'));
	$label_confirm = apply_filters('simplr_label_confirm', __('Confirm Password','simpr-reg'));
	
	//POST FORM
	$form = '';
	$form .= apply_filters('simplr-reg-instructions', __('Please fill out this form to sign up for this site', 'simplr-reg'));
	$form .=  '<div id="simplr-form">';
	$form .=  apply_filters('simplr-personal-header',__('<h3 class="registration personal">Personal Info</h3>','simplr-reg'));
	$form .=  '<form method="post" action="" id="simplr-reg">';
	$form .=  '<div class="simplr-field">';
	$form .=  '<label for="username" class="left">' .$label_username .' <span class="required">*</span></label>';
	$form .=  '<input type="text" name="username" class="right" value="'.$data['username'] .'" /><br/>';
	$form .=  '</div>';
	$form .=  '<div class="simplr-field">';
	$form .=  '<label for="fname" class="left">'.$label_first .'</label>';
	$form .=  '<input type="text" name="fname" class="right" value="'.$data['fname'] .'" /><br/>';
	$form .=  '</div>';
	$form .=  '<div class="simplr-field">';
	$form .=  '<label for="lname" class="left">' .$label_last .'</label>';
	$form .=  '<input type="text" name="lname" class="right" value="'.$data['lname'] .'"/><br/>';
	$form .=  '</div>';
	
	$form = apply_filters('simplr-add-personal-fields', $form);
	
	$form .=  apply_filters('simplr-reg-email-header',__('<h3 class="registration email">Contact Info</h3>','simplr-reg'));
	$form .=  '<div class="simplr-field email-field">';
	$form .=  '<label for="email" class="left">' .$label_email .' <span class="required">*</span></label>';
	$form .=  '<input type="text" name="email" class="right" value="'.$data['email'] .'" /><br/>';
	$form .=  '</div>';
	$form .=  '<div class="simplr-field email-field">';
	$form .=  '<label for="email" class="left">' .$label_confirm_email .' <span class="required">*</span></label>';
	$form .=  '<input type="text" name="email_confirm" class="right" value="'.$data['email_confirm'] .'" /><br/>';
	$form .=  '</div>';
	
	$form = apply_filters('simplr-add-contact-fields', $form);
	
	//optional profile fields
	$pro_fields = get_option('simplr_profile_fields');
	if($pro_fields) {
		foreach($pro_fields as $field) {
				if($field[name] != '') {
			$form .= '<div class="simplr-field"><label for="' .$field[name] .'" class="left">'.$field[label] .'</label><input type="text" name="'.$field[name] .'" value="'.$data[$field[name]] .'" class="text" /></div>';
			}
		}
	}
	
	if('yes' == $atts['password']) {
	
		$form .=  apply_filters('simplr-reg-password-header',__('<h3 class="registration password">Choose a password</h3>','simplr-reg'));			
		$form .=  '<div class="simplr-field">';
		$form .=  '<label for="password" class="left">' .$label_pass .'</label>';
		$form .=  '<input type="password" name="password" class="right" value="'.$data['password'] .'"/><br/>';
		$form .=  '</div>';
		
		$form .=  '<div class="simplr-field">';
		$form .=  '<label for="password-confirm" class="left">' .$label_confirm .'</label>';
		$form .=  '<input type="password" name="password_confirm" class="right" value="'.$data['password_confirm'] .'"/><br/>';
		$form .=  '</div>';
	}

	//filter for adding profile fields
	$form = apply_filters('simplr_add_form_fields', $form);
		 
	//submission field
	$form .=  apply_filters('simplr-reg-submit', '<input type="submit" name="submit-reg" value="Register" class="submit button">');
	
	//wordress nonce for security
	$nonce = wp_create_nonce('simplr_nonce');
	$form .= '<input type="hidden" name="simplr_nonce" value="' .$nonce .'" />';
	$form .=  '</form>';
	$form .=  '</div>';
	return $form;
}

function sreg_basic($atts) {
	//Check if the user is logged in, if so he doesn't need the registration page
		if ( is_user_logged_in() ) {
			echo "You are already registered for this site!!!";
		} else {
		//Then check to see whether a form has been submitted, if so, I deal with it.
		if(isset($_POST['submit-reg'])) {
			$output = sreg_process_form($atts);	
			return $output;
		} else {
			$data = array();
			$form = simplr_build_form($data, $atts);		
		return $form;				
	} //Close POST Condiditonal
} //Close LOGIN Conditional

} //END FUNCTION


//this function determines which version of the registration to call
function sreg_figure($atts) {
	global $options;
	extract(shortcode_atts(array(
	'role' => 'subscriber',
	'from' => get_option('sreg_admin_email'),
	'message' => 'Thank you for registering',
	'notify' => get_option('sreg_email'),
	'fb' => false,
	), $atts));
		if($role != 'admin') {
			$function = sreg_basic($atts);
		} else { 
			$function = 'You should not register admin users via a public form';
		}
	return $function;
}//End Function


function action_admin_init() {
	// only hook up these filters if we're in the admin panel, and the current user has permission
	// to edit posts and pages
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		add_filter( 'mce_buttons', 'filter_mce_button' );
		add_filter( 'mce_external_plugins', 'filter_mce_plugin' );
	}
}

function filter_mce_button( $buttons ) {
	array_push( $buttons, '|', 'simplr_reg_button' );
	return $buttons;
}

function filter_mce_plugin( $plugins ) {
	// this plugin file will work the magic of our button
	$plugins['simplr_reg'] = SIMPLR_DIR . 'simplr_reg.js';
	return $plugins;
}

function simplr_reg_scripts() {
	wp_enqueue_script('simplr_reg_options', SIMPLR_DIR . 'simplr_reg_options.js', array('jquery'));
	wp_localize_script( 'simplr_reg_options', 'simplr', array( 'plugin_dir' => SIMPLR_DIR ) );
}

?>