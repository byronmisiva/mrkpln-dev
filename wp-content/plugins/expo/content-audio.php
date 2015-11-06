<div <?php post_class('media post'); ?> >
    <?php if(has_post_thumbnail()): ?>
    <a class="pull-left thumbnail" href="<?php the_permalink(); ?>">
        <span class="post-format-tag"><i class="fa fa-music"></i> Audio</span>
        <?php newsflash_post_thumb(array(150,120)); ?>
    </a>
    <?php endif; ?>

    <div class="media-body">
        <h3 class="media-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

        <div class="post-meta">In <?php the_category(',') ?> / by <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> / on <?php echo get_the_date(); ?> /
            <?php comments_number( ); ?>
        </div>
        <div class="audio-player">
            <?php echo do_shortcode('[audio]'); ?>
        </div>
        <p><?php newsflash_post_excerpt(30); ?>
            <a href="<?php the_permalink(); ?>">read more <i class="fa fa-angle-right"></i></a></p>
    </div>
</div>

