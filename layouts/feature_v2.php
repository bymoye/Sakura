<?php 

	/**
	 * Top Features virsion 2
     * Saurce: https://github.com/gudh/ihover
	 */

?>
<div class="top-feature-row">
    <h1 class="fes-title" style="font-family: 'Ubuntu', sans-serif;"><i class="post_icon_svg" style="--svg-name: var(--svg_anchor);--color: #666;--size: 16px;vertical-align: -0.1em;margin:0;"></i> <?php echo akina_option('feature_title'); ?></h1>
    <div class="top-feature-v2">
        <div class="the-feature square from_left_and_right">
            <a href="<?php echo akina_option('feature1_link', '#'); ?>" target="_blank">
                <div class="img"><img src="<?php echo akina_option('feature1_img', ''); ?>" alt="Discovery1"></div>
                <div class="info">
                    <h3><?php echo akina_option('feature1_title', 'feature1'); ?></h3>
                    <p><?php echo akina_option('feature1_description', 'feature1'); ?></p>
                </div>
            </a>
        </div>
    </div>
    <div class="top-feature-v2">
        <div class="the-feature square from_left_and_right" style="margin: 0 4.5px">
            <a href="<?php echo akina_option('feature2_link', '#'); ?>" target="_blank">
                <div class="img"><img src="<?php echo akina_option('feature2_img', ''); ?>" alt="Discovery2"></div>
                <div class="info">
                    <h3><?php echo akina_option('feature2_title', 'feature2'); ?></h3>
                    <p><?php echo akina_option('feature2_description', 'feature2'); ?></p>
                </div>
            </a>
        </div>
    </div>
    <div class="top-feature-v2">
        <div class="the-feature square from_left_and_right">
            <a href="<?php echo akina_option('feature3_link', '#'); ?>" target="_blank">
                <div class="img"><img src="<?php echo akina_option('feature3_img', ''); ?>" alt="Discovery3"></div>
                <div class="info">
                    <h3><?php echo akina_option('feature3_title', 'feature3'); ?></h3>
                    <p><?php echo akina_option('feature3_description', 'feature3'); ?></p>
                </div>
            </a>
        </div>
    </div>
</div>
