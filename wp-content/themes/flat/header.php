<?php
/**
 * Template for site header
 * @package themify
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php echo themify_get_html_schema(); ?> <?php language_attributes(); ?>>
<head>
<?php
/** Themify Default Variables
 *  @var object */
	global $themify; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">

<title itemprop="name"><?php wp_title( '' ); ?></title>

<?php
/**
 *  Stylesheets and Javascript files are enqueued in theme-functions.php
 */
?>

<!-- wp_header -->
<?php wp_head(); ?>
<script type="text/javascript">
            (function() {
                var link_element = document.createElement("link"),
                    s = document.getElementsByTagName("script")[0];
                if (window.location.protocol !== "http:" && window.location.protocol !== "https:") {
                    link_element.href = "http:";
                }
                link_element.href += "//fonts.googleapis.com/css?family=Roboto:400,100italic,100,300italic,300,400italic,500italic,500,700italic,700,900italic,900";
                link_element.rel = "stylesheet";
                link_element.type = "text/css";
                s.parentNode.insertBefore(link_element, s);
            })();
        </script>
<!--CAMBIO -->
<link href="//fonts.googleapis.com/css?family=Comfortaa:300,400,700" rel="stylesheet" type="text/css">
		<link href="//fonts.googleapis.com/css?family=Roboto:100italic,100,300italic,300,400italic,400,500italic,500,700italic,700,900italic,900" rel="stylesheet" type="text/css">				
<style>
.img-llave{
 background-image: url("http://www.markplan.com/wp-content/uploads/2015/03/llave.png");
    background-position: center bottom;
    background-repeat: no-repeat;
    cursor: pointer;
    float: left;
    height: 26px;
    width: 35px;
}
.themify_builder .accordion-2415-9-0-0 .ui.module-accordion .accordion-title a {
    background-color: #000;
    color: #fff !important;
    font-family: Helvetica;
    font-size: 12pt;
    text-align:center;
}

.acorderon-atelento ul li .accordion-title a{
    float: left;
    height: 24px;
    width: 93% !important;
    background-color: transparent;
    text-align: left !important;
    font-weight: normal;
}
.acorderon-atelento ul li .accordion-title{
    border-bottom: 1px solid #fff;
    height:30px;
}

.flecha-acordeon{
    background-color: transparent;;
    background-image: url("http://markplan.misiva.com.ec/imagenes/flecha-abajo.png");
    background-position: right center;
    background-repeat: no-repeat;
    float: left;
    height: 34px;
    width: 4%;
}

.acorderon-atelento ul li .accordion-title:hover {
    background-color: #191818 !important;
    border-bottom: 1px solid #fff;
    color:#fff;
}

.acorderon-atelento ul li .accordion-title:hover .flecha-acordeon{    
   /* background-image: url("http://markplan.misiva.com.ec/imagenes/flecha_negra.png");*/
}

.acorderon-atelento ul li .accordion-title:visited .flecha-acordeon{
    transform: rotate(0deg)!important;
    background-color:#fff !important;
}

.themify_builder .accordion-2415-9-0-0 .ui.module-accordion .accordion-title a:hover{
    color:#fff !important;
}

.acorderon-atelento ul li .accordion-title { 
    border-bottom: 1px solid #fff;
    height: 30px;
}

#accordion-2415-9-0-0{
border:none;
}

.accordion-content{
 background-color: rgba(255, 255, 255, 0)!important;
}

.themify_builder .accordion-2415-9-0-0 .ui.module-accordion .accordion-title a {
    background-color: transparent !important;
}

.module-accordion li .current{
    background-color:rgba(0,0,0,0) !important;
}



.ui.black, .ui.black.nav ul, .ui.black.nav li, .ui.black.nav.separate > li, .ui.black.module-tab .tab-nav li, .ui.black.module-accordion li, .ui.black.window > div, .ui.black.module-tab .tab-nav {
    border-color: rgba(0, 0, 0, 0);
}

.ui.black > li.current, .ui.black > li.current:hover, .ui.black .tab-nav li.current{
  background-color:rgba(0,0,0,0) !important;
  border-color: rgba(0, 0, 0, 0);
}

.ui.black.builder_button:hover,
.ui.black.nav li:hover,
.ui.black.module-accordion li:hover .accordion-title,
.ui.black.module-tab .tab-nav li:hover { 
	background: rgba(0,0,0,0); 
}

.module-accordion {
    margin-bottom: 5px !important;
    margin-top: 20px !important;
    padding: 0;
}

@media screen and (max-width: 1025px) {
.tabla-descarga {
    margin-left: -50px;
    margin-top: 0;
    width: 70%;
}

.fondo-descargas{
    background-position: -140px 0;
}
}

@media screen and (max-width: 400px) {
#main-nav {
    height: auto;
    position: absolute;
    top: 60px;
}
.acorderon-atelento ul li .accordion-title a {
    text-align: left !important;
    width: 85% !important;
}

.flecha-acordeon {
    width: 7%;
}

.tabla-descarga {
    margin-top: 100px;
    width: 95%;
}

.tabla-descarga {
    margin-left: 5px;
    margin-top: 100px;
    width: 150%;
}



.tabla-descarga tr td a {
    color: #666666;
    font-size: 11px;
    text-decoration: none;
}

.fondo-descargas {
    background-position: -90px 0;
}
}

@media screen and (max-width: 780px) {
.fondo-descargas{
background-position:-200px 0;
}

.tabla-descarga {
    margin-top: 0;
    width: 90% !important;
}

.acorderon-atelento ul li .accordion-title a {
    text-align: left !important;
    width: 85% !important;
    font-size: 9pt!important ;
    padding-left: 0 !important;
    text-align: left !important;
}

.flecha-acordeon {
    width: 7%;
}

.module-accordion .accordion-content {
    margin-left: 1%;
    overflow: hidden;
    padding: 1em 0;
    width: 96%;
}
}

<!-- LANDING -->
.ui.gradient > li, .ui.gradient.builder_button, .ui.gradient.nav, .ui.gradient.module-callout, .ui.gradient.separate > li, .ui.gradient.module-accordion .accordion-title, .ui.gradient.window .bar, .ui.gradient, .ui.gradient.nav, .ui.gradient.nav ul, .ui.gradient.separate > li, .ui.gradient.module-tab .tab-nav li, .ui.gradient.vertical .tab-nav, .ui.gradient.window .bar, .ui.gradient.module-tab.panel .tab-nav, .ui.gradien.module-box, .ui.gradient.module-callout, .ui.gradient.module-tab.panel .tab-nav:before {
    background-image: none;
}

.sidebar-none .page-title {
    display: none;
    margin-top: 0;
    text-align: center;
}

.forma-ingreso{
    border-radius: 10px;
    overflow: hidden;
    position: absolute;
    right: 5%;
    top: 10%;
}

#layout{
background-image:none !important;
}

#codigo {
    border: 1px solid #c70401;
    color: #c70401;
    height: 23px;
    padding-bottom: 5px;
    padding-top: 5px;
}

.tabla-descarga{
     margin-top: 34px;
    width: 70%;
}

.tabla-descarga tr td a{
color:#666666;
text-decoration: none;
}

.tabla-descarga tr td a img{
  margin-bottom: 15px;
    text-align: center;
    width: 25px;

}

.fila1{
/*background:#f0f0f1;*/
}

.ui.module-accordion .accordion-title a{
color:#ffffff;
}

.ui, .ui.nav, .ui.nav ul, .ui.separate > li, .ui.module-tab .tab-nav li, .ui.vertical .tab-nav, .ui.window .bar, .ui.module-tab.panel .tab-nav, .ui.module-accordion .accordion-title, .ui.module-callout {
    background-color: transparent;
}

.ui.builder_button, .ui.nav, .ui.nav ul, .ui.nav.separate > li, .ui.module-tab .tab-nav > li, .ui.module-tab .tab-content, .ui.module-tab.panel .tab-nav, .ui.module-accordion, .ui.module-accordion > li, .ui.module-callout {
    border-style: none;
}
</style>

<!-- CAMBIO -->
</head>

<body <?php body_class(); ?>>
<?php themify_body_start(); // hook ?>
<div id="pagewrap" class="hfeed site">

	<div id="headerwrap">
    
		<?php themify_header_before(); // hook ?>

		<header id="header" class="section-inner">

        	<?php themify_header_start(); // hook ?>

			<hgroup>
				<?php echo themify_logo_image(); ?>
				<?php if ( $site_desc = get_bloginfo( 'description' ) ) : ?>
					<?php global $themify_customizer; ?>
					<div id="site-description" class="site-description"><?php echo class_exists( 'Themify_Customizer' ) ? $themify_customizer->site_description( $site_desc ) : $site_desc; ?></div>
				<?php endif; ?>
			</hgroup>

			<div class="social-widget">
				<?php dynamic_sidebar('social-widget'); ?>

				<?php if(!themify_check('setting-exclude_rss')): ?>
					<div class="rss"><a href="<?php if(themify_get('setting-custom_feed_url') != ''){ echo themify_get('setting-custom_feed_url'); } else { bloginfo('rss2_url'); } ?>">RSS</a></div>
				<?php endif ?>
			</div>
			<!-- /.social-widget -->

			<?php if(!themify_check('setting-exclude_search_form')): ?>
				<?php get_search_form(); ?>
			<?php endif ?>
	
			<nav>
				<div id="menu-icon" class="mobile-button"><span><?php _e('Menu', 'themify'); ?></span></div>
				<?php themify_theme_menu_nav(); ?>
				<!-- /#main-nav --> 
			</nav>

			<?php themify_header_end(); // hook ?>

		</header>
		<!-- /#header -->

        <?php themify_header_after(); // hook ?>
				
	</div>
	<!-- /#headerwrap -->
	
	<div id="body" class="clearfix">

		<?php themify_layout_before(); //hook ?>