<?php 

	/**
	 * DISQUS COMMENTS
	 */

?>

<?php if (!is_home()&&(comments_open()) ){ ?>
	<section class="duoshuowrapper comments">
		<div class="commentwrap comments-hidden">
			<div class="notification"><i class="post_icon_svg" style="--svg-name: var(--svg_message);--color: #000;--size: 14px;"></i><?php _e('View comments', 'sakura'); /*查看评论*/?></div>
		</div>
		<div class="comments-main">
			<div class="commentwrap">
				<?php comments_template('', true); ?>
			</div>
		</div>
	</section>
	<?php } ?>
