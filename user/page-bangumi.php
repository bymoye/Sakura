<?php

/**
 Template Name: Bangumi
 */
get_header(); 
?>
<meta name="referrer" content="same-origin">
<style>
#content,.comments,.site-footer{max-width:1200px;}
.comments{display: none}
.pattern-center{max-width: 1200px;}
</style>
</head>

<?php while(have_posts()) : the_post(); ?>
	<article <?php post_class("post-item"); ?>>
		<?php the_content(); ?>
			<section class="bangumi">
            <?php if (akina_option('bilibili_id') ):?>
                <div class="row">
                <?php
                $bgm = new \Sakura\API\Bilibili();
                echo $bgm->get_bgm_items(); 
            ?>
            <?php else: ?>
                <div class="row">
                    <p> <?php _e("Please fill in the Bilibili UID in Sakura Options.","sakura"); ?></p>
                </div>
            <?php endif; ?>
            </section>
	</article>
<?php endwhile; ?>

<?php
get_footer();