<?php 

	/**
	 * DISQUS COMMENTS
	 */

?>

<?php if (!is_home()&&(comments_open()) ){ ?>
	<section class="duoshuowrapper comments">
		<div class="commentwrap comments-hidden">
			<div class="notification"><svg class="message"><use xlink:href="#svg_message"></use></svg><?php _e('View comments', 'sakura'); /*查看评论*/?></div>
		</div>
		<div class="comments-main">
			<div class="commentwrap">
				<?php comments_template('', true); ?>
			</div>
		</div>
	</section>
	<?php } ?>
