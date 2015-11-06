<!DOCTYPE html>
<html <?php language_attributes(); ?> class="w3eden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php wpeden_favicon(); ?>
    <title><?php wp_title(); ?></title>


    <?php wp_head(); ?>
    <link href="<?php echo get_stylesheet_uri(); ?>" rel="stylesheet" type="text/css" />
</head>
<body <?php body_class(); ?>>

<div class="container">

    <div class="navbar navbar-color navbar-static-top" id="topnav">
        <div class="row">
        <div class="col-md-3">
        <div class="branding">
            <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>"><?php wpeden_get_site_logo(); ?></a>
        </div>
        </div>
        <div class="col-md-9">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="fa fa-reorder"></span>

            </button>

        </div>
        <div class="navbar-collapse collapse">


            <?php


            $args = array(
                'theme_location' => 'primary',
                'depth' => 9,
                'container' => false,
                'menu_class' => 'nav navbar-nav',
                'fallback_cb' => false,
                'walker' => new newsflash_bootstrap_walker_nav_menu()
            );


            wp_nav_menu($args);


            ?>

        </div>
        <!--/.navbar-collapse -->
        </div>
        </div>

    </div>

