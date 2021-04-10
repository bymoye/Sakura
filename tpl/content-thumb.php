<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */
//function custom_short_excerpt($excerpt){
//	return substr($excerpt, 0, 120);
//}
//add_filter('the_excerpt', 'custom_short_excerpt');
$i=0; while ( have_posts() ) : the_post(); $i++;
    $class = match (akina_option('feature_align')) {
        "left" => 'post-list-thumb-left',
        "right" => '',
        "alternate" => ($i % 2 == 0) ? 'post-list-thumb-left' : '',
        default => ($i % 2 == 0) ? 'post-list-thumb-left' : '',
    };
if( $i == 1 ){
    $class .= ' post-list-show';
}
if(has_post_thumbnail()){
	$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
	$post_img = !empty($large_image_url) ? $large_image_url[0] : '';
}else{
	$post_img = DEFAULT_FEATURE_IMAGE();
}
$the_cat = get_the_category();
// 摘要字数限制

//add_filter( 'excerpt_length', 'custom_excerpt_length', 120 );
?>
	<article class="post post-list-thumb <?php echo $class; ?>" itemscope="" itemtype="http://schema.org/BlogPosting">
		<div class="post-thumb">
		<?php if(is_array($post_img)){$post_img_lazyload = $post_img[0];$post_img_url = $post_img[1];}else{$post_img_lazyload = $post_img;$post_img_url = $post_img;}?>
			<a href="<?php the_permalink(); ?>"><img class="lazyload" src="<?php echo $post_img_lazyload?>" data-src="<?php echo $post_img_url ?>"></a>
		</div><!-- thumbnail-->
		<div class="post-content-wrap">
			<div class="post-content">
				<div class="post-date">
				<i class="post_icon_svg" style="--svg-name: var(--svg_time);"></i><?php echo poi_time_since(strtotime($post->post_date_gmt)); ?>
					<?php if(is_sticky()) : ?>
					&nbsp;<i class="post_icon_svg" style="--svg-name: var(--svg_hot);--color: #FF3B00;--size: 14px;"></i>
			 		<?php endif ?>
				</div>
				<a href="<?php the_permalink(); ?>" class="post-title"><h3><?php the_title();?></h3></a>
				<div class="post-meta">
					<span><i class="post_icon_svg" style="--svg-name: var(--svg_eyes);"></i><?php echo get_post_views(get_the_ID()).' '._n('Hit','Hits',get_post_views(get_the_ID()),'sakura')/*热度*/?></span>
					<span class="comments-number"><i class="post_icon_svg" style="--svg-name: var(--svg_message);"></i></svg><?php comments_popup_link('NOTHING', '1 '.__("Comment","sakura")/*条评论*/, '% '.__("Comments","sakura")/*条评论*/); ?></span>
					<span><i class="post_icon_svg" style="--svg-name: var(--svg_fenlei);"></i><a href="<?php echo esc_url(get_category_link($the_cat[0]->cat_ID)); ?>"><?php echo $the_cat[0]->cat_name; ?></a>
					</span>
				</div>
				<div class="float-content">
					<?php substr(the_excerpt() , 0 , 3); ?>
					<div class="post-bottom">
						<a href="<?php the_permalink(); ?>" class="button-normal"><i class="post_icon_svg" style="--svg-name: var(--svg_caidan);--color: #666;--size: 25px;"></i></a>
					</div>
				</div>
			</div>
		</div>
	</article>
<?php
endwhile; 