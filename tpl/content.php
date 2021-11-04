<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

?>
	<article class="post post-list" itemscope="" itemtype="http://schema.org/BlogPosting">
	<div class="post-entry">
	  <div class="feature">
		<?php if ( has_post_thumbnail() ) { ?>
			<a href="<?php the_permalink();?>"><div class="overlay"><i><em class="post_icon_svg svg_paper"></em></i></div><?php the_post_thumbnail(); ?></a>
			<?php } else {?>
			<a href="<?php the_permalink();?>"><div class="overlay"><i><em class="post_icon_svg svg_paper"></em></i></div><img src="<?php bloginfo('template_url'); ?>/images/random/d-<?php echo rand(1,10)?>.jpg" /></a>
			<?php } ?>
		</div>	
		<h1 class="entry-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
		<div class="p-time">
		<?php if(is_sticky()) : ?>
			<i class="post_icon_svg svg_hot"></i>
		 <?php endif ?>
		 <i class="post_icon_svg svg_time"></i><?php echo poi_time_since(strtotime($post->post_date_gmt)); ?>
	  	</div>
		<?php the_excerpt(); ?>
		<footer class="entry-footer">
		<div class="post-more">
			<a href="<?php the_permalink(); ?>"><i class='post_icon_svg svg_caidan'></i></a>
		</div>
		<div class="info-meta">
	       <div class="comnum">  
	        <span><i class="post_icon_svg svg_message"></i><?php comments_popup_link('NOTHING', '1 '.__("Comment","sakura")/*条评论*/, '% '.__("Comments","sakura")/*条评论*/); ?></span>
			</div>
			<div class="views"> 
			<span><i class="post_icon_svg svg_eyes"></i><?php echo get_post_views(get_the_ID()).' '._n('Hit','Hits',get_post_views(get_the_ID()),'sakura')/*热度*/?></span>
			 </div>   
	    </div>		
		</footer><!-- .entry-footer -->
		</div>	
	<hr>
	</article><!-- #post-## -->