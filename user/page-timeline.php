<?php 

/**
 Template Name: 时光轴
 */

get_header();

#error_reporting(E_ALL);
#ini_set('display_errors', '1');
?>
   	<div id="main">
        <div id="main-part">
			<?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
            <article class="art">
                <div class="art-main">
                    <div class="art-content">
                        <?php if ( has_post_thumbnail() ) {
							the_post_thumbnail();
						}
						the_content();
						memory_archives_list();
						?>
					</div>
				</div>
			</article>
			<?php endif; ?>
        </div>
    </div>
<?php get_footer(); 
