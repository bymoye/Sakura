<?php 

/**
 Template Name: archive
 */

get_header(); 

?>

	<?php while(have_posts()) : the_post(); ?>
	
	<article <?php post_class("post-item"); ?>>
		<?php the_content(); ?>
		<div id="archives-temp">
    <div id="archives-content">      
    <?php       
        $the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1' );      
        $year=0; $mon=0; $i=0; $j=0;      
        $all = array();      
        $output = '';      
        while ( $the_query->have_posts() ) : $the_query->the_post();      
            $year_tmp = get_the_time('Y');      
            $mon_tmp = get_the_time('n');      
            $y=$year; $m=$mon;      
            if ($mon != $mon_tmp && $mon > 0) $output .= '</div></div>';      
            if ($year != $year_tmp) { // 输出年份      
                $year = $year_tmp;      
                $all[$year] = array();      
            }      
            if ($mon != $mon_tmp) { // 输出月份      
                $mon = $mon_tmp;     
                array_push($all[$year], $mon);      
                $output .= "<div class='archive-title' id='arti-$year-$mon'><span class='ar-time'><i><em class='post_icon_svg' style='--svg-name: var(--svg_calendar);--size: 17px;--color:white;vertical-align: text-top;margin:0'></em></i></span><h3>$year-$mon</h3><div class='archives archives-$mon' id='monlist' data-date='$year-$mon'>";      
            }      
            $output .= '<span class="ar-circle"></span><div class="arrow-left-ar"></div><div class="brick"><a href="'.get_permalink() .'"><span class="time"><i class="post_icon_svg" style="--svg-name: var(--svg_time);"></i>'.get_the_time('n-d').'</span>'.get_the_title() .'<em>('. get_comments_number('0', '1', '%') .')</em></a></div>';      
        endwhile;      
        wp_reset_postdata();      
        $output .= '</div></div>';      
        echo $output;  		         
    ?>   
		</article>
	<?php endwhile; ?>
	
<?php get_footer(); ?>


