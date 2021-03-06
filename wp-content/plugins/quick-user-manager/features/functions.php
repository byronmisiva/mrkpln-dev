<?php
/**
 * Functions Load
 *
 */
 
// whitelist options, you can add more register_settings changing the second parameter
function qum_register_settings() {
	register_setting( 'qum_option_group', 'qum_default_settings' );
	register_setting( 'qum_general_settings', 'qum_general_settings', 'qum_general_settings_sanitize' );
	register_setting( 'qum_display_admin_settings', 'qum_display_admin_settings' );
	register_setting( 'qum_QUICK_USER_MANAGER_pro_serial', 'qum_QUICK_USER_MANAGER_pro_serial' );
	register_setting( 'qum_module_settings', 'qum_module_settings' );
	register_setting( 'qum_module_settings_description', 'qum_module_settings_description' );
	register_setting( 'customRedirectSettings', 'customRedirectSettings' );
	register_setting( 'customUserListingSettings', 'customUserListingSettings' );
	register_setting( 'reCaptchaSettings', 'reCaptchaSettings' );
	register_setting( 'emailCustomizer', 'emailCustomizer' );
}




// WPML support
function qum_icl_t($context, $name, $value){  
	if( function_exists( 'icl_t' ) )
		return icl_t( $context, $name, $value );
		
	else
		return $value;
}


function qum_add_plugin_stylesheet() {
	$qum_generalSettings = get_option( 'qum_general_settings' );

	if ( ( file_exists( QUM_PLUGIN_DIR . '/assets/css/style-front-end.css' ) ) && ( isset( $qum_generalSettings['extraFieldsLayout'] ) && ( $qum_generalSettings['extraFieldsLayout'] == 'default' ) ) ){
		wp_register_style( 'qum_stylesheet', QUM_PLUGIN_URL . 'assets/css/style-front-end.css' );
		wp_enqueue_style( 'qum_stylesheet' );
	}
	if( is_rtl() ) {
		if ( ( file_exists( QUM_PLUGIN_DIR . '/assets/css/rtl.css' ) ) && ( isset( $qum_generalSettings['extraFieldsLayout'] ) && ( $qum_generalSettings['extraFieldsLayout'] == 'default' ) ) ){
			wp_register_style( 'qum_stylesheet_rtl', QUM_PLUGIN_URL . 'assets/css/rtl.css' );
			wp_enqueue_style( 'qum_stylesheet_rtl' );
		}
	}
}


function qum_show_admin_bar($content){
	global $current_user;

	$adminSettingsPresent = get_option('qum_display_admin_settings','not_found');
	$show = null;

	if ($adminSettingsPresent != 'not_found' && $current_user->ID)
		foreach ($current_user->roles as $role_key) {
			if (empty($GLOBALS['wp_roles']->roles[$role_key]))
				continue;
			$role = $GLOBALS['wp_roles']->roles[$role_key];
			if (isset($adminSettingsPresent[$role['name']])) {
				if ($adminSettingsPresent[$role['name']] == 'show')
					$show = true;
				if ($adminSettingsPresent[$role['name']] == 'hide' && $show === null)
					$show = false;
			}
		}
	return $show === null ? $content : $show;
}


if(!function_exists('qum_curpageurl')){
	function qum_curpageurl() {
		$pageURL = 'http';
		
		if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on"))
			$pageURL .= "s";
			
		$pageURL .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80")
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			
		else
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		
		return $pageURL;
	}
}


if ( is_admin() ){

	// register the settings for the menu only display sidebar menu for a user with a certain capability, in this case only the "admin"
	add_action( 'admin_init', 'qum_register_settings' );	

	// display the same extra profile fields in the admin panel also
	if ( file_exists ( QUM_PLUGIN_DIR.'/front-end/extra-fields/extra-fields.php' ) ){
		require_once( QUM_PLUGIN_DIR.'/front-end/extra-fields/extra-fields.php' );
		
		add_action( 'show_user_profile', 'display_profile_extra_fields_in_admin', 10 );
		add_action( 'edit_user_profile', 'display_profile_extra_fields_in_admin', 10 );
        global $pagenow;
        if( $pagenow != 'user-new.php' )
            add_action( 'user_profile_update_errors', 'qum_validate_backend_fields', 10, 3 );
		add_action( 'personal_options_update', 'save_profile_extra_fields_in_admin', 10 );
		add_action( 'edit_user_profile_update', 'save_profile_extra_fields_in_admin', 10 );
	}

}else if ( !is_admin() ){
	// include the stylesheet
	add_action( 'wp_print_styles', 'qum_add_plugin_stylesheet' );		

	// include the menu file for the profile informations
	include_once( QUM_PLUGIN_DIR.'/front-end/edit-profile.php' ); 
	include_once( QUM_PLUGIN_DIR.'/front-end/class-formbuilder.php' );  	
	add_shortcode( 'qum-edit-profile', 'qum_front_end_profile_info' );

	// include the menu file for the login screen
	include_once( QUM_PLUGIN_DIR.'/front-end/login.php' );       
	add_shortcode( 'qum-login', 'qum_front_end_login' );

    // include the menu file for the logout screen
    include_once( QUM_PLUGIN_DIR.'/front-end/logout.php' );
    add_shortcode( 'qum-logout', 'qum_front_end_logout' );

	// include the menu file for the register screen
	include_once( QUM_PLUGIN_DIR.'/front-end/register.php' );        		
	add_shortcode( 'qum-register', 'qum_front_end_register_handler' );	
	
	// include the menu file for the recover password screen
	include_once( QUM_PLUGIN_DIR.'/front-end/recover.php' );        		
	add_shortcode( 'qum-recover-password', 'qum_front_end_password_recovery' );

	// set the front-end admin bar to show/hide
	add_filter( 'show_admin_bar' , 'qum_show_admin_bar');

	// Shortcodes used for the widget area
	add_filter( 'widget_text', 'do_shortcode', 11 );
}


/**
 * Function that overwrites the default wp_mail function and sends out emails
 *
 * @since v.1.0
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $message_from
 *
 */
function qum_mail($to, $subject, $message, $message_from){
	$to = apply_filters ( 'qum_send_email_to', $to );
	$send_email = apply_filters ( 'qum_send_email', true, $to, $subject, $message );
	
	$message = apply_filters( 'qum_email_message', $message );

	do_action( 'qum_before_sending_email', $to, $subject, $message, $send_email );
	
	if ( $send_email ){
		//we add this filter to enable html encoding
		add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html"; ' ) );	
		
		$sent = wp_mail( $to , $subject, $message );
	}
	
	do_action( 'qum_after_sending_email', $sent, $to, $subject, $message, $send_email );
	
	return $sent;
}

function qum_activate_account_check(){
	if ( ( isset( $_GET['activation_key'] ) ) && ( trim( $_GET['activation_key'] ) != '' ) ){
		global $post;

		$qum_generalSettings = get_option( 'qum_general_settings' );
		$activation_landing_page_id = ( ( isset( $qum_generalSettings['activationLandingPage'] ) && ( trim( $qum_generalSettings['activationLandingPage'] ) != '' ) ) ? $qum_generalSettings['activationLandingPage'] : 'not_set' );
		
		if ( $activation_landing_page_id != 'not_set' ){
			//an activation page was selected, but we still need to check if the current page doesn't already have the registration shortcode
			if ( strpos( $post->post_content, '[qum-register' ) === false )
				add_filter( 'the_content', 'qum_add_activation_message' );

		}elseif ( strpos( $post->post_content, '[qum-register' ) === false ){
			//no activation page was selected, and the sent link pointed to the home url
			wp_redirect( apply_filters( 'qum_activatate_account_redirect_url', QUM_PLUGIN_URL.'assets/misc/fallback-page.php?activation_key='.urlencode( $_GET['activation_key'] ).'&site_name='.urlencode( get_bloginfo( 'name' ) ).'&site_url='.urlencode( get_bloginfo( 'url' ) ).'&message='.urlencode( $activation_message = qum_activate_signup( $_GET['activation_key'] ) ), $_GET['activation_key'], $activation_message ) ); 
			exit;
		}
	}
}
add_action( 'template_redirect', 'qum_activate_account_check' );


function qum_add_activation_message( $content ){

	return qum_activate_signup( $_GET['activation_key'] ) . $content;
}


// Create a new, top-level page
$args = array(							
			'page_title'	=> 'Quick User Manager',
			'menu_title'	=> 'Quick User Manager',
			'capability'	=> 'manage_options',
			'menu_slug' 	=> 'quick-user-manager',
			'page_type'		=> 'menu_page',
			'position' 		=> '70,69',
			'priority' 		=> 1,
			'icon_url' 		=> QUM_PLUGIN_URL . 'assets/images/qum_menu_icon.png'
		);
new WCK_Page_Creator_QUM( $args );

/**
 * Remove the automatically created submenu page
 *
 * @since v.1.0
 *
 * @return void
 */
function qum_remove_main_menu_page(){
	remove_submenu_page( 'quick-user-manager', 'quick-user-manager' );
}
add_action( 'admin_menu', 'qum_remove_main_menu_page', 11 );

/**
 * Add scripts to the back-end CPT's to remove the slug from the edit page
 *
 * @since v.1.0
 *
 * @return void
 */
function qum_print_cpt_script( $hook ){
	wp_enqueue_script( 'jquery-ui-dialog' );
    wp_enqueue_style( 'wp-jquery-ui-dialog' );
    
	if ( $hook == 'quick-user-manager_page_manage-fields' ){
		wp_enqueue_script( 'qum-manage-fields-live-change', QUM_PLUGIN_URL . 'assets/js/jquery-manage-fields-live-change.js', array(), QUICK_USER_MANAGER_VERSION, true );
	}
	
	if ( ( $hook == 'quick-user-manager_page_manage-fields' ) || ( $hook == 'quick-user-manager_page_quick-user-manager-basic-info' ) || ( $hook == 'quick-user-manager_page_quick-user-manager-modules' ) || ( $hook == 'quick-user-manager_page_quick-user-manager-general-settings' ) || ( $hook == 'quick-user-manager_page_quick-user-manager-admin-bar-settings' ) || ( $hook == 'quick-user-manager_page_quick-user-manager-register' ) || ( $hook == 'quick-user-manager_page_quick-user-manager-qum_userListing' ) || ( $hook == 'quick-user-manager_page_quick-user-manager-qum_customRedirect' ) || ( $hook == 'quick-user-manager_page_quick-user-manager-qum_emailCustomizer' ) || ( $hook == 'quick-user-manager_page_quick-user-manager-qum_emailCustomizerAdmin' ) || ( $hook == 'quick-user-manager_page_quick-user-manager-add-ons' ) ){
		wp_enqueue_style( 'qum-back-end-style', QUM_PLUGIN_URL . 'assets/css/style-back-end.css', false, QUICK_USER_MANAGER_VERSION );
	}
	
	if ( $hook == 'quick-user-manager_page_quick-user-manager-general-settings' )
		wp_enqueue_script( 'qum-manage-fields-live-change', QUM_PLUGIN_URL . 'assets/js/jquery-email-confirmation.js', array(), QUICK_USER_MANAGER_VERSION, true );

    if( $hook == 'quick-user-manager_page_quick-user-manager-add-ons' )
        wp_enqueue_script( 'qum-add-ons', QUM_PLUGIN_URL . 'assets/js/jquery-qum-add-ons.js', array(), QUICK_USER_MANAGER_VERSION, true );

	if ( isset( $_GET['post_type'] ) || isset( $_GET['post'] ) ){
		if ( isset( $_GET['post_type'] ) )
			$post_type = $_GET['post_type'];
		
		elseif ( isset( $_GET['post'] ) )
			$post_type = get_post_type( $_GET['post'] );
		
		if ( ( 'qum-epf-cpt' == $post_type ) || ( 'qum-rf-cpt' == $post_type ) || ( 'qum-ul-cpt' == $post_type ) ){
			wp_enqueue_style( 'qum-back-end-style', QUM_PLUGIN_URL . 'assets/css/style-back-end.css', false, QUICK_USER_MANAGER_VERSION );
			wp_enqueue_script( 'qum-epf-rf', QUM_PLUGIN_URL . 'assets/js/jquery-epf-rf.js', array(), QUICK_USER_MANAGER_VERSION, true );
		}
	}
    if ( file_exists ( QUM_PLUGIN_DIR.'/update/update-checker.php' ) ) {
        wp_enqueue_style( 'qum-serial-notice-css', QUM_PLUGIN_URL . 'assets/css/serial-notice.css', false, QUICK_USER_MANAGER_VERSION );
        wp_enqueue_script( 'qum-sitewide', QUM_PLUGIN_URL . 'assets/js/jquery-qum-sitewide.js', array(), QUICK_USER_MANAGER_VERSION, true );
    }
}
add_action( 'admin_enqueue_scripts', 'qum_print_cpt_script' );


// Set up the AJAX hooks
function qum_delete(){
	if ( isset( $_POST['_ajax_nonce'] ) ){
		
		if ( ( isset( $_POST['what'] ) ) && ( $_POST['what'] == 'avatar' ) ){
			if ( !wp_verify_nonce( $_POST['_ajax_nonce'], 'user'.base64_decode( $_POST['currentUser'] ).'_nonce_avatar' ) ){
				echo __( 'The user-validation has failed - the avatar was not deleted!', 'quickusermanager' );
				die();
				
			}else{
				update_user_meta( base64_decode( $_POST['currentUser'] ), base64_decode( $_POST['customFieldName'] ), '');
				update_user_meta( base64_decode( $_POST['currentUser'] ), 'resized_avatar_'.base64_decode(  $_POST['customFieldID'] ), '' );
				echo 'done';
				die();
			}
		}elseif ( ( isset( $_POST['what'] ) ) && ( $_POST['what'] == 'attachment' ) ){
			if ( !wp_verify_nonce( $_POST['_ajax_nonce'], 'user'.base64_decode( $_POST['currentUser'] ).'_nonce_upload' ) ){
				echo __( 'The user-validation has failed - the attachment was not deleted!', 'quickusermanager' );
				die();
				
			}else{
				update_user_meta( base64_decode( $_POST['currentUser'] ), base64_decode( $_POST['customFieldName'] ), '');
				echo 'done';
				die();
			}
		}
	}
} 
add_action( 'wp_ajax_hook_qum_delete', 'qum_delete' );


//the function used to overwrite the avatar across the wp installation
function qum_changeDefaultAvatar( $avatar, $id_or_email, $size, $default, $alt ){
	/* Get user info. */ 
	if(is_object($id_or_email)){
		$my_user_id = $id_or_email->user_id;
		
	}elseif(is_numeric($id_or_email)){
		$my_user_id = $id_or_email; 
		
	}elseif(!is_integer($id_or_email)){
		$user_info = get_user_by( 'email', $id_or_email );
		$my_user_id = ( is_object( $user_info ) ? $user_info->ID : '' );
	}else  
		$my_user_id = $id_or_email; 

	$qum_manage_fields = get_option( 'qum_manage_fields', 'not_found' );
	
	if ( $qum_manage_fields != 'not_found' ){
		foreach( $qum_manage_fields as $value ){
			if ( $value['field'] == 'Avatar'){

                $customUserAvatar = get_user_meta( $my_user_id, $value['meta-name'], true );
                if( !empty( $customUserAvatar ) ){
                    if( is_numeric( $customUserAvatar ) ){
                        $img_attr = wp_get_attachment_image_src( $customUserAvatar, 'qum-avatar-size-'.$size );
                        if( $img_attr[3] === false ){
                            $img_attr = wp_get_attachment_image_src( $customUserAvatar, 'thumbnail' );
                            $avatar = "<img alt='{$alt}' src='{$img_attr[0]}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
                        }
                        else
                            $avatar = "<img alt='{$alt}' src='{$img_attr[0]}' class='avatar avatar-{$size} photo avatar-default' height='{$img_attr[2]}' width='{$img_attr[1]}' />";
                    }
                    else {
                        $customUserAvatar = get_user_meta($my_user_id, 'resized_avatar_' . $value['id'], true);
                        $customUserAvatarRelativePath = get_user_meta($my_user_id, 'resized_avatar_' . $value['id'] . '_relative_path', true);

                        if ((($customUserAvatar != '') || ($customUserAvatar != null)) && file_exists($customUserAvatarRelativePath)) {
                            $avatar = "<img alt='{$alt}' src='{$customUserAvatar}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
                        }
                    }
                }
			}
		}
	}

	return $avatar;
}
add_filter( 'get_avatar', 'qum_changeDefaultAvatar', 21, 5 );


//the function used to resize the avatar image; the new function uses a user ID as parameter to make pages load faster
function qum_resize_avatar( $userID, $userlisting_size = null, $userlisting_crop = null ){
	// include the admin image API
	require_once( ABSPATH . '/wp-admin/includes/image.php' );
	
	// retrieve first a list of all the current custom fields
	$qum_manage_fields = get_option( 'qum_manage_fields' );
	
	foreach ( $qum_manage_fields as $key => $value ){
		if ( $value['field'] == 'Avatar' ){
				
			// retrieve width and height of the image
			$width = $height = '';
			
			//this checks if it only has 1 component
			if ( is_numeric( $value['avatar-size'] ) ){
				$width = $height = $value['avatar-size'];

			}else{
				//this checks if the entered value has 2 components
				$sentValue = explode( ',', $value['avatar-size'] );
				$width = $sentValue[0];
				$height = $sentValue[1];
			}
			
			$width = ( !empty( $userlisting_size ) ? $userlisting_size : $width );
			$height = ( !empty( $userlisting_size ) ? $userlisting_size : $height );

            if( !strpos( get_user_meta( $userID, 'resized_avatar_'.$value['id'], true ), $width . 'x' . $height ) ) {
                // retrieve the original image (in original size)
                $avatar_directory_path = get_user_meta( $userID, 'avatar_directory_path_'.$value['id'], true );

                $image = wp_get_image_editor( $avatar_directory_path );
                if ( !is_wp_error( $image ) ) {
                    do_action( 'qum_before_avatar_resizing', $image, $userID, $value['meta-name'], $value['avatar-size'] );

                    $crop = apply_filters( 'qum_avatar_crop_resize', ( !empty( $userlisting_crop ) ? $userlisting_crop : false ) );

                    $resize = $image->resize( $width, $height, $crop );

                    if ($resize !== FALSE) {
                        do_action( 'qum_avatar_resizing', $image, $resize );

                        $fileType = apply_filters( 'qum_resized_file_extension', 'png' );

                        $wp_upload_array = wp_upload_dir(); // Array of key => value pairs

                        //create file(name); both with directory and url
                        $fileName_dir = $image->generate_filename( NULL, $wp_upload_array['basedir'].'/QUICK_USER_MANAGER/avatars/', $fileType );

                        if ( PHP_OS == "WIN32" || PHP_OS == "WINNT" )
                            $fileName_dir = str_replace( '\\', '/', $fileName_dir );

                        $fileName_url = str_replace( str_replace( '\\', '/', $wp_upload_array['basedir'] ), $wp_upload_array['baseurl'], $fileName_dir );

                        //save the newly created (resized) avatar on the disc
                        $saved_image = $image->save( $fileName_dir );

                        /* the image save sometimes doesn't save with the desired extension so we need to see with what extension it saved it with and
                        if it differs replace the extension	in the path and url that we save as meta */
                        $validate_saved_image = wp_check_filetype_and_ext( $saved_image['path'], $saved_image['path'] );
                        $ext = substr( $fileName_dir,strrpos( $fileName_dir, '.', -1 ), strlen($fileName_dir) );
                        if( !empty( $validate_saved_image['ext'] ) && $validate_saved_image['ext'] != $ext ){
                            $fileName_url = str_replace( $ext, '.'.$validate_saved_image['ext'], $fileName_url );
                            $fileName_dir = str_replace( $ext, '.'.$validate_saved_image['ext'], $fileName_dir );
                        }

                        update_user_meta( $userID, 'resized_avatar_'.$value['id'], $fileName_url );
                        update_user_meta( $userID, 'resized_avatar_'.$value['id'].'_relative_path', $fileName_dir );

                        do_action( 'qum_after_avatar_resizing', $image, $fileName_dir, $fileName_url );
                    }
                }
            }
		}
	}
}


if ( is_admin() ){
	// add a hook to delete the user from the _signups table if either the email confirmation is activated, or it is a wpmu installation
	function qum_delete_user_from_signups_table($user_id) {
		global $wpdb;

		$userLogin = $wpdb->get_var( $wpdb->prepare( "SELECT user_login, user_email FROM " . $wpdb->users . " WHERE ID = %d LIMIT 1", $user_id ) );
		if ( is_multisite() )
			$delete = $wpdb->delete( $wpdb->signups, array( 'user_login' => $userLogin ) );
		else
			$delete = $wpdb->delete( $wpdb->prefix.'signups', array( 'user_login' => $userLogin ) );
	}
	
	if ( is_multisite() )
		add_action( 'wpmu_delete_user', 'qum_delete_user_from_signups_table' );
	
	else{
		$qum_generalSettings = get_option( 'qum_general_settings' );
				
		if ( $qum_generalSettings['emailConfirmation'] == 'yes' )
			add_action( 'delete_user', 'qum_delete_user_from_signups_table' );
	}

}



// This function offers compatibility with the all in one event calendar plugin
function qum_aioec_compatibility(){

	wp_deregister_script( 'jquery.tools-form');
}
add_action('admin_print_styles-users_page_quickusermanagerOptionsAndSettings', 'qum_aioec_compatibility');


function qum_user_meta_exists( $id, $meta_name ){
	global $wpdb;
	
	return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->usermeta WHERE user_id = %d AND meta_key = %s", $id, $meta_name ) );
}


// function to check if there is a need to add the http:// prefix
function qum_check_missing_http( $redirectLink ) {
	return preg_match( '#^(?:[a-z\d]+(?:-+[a-z\d]+)*\.)+[a-z]+(?::\d+)?(?:/|$)#i', $redirectLink );
}



//function to output the password strength checker on frontend forms
function qum_password_strength_checker_html(){
    $qum_generalSettings = get_option( 'qum_general_settings' );
    if( !empty( $qum_generalSettings['minimum_password_strength'] ) ){
        $password_strength = '<span id="pass-strength-result">'.__('Strength indicator', 'quickusermanager' ).'</span>
        <input type="hidden" value="" name="qum_password_strength" id="qum_password_strength"/>';
        return $password_strength;
    }
    return '';
}

//function to check password length check
function qum_check_password_length( $password ){
    $qum_generalSettings = get_option( 'qum_general_settings' );
    if( !empty( $qum_generalSettings['minimum_password_length'] ) ){
        if( strlen( $password ) < $qum_generalSettings['minimum_password_length'] ){
            return true;
        }
        else
            return false;
    }
    return false;
}

//function to check password strength
function qum_check_password_strength(){
    $qum_generalSettings = get_option( 'qum_general_settings' );
    if( isset( $_POST['qum_password_strength'] ) && !empty( $qum_generalSettings['minimum_password_strength'] ) ){
        $password_strength_array = array( 'short' => 0, 'bad' => 1, 'good' => 2, 'strong' => 3 );
        $password_strength_text = array( 'short' => __( 'Very Weak', 'quickusermanager' ), 'bad' => __( 'Weak', 'quickusermanager' ), 'good' => __( 'Medium', 'quickusermanager' ), 'strong' => __( 'Strong', 'quickusermanager' ) );
        if( $password_strength_array[$_POST['qum_password_strength']] < $password_strength_array[$qum_generalSettings['minimum_password_strength']] ){
            return $password_strength_text[$qum_generalSettings['minimum_password_strength']];
        }
        else
            return false;
    }
    return false;
}

/* function to output password length requirements text */
function qum_password_length_text(){
    $qum_generalSettings = get_option( 'qum_general_settings' );
    if( !empty( $qum_generalSettings['minimum_password_length'] ) ){
        return sprintf(__('Minimum length of %d characters', 'quickusermanager'), $qum_generalSettings['minimum_password_length']);
    }
    return '';
}

/**
 * Include password strength check scripts on frontend where we have shortoces present
 */
add_action( 'wp_footer', 'qum_enqueue_password_strength_check' );
function qum_enqueue_password_strength_check() {
    global $qum_shortcode_on_front;
    if( $qum_shortcode_on_front ){
        $qum_generalSettings = get_option( 'qum_general_settings' );
        if( !empty( $qum_generalSettings['minimum_password_strength'] ) ){
             wp_enqueue_script( 'password-strength-meter' );
        }
    }
}
add_action( 'wp_footer', 'qum_password_strength_check', 102 );
function qum_password_strength_check(){
    global $qum_shortcode_on_front;
    if( $qum_shortcode_on_front ){
        $qum_generalSettings = get_option( 'qum_general_settings' );
        if( !empty( $qum_generalSettings['minimum_password_strength'] ) ){
            ?>
            <script type="text/javascript">
                function check_pass_strength() {
                    var pass1 = jQuery('#passw1').val(), pass2 = jQuery('#passw2').val(), strength;

                    jQuery('#pass-strength-result').removeClass('short bad good strong');
                    if ( ! pass1 ) {
                        jQuery('#pass-strength-result').html( pwsL10n.empty );
                        return;
                    }

                    strength = wp.passwordStrength.meter( pass1, wp.passwordStrength.userInputBlacklist(), pass2 );

                    switch ( strength ) {
                        case 2:
                            jQuery('#pass-strength-result').addClass('bad').html( pwsL10n.bad );
                            jQuery('#qum_password_strength').val('bad');
                            break;
                        case 3:
                            jQuery('#pass-strength-result').addClass('good').html( pwsL10n.good );
                            jQuery('#qum_password_strength').val('good');
                            break;
                        case 4:
                            jQuery('#pass-strength-result').addClass('strong').html( pwsL10n.strong );
                            jQuery('#qum_password_strength').val('strong');
                            break;
                        case 5:
                            jQuery('#pass-strength-result').addClass('short').html( pwsL10n.mismatch );
                            jQuery('#qum_password_strength').val('short');
                            break;
                        default:
                            jQuery('#pass-strength-result').addClass('short').html( pwsL10n['short'] );
                            jQuery('#qum_password_strength').val('short');
                    }
                }
                jQuery( document ).ready( function() {
                    // Binding to trigger checkPasswordStrength
                    jQuery('#passw1').val('').keyup( check_pass_strength );
                    jQuery('#passw2').val('').keyup( check_pass_strength );
                    jQuery('#pass-strength-result').show();
                });
            </script>
        <?php
        }
    }
}
/**
 * Create functions for repeating error messages in front-end forms
 */
function qum_required_field_error($field_title='') {
    $required_error = apply_filters('qum_required_error' , __('This field is required','quickusermanager') , $field_title);

    return $required_error;

}
/* Function for displaying reCAPTCHA error on Login and Recover Password forms */
function qum_recaptcha_field_error($field_title='') {
    $recaptcha_error = apply_filters('qum_recaptcha_error' , __('Please enter a (valid) reCAPTCHA value','quickusermanager') , $field_title);

    return $recaptcha_error;

}

/* Create a wrapper function for get_query_var */
function qum_get_query_var( $varname ){
    return apply_filters( 'qum_get_query_var_'.$varname, get_query_var( $varname ) );
}

/*Filter the "Save Changes" button text, to make it translatable*/
function qum_change_save_changes_button($value){
    $value = __('Save Changes','quickusermanager');
    return $value;
}
add_filter( 'wck_save_changes_button', 'qum_change_save_changes_button', 10, 2);

/*Filter the "Cancel" button text, to make it translatable*/
function qum_change_cancel_button($value){
    $value = __('Cancel','quickusermanager');
    return $value;
}
add_filter( 'wck_cancel_button', 'qum_change_cancel_button', 10, 2);

/*Filter the "Delete" button text, to make it translatable*/
function qum_change_delete_button($value){
    $value = __('Delete','quickusermanager');
    return $value;
}
add_filter( 'wck_delete_button', 'qum_change_delete_button', 10, 2);

/*Filter the "Edit" button text, to make it translatable*/
function qum_change_edit_button($value){
    $value = __('Edit','quickusermanager');
    return $value;
}
add_filter( 'wck_edit_button', 'qum_change_edit_button', 10, 2);

/*Filter the User Listing, Register Forms and Edit Profile forms metabox header content, to make it translatable*/
function qum_change_metabox_content_header(){
  return '<thead><tr><th class="wck-number">#</th><th class="wck-content">'. __( 'Content', 'quickusermanager' ) .'</th><th class="wck-edit">'. __( 'Edit', 'quickusermanager' ) .'</th><th class="wck-delete">'. __( 'Delete', 'quickusermanager' ) .'</th></tr></thead>';
}
add_filter('wck_metabox_content_header_qum_ul_page_settings', 'qum_change_metabox_content_header', 1);
add_filter('wck_metabox_content_header_qum_rf_page_settings', 'qum_change_metabox_content_header', 1);
add_filter('wck_metabox_content_header_qum_epf_page_settings', 'qum_change_metabox_content_header', 1);


/* Add a notice if people are not able to register via Quick User Manager; Membership -> "Anyone can register" checkbox is not checked under WordPress admin UI -> Settings -> General tab */
if ( get_option('users_can_register') == false) {
    if( is_multisite() ) {
        new qum_Add_General_Notices('qum_anyone_can_register',
            sprintf(__('To allow users to register for your website via Quick User Manager, you first must enable user registration. Go to %1$sNetwork Settings%2$s, and under Registration Settings make sure to check “User accounts may be registered”. %3$sDismiss%4$s', 'quickusermanager'), "<a href='" . network_admin_url('settings.php') . "'>", "</a>", "<a href='" . esc_url( add_query_arg('qum_anyone_can_register_dismiss_notification', '0') ) . "'>", "</a>"),
            'update-nag');
    }else{
        new qum_Add_General_Notices('qum_anyone_can_register',
            sprintf(__('To allow users to register for your website via Quick User Manager, you first must enable user registration. Go to %1$sSettings -> General%2$s tab, and under Membership make sure to check “Anyone can register”. %3$sDismiss%4$s', 'quickusermanager'), "<a href='" . admin_url('options-general.php') . "'>", "</a>", "<a href='" . esc_url( add_query_arg('qum_anyone_can_register_dismiss_notification', '0') ) . "'>", "</a>"),
            'update-nag');
    }
}

/*Filter default WordPress notices ("Post published. Post updated."), add post type name for User Listing, Registration Forms and Edit Profile Forms*/
function qum_change_default_post_updated_messages($messages){
    global $post;
    $post_type = get_post_type($post->ID);
    $object = get_post_type_object($post_type);

    if ( ($post_type == 'qum-rf-cpt')||($post_type == 'qum-epf-cpt')||($post_type == 'qum-ul-cpt') ){
        $messages['post'][1] = $object->labels->name . ' updated.';
        $messages['post'][6] = $object->labels->name . ' published.';
    }
    return $messages;
}
add_filter('post_updated_messages','qum_change_default_post_updated_messages', 2);