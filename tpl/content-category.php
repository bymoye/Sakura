<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

?>

<article class="post works-list" itemscope="" itemtype="http://schema.org/BlogPosting">
<div class="works-entry">
<div class="works-main">
<div class="works-feature">
	<?php if ( has_post_thumbnail() ) { ?>
		<a href="<?php the_permalink();?>"><?php the_post_thumbnail('large'); ?></a>
		<?php } else {?>
		<?php $feature_image = DEFAULT_FEATURE_IMAGE();
		if (is_array($feature_image)){$feature_img=$feature_image['th'];}else{$feature_img=$feature_image;}?>
		<a href="<?php the_permalink();?>"><img src="<?php echo $feature_img; ?>" /></a>
		<?php } ?>
	</div>
	
 	<div class="works-overlay">
	<h1 class="works-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
	<div class="works-p-time">		
	<em class='post_icon_svg' style='--svg-name: var(--svg_calendar);--size: 17px;--color:white;vertical-align: text-top;margin:0'></em> <?php echo poi_time_since(strtotime($post->post_date_gmt));//the_time('Y-m-d');?>
	  </div>
	<div class="works-meta">
       <div class="works-comnum">  
        <span><i class="post_icon_svg" style="--svg-name: var(--svg_message);--size: 16px;--color: #000;"></i> <?php comments_popup_link(__('NOTHING','sakura'), '1 ', '% '); /*暂无*/?></span>
		</div>
		<div class="works-views"> 
		<span><i class="post_icon_svg" style="--svg-name: var(--svg_eyes);--size: 16px;--color: #000;"></i> <?php echo get_post_views(get_the_ID()); ?> </span>
		 </div>   
        </div>
		<a class="worksmore" href="<?php the_permalink(); ?>"></a>
     </div>		
	<!-- .entry-footer -->
	</div>	
	</div>	
	
</article><!-- #post-## -->
