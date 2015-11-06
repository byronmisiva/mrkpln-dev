<?php
// Warning :
// > You should copy this file (e.g. my-static-slides.php) if you want to customize the static slides!
// > Automatic update will replace this file so all your changes can be lost :(

// --- Slide template -----------------------------------
//<div class="da-slide">
//	<h2>Slide tittle</h2>
//	<p>Slide text here...</p>
//	<a href="http://jltweb.info/realisations/wp-parallax-content-plugin/" class="da-link">Read more link</a>
//	<div class="da-img"><img src="$plugin_abs_path/images/1.png" alt="Slkide image" /></div>
//</div>
// -------------------------------------------------------
?>
    <div id="da-slider" class="da-slider">
        <?php
        markplan_carousel();
        ?>
        <nav class="da-arrows">
            <span class="da-arrows-prev"></span>
            <span class="da-arrows-next"></span>
        </nav>
    </div>
<?php

function markplan_carousel()
{
    $read_more = __('Read more', 'wp-parallax-content-slider');
    $categoria = 'destacados';

    $params = array(
        'post_type' => 'wpdmpro',
        'posts_per_page' => 10
    );
    if (isset($categoria))
        $params['tax_query'] = array(array(
            'taxonomy' => 'wpdmcategory',
            'field' => 'slug',
            'terms' => array($categoria)
        ));
    $packs = get_posts($params);
    foreach ($packs as $file) {
        ?>
        <div class="da-slide">
            <h2><a href="<?php echo get_permalink($file->ID); ?>"><?php echo $file->post_title; ?></a></h2>

            <p><a href="<?php echo get_permalink($file->ID); ?>"><?php echo $file->post_content; ?></a></p>
            <div class="da-link">
            <a href="<?php echo get_permalink($file->ID); ?>"><?php echo $read_more; ?></a>
            </div>
            <div class="da-img">
                <a href="<?php echo get_permalink($file->ID); ?>"><?php wpdm_thumb($file->ID, array(300, 200)); ?></a>
            </div>
        </div>
        <?php
    }
}

?>

<div class="da-slide da-slide-toleft" style="display: none;">
    <h2><a href="http://demo.wpdownloadmanager.com/wpdmpro/wpdm-package/jumper/" class="font-effect-shadow-multiple">Jumper</a></h2>
    <p>
        Jumper – Single Page Responsive Website Template

        Provided By http://scripteden.com/...
    </p>
    <div class="da-link">
        <a class="btn btn-bordered" href="http://demo.wpdownloadmanager.com/wpdmpro/wpdm-package/jumper/">Continue to Download...</a>
    </div>
    <div class="da-img">
        <img src="http://demo.wpdownloadmanager.com/wpdmpro/wp-content/uploads/2013/12/091-350x300.jpg" alt="" class="">                    </div>
</div>
