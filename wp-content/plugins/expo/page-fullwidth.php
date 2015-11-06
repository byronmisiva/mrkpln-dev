<?php 
/*
Template Name: Full Width 
*/
get_header();
 ?>     

<div class="row">


 


<div class="col-md-12">
<div  id="single-post post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
<?php 

while(have_posts()): the_post(); ?>

    <div  <?php post_class('post single-post'); ?>>
        <div class="breadcrumb"><h1 class="entry-title"><?php the_title(); ?></h1></div>
    <?php newsflash_post_thumb(array(1200,0)); ?>
<div class="entry-content">
<?php the_content(); ?>
</div>
<?php wp_link_pages( ); ?>
</div>

<?php endwhile; ?>
</div>
</div>

</div>


         

<?php get_footer(); ?>
