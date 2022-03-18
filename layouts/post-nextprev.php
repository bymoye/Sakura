<?php 

/**
 * NEXT / PREVIOUS POSTS (???)
 */

if ( akina_option('post_nepre') == 'yes') {
?>
<section class="post-squares nextprev">
	<div class="post-nepre <?=get_next_post() ? 'half' : 'full'?> previous">
	<?php 
	$prev_img = get_prev_thumbnail_url();
	$prev_lazyload = $prev_img['md'] ?? 'https://jsd.nmxc.ltd/gh/moezx/cdn@3.0.1/img/svg/loader/orange.progress-bar-stripe-loader.svg';
	$prev_img_url = $prev_img['th'] ?? $prev_img;
	?>
	<?php previous_post_link('%link','<div class="background lazyload" style="background-image:url('.$prev_lazyload.');" data-src="'.$prev_img_url.'"></div><span class="label">Previous Post</span><div class="info"><h3>%title</h3><hr></div>') ?>
	</div>
	<div class="post-nepre <?=get_previous_post() ? 'half' : 'full'?> next">
	<?php
	$next_img = get_next_thumbnail_url();
	$next_lazyload = $next_img['md'] ?? 'https://jsd.nmxc.ltd/gh/moezx/cdn@3.0.1/img/svg/loader/orange.progress-bar-stripe-loader.svg';
	$next_img_url = $next_img['th'] ?? $next_img;
	?>
	<?php next_post_link('%link','<div class="background lazyload" style="background-image:url('.$next_lazyload.');" data-src="'.$next_img_url.'"></div><span class="label">Next Post</span><div class="info"><h3>%title</h3><hr></div>') ?>
	</div>
</section>
<?php } ?>