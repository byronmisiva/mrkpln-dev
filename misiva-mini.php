<?php
/*
Plugin Name: Misiva Mini
Plugin URI: http://example.com/my-crazy-admin-theme
Description: Misiva Visor mini de admin  - Upload and Activate.
Author: Misiva Corp.
Version: 1.0
Author URI: http://misiva.com.ec
*/
function misiva_mini_admin_theme_style() {

	if (isset($_GET["mini"])) {
		if ($_GET["mini"] == '1' ){
		    wp_enqueue_script( 'script-name', plugins_url('misiva-admin-mini.js', __FILE__) , array(), '1.0.0', true );
//		    wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/misiva-admin-mini.js', array(), '1.0.0', true );
		}
	}
	if (isset($_GET["action"])) {
		if ($_GET["action"] == 'edit' ){
		    wp_enqueue_script( 'script-name', plugins_url('misiva-admin-mini-edit.js', __FILE__) , array(), '1.0.0', true );
//		    wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/misiva-admin-mini.js', array(), '1.0.0', true );
		}
	}

}
add_action('admin_enqueue_scripts', 'misiva_mini_admin_theme_style');


function theme_name_scripts() {
	wp_enqueue_style('my-admin-theme', plugins_url('misiva_style.css', __FILE__));
	wp_enqueue_script( 'script-name', plugins_url('misiva-front-mini.js', __FILE__) , array(), '1.0.0', true );
	//wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/misiva-front-mini.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );

// para el envio por email
function mi_funcion_ajax(){  
	$user_asunto = $_POST ['user_asunto'];
	$user_cuerpo = $_POST ['user_cuerpo'];
	$user_file = $_POST ['user_file'];
	$user_from = $_POST ['user_from'];
	$user_name = $_POST ['user_name'];	
	$user_to = $_POST ['user_to'];
	$user_file_name = $_POST ['user_file_name'];

	add_filter( 'wp_mail_content_type', 'set_html_content_type' );
	$to = $user_to;
	$subject = $user_asunto;
	$message = "Se a enviado un archivo, <br><br>Mensaje: <br><br> " . $user_cuerpo ."<br><br> Para descargar de click en el siguiente vínculo : <br><br>  <a  href='" . $user_file ."'>".$user_file_name."</a>"   ;
	$headers = 'De: ' . $user_name . ' <$user_from>' . "\r\n";

	wp_mail( $to, $subject, $message, $headers  );
	remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

	echo 'Mensaje enviado' ;
	die();
}  

function set_html_content_type() {
	return 'text/html';
}

// Creando las llamadas Ajax para el plugin de WordPress  
add_action( 'wp_ajax_nopriv_mi_funcion_accion', 'mi_funcion_ajax' );  
add_action( 'wp_ajax_mi_funcion_accion', 'mi_funcion_ajax' );


/*Grabar formulario envio */

/*function misiva_save_post(){

	if(isset($_POST['gptask']) && $_POST['gptask'] == 'savepost'){
		require_once("misiva-post-submit.php");
	}
}

add_action("wp", "misiva_save_post");*/
function misiva_save_post() {

	echo "aaaa";
	die();
	//
	//require_once("misiva-post-submit.php");

	if (!defined('ABSPATH')) die('!');
//Get Really Simple Guest Post submitted form
	ob_start();

	$title = $_REQUEST['titulo'];
	$description = $_REQUEST['content'];
	$cat =  $_REQUEST['jsonCategorias'];
	$archivo = array();
	$archivo[] =  $_REQUEST['archivo'];



//Load WordPress
//require($path);
	$current_user = wp_get_current_user();
//Verify the form fields
//if (!wp_verify_nonce($nonce)) die('Security check');
	$authorid = $current_user->ID;
//Post Properties
	$new_post = array(
		'post_title' => $title,
		'post_content' => $description,
		'post_status' => 'publish',           // Choose: publish, preview, future, draft, etc.
		'post_type' => 'wpdmpro',  //'post',page' or use a custom post type if you want to
		'post_author' => $authorid, //Author ID
		'comment_status' => 'closed', //Author ID
		'ping_status' => 'closed' //Author ID
	);
//save the new post
	$post_id = wp_insert_post($new_post);
	$ret = wp_set_post_terms($post_id, $cat, 'wpdmcategory' );

	/* Insert Form data into Custom Fields */
	add_post_meta($post_id, '__wpdm_individual_file_download','1', true);
	add_post_meta($post_id, '__wpdm_page_template',  '544fef1fd30cc', true);
	add_post_meta($post_id, '__wpdm_template', '545917f8343bb', true);
	$acceso1 = array();
	$acceso1[] = 'guest';
	add_post_meta($post_id, '__wpdm_access', $acceso1);

	update_post_meta($post_id, '__wpdm_files',  $archivo );
	$fileinfo = array($archivo[0]);
//	$fileinfo = [$archivo[0]];
	$fileinfo[$archivo[0]]['title'] = '' ;
	$fileinfo[$archivo[0]]['password'] = '' ;

//add_post_meta($post_id, '__wpdm_fileinfo' ,$fileinfo);


	add_post_meta($post_id, '__wpdm_package_dir','', true);
	add_post_meta($post_id, '_edit_lock','1442605134:18', true);
	add_post_meta($post_id, '_edit_last','18', true);
	add_post_meta($post_id, '__wpdm_publish_date','', true);
	add_post_meta($post_id, '__wpdm_expire_date','', true);
	add_post_meta($post_id, '__wpdm_version','', true);
	add_post_meta($post_id, '__wpdm_link_label','', true);
	add_post_meta($post_id, '__wpdm_quota','', true);
	add_post_meta($post_id, '__wpdm_download_limit_per_user','', true);
	add_post_meta($post_id, '__wpdm_view_count','19', true);
	add_post_meta($post_id, '__wpdm_download_count','1', true);
	add_post_meta($post_id, '__wpdm_package_size','661.97 KB', true);
	add_post_meta($post_id, '__wpdm_password','', true);
	add_post_meta($post_id, '__wpdm_password_usage_limit','', true);
	add_post_meta($post_id, '__wpdm_linkedin_message','', true);
	add_post_meta($post_id, '__wpdm_linkedin_url','', true);
	add_post_meta($post_id, '__wpdm_tweet_message','', true);
	add_post_meta($post_id, '__wpdm_google_plus_1','', true);
	add_post_meta($post_id, '__wpdm_facebook_like','', true);
	add_post_meta($post_id, '__wpdm_email_lock_idl','0', true);
	add_post_meta($post_id, '__wpdm_icon','', true);
	add_post_meta($post_id, '__wpdm_masterkey',uniqid(), true);
	add_post_meta($post_id, '__wpdm_package_size_b','677854', true);



	echo "OK";

	// Always die in functions echoing ajax content
	die();
}

add_action( 'wp_ajax_misiva_save_post', 'misiva_save_post' );