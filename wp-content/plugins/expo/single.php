<?php get_header(); ?>     

<div class="row">
<div class="col-md-9">
<div  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>         
<?php 

while(have_posts()): the_post(); $meta = maybe_unserialize(get_post_meta(get_the_ID(),'wpeden_post_meta', true)); ?>
 
<div  <?php post_class('post single-post'); ?>>
<div class="breadcrumb"><h1 class="entry-title"><?php the_title(); ?></h1></div>
<div class="post-meta">Posted on <?php the_date(); ?> / Posted by <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></div>
<div class="clear"></div>

    <?php if(get_post_format()=='link') {
        newsflash_post_thumb(array(1100,0));
        ?>

    <blockquote class="link">
        <a href="<?php echo esc_url($meta['linkurl']); ?>"><i class="fa fa-link"></i> <?php echo esc_url($meta['linkurl']); ?></a>
    </blockquote>
    <?php } else if(get_post_format()=='video') { ?>


                <?php
                echo wp_oembed_get($meta['videourl'],array('width'=>850)); ?>


    <?php } else if(get_post_format()=='gallery') {  ?>

        <?php newsflash_post_gallery(900,0); ?>

    <?php } else {  newsflash_post_thumb(array(1100,0)); } ?>
<div class="entry-content">
<?php if(get_post_format()=='audio') echo do_shortcode('[audio]'); ?>
<?php the_content(); ?>
</div>
<?php wp_link_pages( ); ?>
    <div class="well tags">
    <nav id="nav-single">
        <?php previous_post_link( '%link', '<i class="fa fa-chevron-left"></i>'); ?>
        <?php next_post_link( '%link', '<i class="fa fa-chevron-right"></i>' ); ?>
    </nav>
</div>
<div class="well tags">
<b><span>Tags: </span></b>
<?php the_tags('',', '); ?>
<div class="clear"></div>
</div>

<div class="well author-box">
 
 <div class="media">
 <div class="pull-left">
 <?php echo get_avatar( get_the_author_meta('ID'), 90 ); ?>
 </div>
 <div class="media-body">  <span class="txt">
 <b><i class="fa fa-edit"></i> About Author: <?php echo get_the_author_meta('display_name'); ?></b>
 </span>
 <div class="clear"></div>
 <?php echo get_the_author_meta('description'); ?>
 </div>
 </div>
 </div>

</div>
 <div class="mx_comments"> 
<?php comments_template(); ?>
</div>
<?php endwhile; ?>
</div>
</div>
<div class="col-md-3">
<div class="sidebar"> 
<?php dynamic_sidebar('Single Post'); ?>
</div>
</div>
</div>


         

<?php get_footer(); ?>
