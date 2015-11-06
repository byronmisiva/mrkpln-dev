<?php get_header(); ?>


<div class="row">

<div class="col-md-3 col-sm-4">

    <?php dynamic_sidebar('homepage_ads_left_top'); ?>
    <?php dynamic_sidebar('homepage_sidebar_left'); ?>
    <?php dynamic_sidebar('homepage_ads_left_bottom'); ?>

</div>

<div class="col-md-6 col-sm-8">

    <?php get_template_part('slider'); ?>

    <?php get_template_part('loop'); ?>

</div>

<div class="col-md-3">
    <?php dynamic_sidebar('homepage_ads_right_top'); ?>
    <?php dynamic_sidebar('homepage_sidebar_right'); ?>
    <?php dynamic_sidebar('homepage_ads_right_bottom'); ?>
</div>

</div>

      
<?php get_footer(); ?>
