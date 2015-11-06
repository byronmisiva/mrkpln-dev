<?php
get_header();
?>


<div class="row">

<div class="col-md-3 col-sm-4">

    <?php dynamic_sidebar('homepage_ads_left_top'); ?>
    <?php dynamic_sidebar('homepage_sidebar_left'); ?>
    <?php dynamic_sidebar('homepage_ads_left_bottom'); ?>

</div>

<div class="col-md-5 col-sm-8">
    <?php  query_posts("post_type=wpdmpro&posts_per_page=4"); ?>
    <?php get_template_part('slider','wpdmpro'); ?>

    <?php while (have_posts()): the_post(); ?>

        <?php get_template_part("content", "wpdmpro"); ?>

    <?php endwhile; ?>

</div>

<div class="col-md-4 featured-area">
   <div class="header"><h3>Popular Downloads</h3></div>
    <?php $params = array(

        'post_type' => 'wpdmpro',
        'posts_per_page' => 5,
        'orderby' => 'meta_value_num',
        'meta_key' => '__wpdm_download_count',
        'order' => 'desc'

    );
    $pd = new WP_Query($params);
    ?>
    <?php while ($pd->have_posts()): $pd->the_post(); ?>

        <?php get_template_part("popular", "downloads"); ?>

    <?php endwhile; ?>
</div>

</div>

      
<?php get_footer(); ?>
