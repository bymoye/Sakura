<?php 

	/**
	 * DISQUS COMMENTS
	 */

?>
	<div class="top-feature">
		<h1 class="fes-title" style="font-family: 'Ubuntu', sans-serif;"><i class="post_icon_svg" style="--svg-name: var(--svg_anchor);--color: #666;--size: 16px;vertical-align: -0.1em;margin:0;"></i> <?php echo akina_option('feature_title'); ?></h1>
		<div class="feature-content">
			<li class="feature-1">
				<a href="<?php echo akina_option('feature1_link', '#'); ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo akina_option('feature1_title', 'feature1'); ?></span></div><img class="lazyload" src="https://jsd.nmxc.ltd/gh/moezx/cdn@3.0.1/img/svg/loader/orange.progress-bar-stripe-loader.svg" data-src="<?php echo akina_option('feature1_img', ''); ?>"></a>
			</li>
			<li class="feature-2">
				<a href="<?php echo akina_option('feature2_link', '#'); ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo akina_option('feature2_title', 'feature2'); ?></span></div><img src="<?php echo akina_option('feature2_img', ''); ?>"></a>
			</li>
			<li class="feature-3">
				<a href="<?php echo akina_option('feature3_link', '#'); ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo akina_option('feature3_title', 'feature3'); ?></span></div><img src="<?php echo akina_option('feature3_img', ''); ?>"></a>
			</li>
		</div>
	</div>
