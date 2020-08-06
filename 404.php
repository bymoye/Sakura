<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Akina
 */

 ?>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php global $page, $paged;wp_title( '-', true, 'right' );
bloginfo( 'name' );$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) ) echo " - $site_description";if ( $paged >= 2 || $page >= 2 ) echo ' - ' . sprintf( __( 'page %s'), max( $paged, $page ) );/*第 %s 页*/?>
</title>

<?php wp_head(); ?>
<script>
var getsvg = new XMLHttpRequest();
getsvg.open("GET", "https://cdn.jsdelivr.net/gh/bymoye/sakura@0.0.3/images/icon.svg", true);
getsvg.onload = function(e) {
    document.body.insertAdjacentHTML("beforeEnd", '<div style="display:none;">' + getsvg.responseText + '</div>');
}
getsvg.send();
var the_dom="<?php echo str_replace("http://", "", str_replace("https://", "", get_site_url())); ?>";
</script>
</head>
<body <?php body_class(); ?>>
<section class="error-404 not-found">
<div class="error-img">
<div class="anim-icon" id="404" style="height: 66%;"></div>
</div>
<div class="err-button back">
<a id="golast" href=javascript:history.go(-1);><?php _e('return to previous page','sakura');/*返回上一页*/?></a>
<a id="gohome" href="<?php bloginfo('url');?>"><?php _e('return to home page','sakura');/*返回主页*/?></a>  
</div>
<div style="display:block; width:284px;margin: auto;">
<p style="margin-bottom: 1em;margin-top: 1.5em;text-align: center;font-size: 15px;"><?php _e('Don\'t worry, search in site?','sakura');/*别急，试试站内搜索？*/?></p>
<form class="s-search" method="get" action="/" role="search">
<svg class="search icon-search" viewBox="0 0 40 40" style="position: absolute;width: 24px; bottom: 8px;left: 12px; fill: #ddd;stroke:#ddd ;stroke-miterlimit:10;stroke-width:40px;";><use xlink:href="#search"/></svg>
    <input class="text-input" style="padding: 8px 20px 8px 46px;" type="search" name="s" placeholder="<?php _e('Search...', 'akina') ?>" required>	
</form>
</div>
</section>
<script src="https://cdn.jsdelivr.net/gh/bymoye/cdn@1.1/sakura/js/other/404.min.js" type="text/javascript"></script>
</body>
