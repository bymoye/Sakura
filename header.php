<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Akina
 */
?>
<?php header('X-Frame-Options: SAMEORIGIN');
$nonce = wp_create_nonce('wp_rest');
$self = "default-src 'self' *.nmxc.ltd cdn.jsdelivr.net 'strict-dynamic'; script-src 'strict-dynamic' 'nonce-{$nonce}'; style-src 'strict-dynamic' 'nonce-{$nonce}'; img-src *.nmxc.ltd fp1.fghrsh.net cdn.jsdelivr.net sdn.geekzu.org 'self' data:;";
header('Content-Security-Policy: '.$self); 
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta  name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php global $page, $paged;wp_title( '-', true, 'right' );
bloginfo( 'name' );$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) ) echo " - $site_description";if ( $paged >= 2 || $page >= 2 ) echo ' - ' . sprintf( __( 'page %s ','sakura'), max( $paged, $page ) );/*第 %s 页*/?>
</title>
<?php
	$site_url = $_SERVER['SERVER_NAME'];
if (akina_option('akina_meta') == true) {
	$keywords = '';
	$description = '';
	if ( is_singular() ) {
		$keywords = '';
		$tags = get_the_tags();
		$categories = get_the_category();
		if ($tags) {
			foreach($tags as $tag) {
				$keywords .= $tag->name . ','; 
			}
        }
        if ($categories) {
			foreach($categories as $category) {
				$keywords .= $category->name . ','; 
			}
        }
        $description = mb_strimwidth( str_replace("\r\n", '', strip_tags($post->post_content)), 0, 240, '…');
	} else {
		$keywords = akina_option('akina_meta_keywords');
		$description = akina_option('akina_meta_description');
	}
    ?>
<meta name="description" content="<?php echo $description; ?>" />
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<link rel="shortcut icon" href="<?php echo akina_option('favicon_link', ''); ?>"/> 
<meta name="theme-color" content="<?php echo akina_option('theme_skin', ''); ?>">
<meta http-equiv="x-dns-prefetch-control" content="on">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<svg id="background">
		<image href="<?php $img = get_random_bg_url();echo is_array($img) ? $img['large'] : $img; ?>" x="-5" y="-5" height="102%" width="102%" preserveAspectRatio="xMidYMid slice"></image>
	</svg>
    <div class="scrollbar" id="bar"></div>
	<div class="openNav no-select">
		<div class="iconflat no-select">	 
			<div class="icon"></div>
		</div>
		<div class="site-branding">
			<?php if (akina_option('akina_logo')){ ?>
			<div class="site-title"><a href="<?php bloginfo('url');?>"><img src="<?php echo akina_option('akina_logo'); ?>"></a></div>
			<?php }elseif ($site_url == "localhost" or $site_url == "nmxc.ltd"){ ?>
				<div class="site-title"><a href="<?php bloginfo('url'); ?>">
				<i class="nmxc_logo sitelogo-mi"></i>
				<i class="nmxc_logo sitelogo-de"></i>
				<i class="nmxc_logo sitelogo-ni"></i>
				<i class="nmxc_logo sitelogo-ming"></i>
				<i class="nmxc_logo sitelogo-xin"></i>
				</a></div>
			<?php }else{ ?>
			<h1 class="site-title"><a href="<?php bloginfo('url');?>" ><?php bloginfo('name');?></a></h1>
			<?php } ?>
		</div>
	</div><!-- m-nav-bar -->
	<section id="main-container">
		<?php 
		if(!akina_option('head_focus')){ 
		?>
		<div class="headertop">
			<?php get_template_part('layouts/imgbox'); ?>
		</div>	
		<?php } ?>
		<div id="page" class="site wrapper">
			<header class="site-header no-select" role="banner">
				<div class="site-top">
					<div class="site-branding">
						<?php if (akina_option('akina_logo')){ ?>
						<div class="site-title">
							<a href="<?php bloginfo('url');?>" ><img src="<?php echo akina_option('akina_logo'); ?>"></a>
						</div>
						<?php }elseif($site_url == 'localhost' or $site_url == 'nmxc.ltd'){ ?>
							<div class="site-title">
							<a href="<?php bloginfo('url');?>">
							<i class="nmxc_logo sitelogo-mi"></i>
							<i class="nmxc_logo sitelogo-de"></i>
							<i class="nmxc_logo sitelogo-ni"></i>
							<i class="nmxc_logo sitelogo-ming"></i>
							<i class="nmxc_logo sitelogo-xin"></i>
							</a>
						</div>
						<?php }else{ ?>
						<span class="site-title">
							<span class="logolink serif">
								<a href="<?php bloginfo('url');?>">
									<span class="site-name"><?php echo akina_option('site_name', ''); ?></span>
								</a>
							</span>
						</span>	
						<?php } ?><!-- logo end -->
					</div><!-- .site-branding -->
					<?php header_user_menu(); if(akina_option('top_search') == 'yes') { ?>
					<div class="searchbox"><i class="post_icon_svg search js-toggle-search"></i></div>
					<?php } ?>
					<div class="lower"><?php if(!akina_option('shownav')){ ?>
						<div id="show-nav" class="showNav">
							<div class="line line1"></div>
							<div class="line line2"></div>
							<div class="line line3"></div>
						</div><?php } ?>
						<nav><?php wp_nav_menu( array( 'depth' => 2, 'theme_location' => 'primary', 'container' => false ) ); ?></nav><!-- #site-navigation -->
					</div>	
				</div>
			</header><!-- #masthead -->
			<?php if (get_post_meta(get_the_ID(), 'cover_type', true) == 'hls') {
                the_video_headPattern_hls();
            } elseif (get_post_meta(get_the_ID(), 'cover_type', true) == 'normal') { 
                the_video_headPattern_normal();
            }else {
                the_headPattern();
            } ?>
		    <div id="content" class="site-content">
