<?php the_post(); ?>
<div class="panel panel-default">
<div class="panel-body">
<div class="slider marketplace">
    <?php newsflash_post_thumb(array(600,300));?>
    <div class="caption">
        <h3 class="media-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

        <div class="post-meta">In <?php the_terms(get_the_ID(), 'ptype'); ?> / by <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> / on <?php echo get_the_date(); ?> /
            <?php comments_number( ); ?>
        </div>


    </div>
</div>
</div>
    <div class="panel-footer">
        <span class="text-success"><?php echo $dc = number_format(intval(get_post_meta(get_the_ID(),'__wpdm_download_count',true)),0,'',','); ?> download<?php if($dc>1) echo 's'; ?></span>
        <a class="pull-right btn btn-primary btn-xs" href="<?php the_permalink(); ?>">read more <i class="fa fa-angle-right"></i></a>


        <div class="clear"></div>
    </div>
</div>