<?php the_post(); ?>
<div class="slider thumbnail">
    <?php newsflash_post_thumb(array(600,300));?>
    <div class="caption">
        <h3 class="media-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

        <div class="post-meta">In <?php the_category(',') ?> / by <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> / on <?php echo get_the_date(); ?> /
            <?php comments_number( ); ?>
        </div>
        <p><?php newsflash_post_excerpt(100); ?>
            <a href="<?php the_permalink(); ?>">read more <i class="fa fa-angle-right"></i></a></p>
    </div>
</div>