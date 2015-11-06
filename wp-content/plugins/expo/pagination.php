<?php 
global $wp_rewrite, $wp_query;
$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

if (  $wp_query->max_num_pages > 1 ) :
$pagination = array(
    'base' => @add_query_arg('paged','%#%'),
    'format' => '',
    'total' => $wp_query->max_num_pages,
    'current' => $current,
    'show_all' => false,
    'type' => 'list',
    'prev_next'    => True,
    'prev_text' => '<i class="fa fa-angle-left"></i> Previous',
    'next_text' => 'Next <i class="fa fa-angle-right"></i>',
    );

if( $wp_rewrite->using_permalinks() )
    $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg('s',get_pagenum_link(1) ) ) . 'page/%#%/', 'paged');

if( !empty($wp_query->query_vars['s']) )
    $pagination['add_args'] = array('s'=>get_query_var('s'));

echo '<div class="text-center">' . str_replace("<ul class='page-numbers'>","<ul class='pagination'>",paginate_links($pagination)) . '</div>';

endif;
?>