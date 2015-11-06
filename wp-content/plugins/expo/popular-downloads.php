<div <?php post_class('panel panel-default product'); ?> >
    <div class="panel-body"><div class="media">
    <?php if(has_post_thumbnail()): ?>
    <a class="pull-left" href="<?php the_permalink(); ?>">
        <?php newsflash_post_thumb(array(100,90)); ?>
    </a>
    <?php endif; ?>

    <div class="media-body">
        <h3 class="media-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

        <div class="post-meta">In <?php the_terms(get_the_ID(), 'ptype'); ?> / by <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> / on <?php echo get_the_date(); ?> /
            <?php comments_number( ); ?>
        </div>


    </div>
        </div> </div>
    <div class="panel-footer">
        <span class="text-success"><?php echo $dc = number_format(intval(get_post_meta(get_the_ID(),'__wpdm_download_count',true)),0,'',','); ?> download<?php if($dc>1) echo 's'; ?></span>
        <a class="pull-right btn btn-primary btn-xs" href="<?php the_permalink(); ?>">read more <i class="fa fa-angle-right"></i></a>
        <div class="clear"></div>
    </div>
</div>
