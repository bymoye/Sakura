<?php 

	/**
	 * DISQUS COMMENTS
	 */

?>
	<div class="top-feature">
		<h1 class="fes-title" style="font-family: 'Ubuntu', sans-serif;"><svg class="pinna" aria-hidden="true"><use xlink:href="#svg_anchor"></use></svg> <?php echo akina_option('feature_title', '蝚焦'); ?></h1>
		<div class="feature-content">
			<li class="feature-1">
				<a href="<?php echo akina_option('feature1_link', '#'); ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo akina_option('feature1_title', 'feature1'); ?></span></div><img class="lazyload" src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.1/img/svg/loader/orange.progress-bar-stripe-loader.svg" data-src="<?php echo akina_option('feature1_img', ''); ?>"></a>
			</li>
			<li class="feature-2">
				<a href="<?php echo akina_option('feature2_link', '#'); ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo akina_option('feature2_title', 'feature2'); ?></span></div><img src="<?php echo akina_option('feature2_img', ''); ?>"></a>
			</li>
			<li class="feature-3">
				<a href="<?php echo akina_option('feature3_link', '#'); ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo akina_option('feature3_title', 'feature3'); ?></span></div><img src="<?php echo akina_option('feature3_img', ''); ?>"></a>
			</li>
		</div>
	</div>
