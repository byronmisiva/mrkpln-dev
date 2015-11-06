<?php do_action("before_loop"); ?>

<?php while (have_posts()): the_post(); ?>

    <?php get_template_part("content", get_post_format()); ?>

<?php endwhile; ?>

<?php get_template_part("pagination"); ?>


<?php do_action("after_loop"); ?>
