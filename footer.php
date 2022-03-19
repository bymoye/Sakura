<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Sakura
 */

?>
	</div><!-- #content -->
	<?php 
		$site_url = $_SERVER['SERVER_NAME'];
		comments_template('', true);
	?>
	</div><!-- #page Pjax container-->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info" theme-info="Sakura_Blur v<?php echo NMX_VERSION; ?>">
			<div class="footertext">
				<p style="color: #666666;"><?php echo akina_option('footer_info', ''); ?></p>
			</div>
			<div class="footer-device">
			<p style="font-family: 'Ubuntu', sans-serif;">
					<span style="color: #b9b9b9;">
						<?php /* 能保留下面两个链接吗？算是我一个小小的心愿吧~ */ ?>
						Nazo © 2018-2022 Theme <a href="https://github.com/bymoye/Sakura/" rel="noreferrer" target="_blank" style="color: #b9b9b9;text-decoration: underline dotted rgba(0, 0, 0, .1);">Sakura</a><br>
						<?php printf(' 耗时 %.3f 秒 | 查询 %d 次 | 内存 %.2f MB',timer_stop(),get_num_queries(), memory_get_peak_usage() / 1024 / 1024);?>
				</span>
				</p>
			</div>
			<?php if (akina_option('data_query') != '0') { ?>
        <?php } ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
	</section><!-- #section -->
	<!-- m-nav-center -->
	<div id="mo-nav">
		<div class="m-avatar">
			<?php $ava = akina_option('focus_logo') ? akina_option('focus_logo') : get_template_directory_uri().'/images/avatar.jpg'; ?>
			<img src="<?php echo $ava ?>">
		</div>
		<div class="m-search">
			<form class="m-search-form" method="get" action="<?php echo home_url(); ?>" role="search">
				<input class="m-search-input" type="search" name="s" placeholder="<?php _e('Search...', 'sakura') /*搜索...*/?>" required>
			</form>
		</div>
		<?php wp_nav_menu( [ 'depth' => 2, 'theme_location' => 'primary', 'container' => false ] ); ?>
	</div><!-- m-nav-center end -->
	<button id="GoTop" title="Go to top"><i class="post_icon_svg" style="--svg-name: var(--svg_backtop);--color: #404040;--size: 16px;margin:0;"></i></button>
	<!-- search start -->
	<form class="js-search search-form search-form--modal" method="get" action="<?php echo home_url(); ?>" role="search">
		<div class="search-form__inner">
		<?php if(akina_option('live_search')){ ?>
			<div class="micro">
			<i class="post_icon_svg icon-search" style="--svg-name: var(--svg_search);--color: #666;--size: 35px;"></i>
				<input id="search-input" class="text-input" type="search" name="s" placeholder="<?php _e('Want to find something?', 'sakura') /*想要找点什么呢*/?>" required>
			</div>
			<div class="ins-section-wrapper">
                <a id="Ty" href="#"></a>
                <div class="ins-section-container" id="PostlistBox"></div>
            </div>
		<?php }else{ ?>
			<div class="micro">
				<p class="micro mb-"><?php _e('Want to find something?', 'sakura') /*想要找点什么呢*/?></p>
				<i class="post_icon_svg icon-search" style="--svg-name: var(--svg_search);--color: #666;--size: 35px;"></i>
				<input class="text-input" type="search" name="s" placeholder="<?php _e('Search', 'sakura') ?>" required />
			</div>
		<?php } ?>
		</div>
		<div class="search_close"></div>
	</form>
	<!-- search end -->
	<?php if(akina_option('google_analytics_id', '')):?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo akina_option('google_analytics_id', ''); ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config','<?php echo akina_option('google_analytics_id', ''); ?>');</script>
<?php endif; ?>
<?php wp_footer(); ?>
<?php if(akina_option('site_statistics')){ ?>
<div class="site-statistics">
<script type="text/javascript"><?php echo akina_option('site_statistics'); ?></script>
</div>
<?php } ?>
<?php if (akina_option('sakura_widget')) : ?>
	<aside id="secondary" class="widget-area" role="complementary" style="left: -400px;">
    <div class="heading"><?php _e('Widgets') /*小工具*/ ?></div>
    <div class="sakura_widget">
	</div>
	<div class="show-hide-wrap"><button class="show-hide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M22 16l-10.105-10.6-1.895 1.987 8.211 8.613-8.211 8.612 1.895 1.988 8.211-8.613z"></path></svg></button></div>
    </aside>
<?php endif; ?>
</body>
</html>
