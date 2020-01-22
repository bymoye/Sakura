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
		<a href="<?php the_permalink();?>"><div class="overlay"><i><svg class="paper" viewBox="0 0 40 40"><use xlink:href="#paper"></use></svg></i></div><?php the_post_thumbnail(); ?></a>
		<?php } else {?>
		<a href="<?php the_permalink();?>"><div class="overlay"><i><svg class="paper" viewBox="0 0 40 40"><use xlink:href="#paper"></use></svg></i></div><img src="<?php bloginfo('template_url'); ?>/images/random/d-<?php echo rand(1,10)?>.jpg" /></a>
		<?php } ?>
	</div>	
	<h1 class="entry-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
	<div class="p-time">
	 <?php if(is_sticky()) : ?>
			<svg class="hot"><use xlink:href="#hot"></use></svg>
		 <?php endif ?>
		 <svg class="fenlei"><use xlink:href="#time"/></svg><?php echo poi_time_since(strtotime($post->post_date_gmt));//the_time('Y-m-d');?>
	  </div>
		<p><?php echo mb_strimwidth(strip_shortcodes(strip_tags(apply_filters('the_content', $post->post_content))), 0, 150,"...");?></p>
	<footer class="entry-footer">
	<div class="post-more">
			<a href="<?php the_permalink(); ?>"><svg class="caidan"><use xlink:href="#caidan"/></svg></a>
		</div>
	<div class="info-meta">
       <div class="comnum">  
        <span><svg class="fenlei2"><use xlink:href="#message"/></svg><?php comments_popup_link('NOTHING', '1 '.__("Comment","sakura")/*条评论*/, '% '.__("Comments","sakura")/*条评论*/); ?></span>
		</div>
		<div class="views"> 
		<span><svg class="fenlei2"><use xlink:href="#eyes"/></svg><?php echo get_post_views(get_the_ID()).' '._n('Hit','Hits',get_post_views(get_the_ID()),'sakura')/*热度*/?></span>
		 </div>   
        </div>		
	</footer><!-- .entry-footer -->
	</div>	
	<hr>
</article><!-- #post-## -->

