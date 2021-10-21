<?php
/**
 * Sakura functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Sakura
 */


const SAKURA_VERSION = '3.3.2';
const NMX_VERSION = '1.3.3';
const BUILD_VERSION = '3';

//error_reporting(E_ALL);   

if ( !function_exists('akina_setup')){
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
 
if ( !function_exists('optionsframework_init')) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
	require_once __DIR__ . '/inc/options-framework.php';
}

function akina_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Akina, use a find and replace
	 * to change 'akina' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('sakura', get_template_directory() . '/languages');


	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 150, 150, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( [
		'primary' => __( 'Nav Menus', 'sakura' ), //导航菜单
     ] );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
     ] );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', [
		'aside',
		'image',
		'status',
     ] );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'akina_custom_background_args',[
		'default-color' => 'ffffff',
		'default-image' => '',
     ] ) );
	
	add_filter('pre_option_link_manager_enabled','__return_true');
	
	// 优化代码
	//去除头部冗余代码
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'start_post_rel_link' );
    remove_action('wp_head', 'wp_generator');
    remove_filter('the_content', 'wptexturize'); //取消标点符号转义
    
	//remove_action('rest_api_init', 'wp_oembed_register_route');
	//remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);
	//remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
	//remove_filter('oembed_response_data', 'get_oembed_response_data_rich', 10, 4);
	//remove_action('wp_head', 'wp_oembed_add_discovery_links');
	//remove_action('wp_head', 'wp_oembed_add_host_js');
	remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
	
	function coolwp_remove_open_sans_from_wp_core() {
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', false );
		wp_enqueue_style('open-sans');
	}
	add_action( 'init', 'coolwp_remove_open_sans_from_wp_core' );
	
	/**
	* Disable the emoji's
	*/
	function disable_emojis() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	}
	add_action( 'init', 'disable_emojis' );
	 
	/**
	 * Filter function used to remove the tinymce emoji plugin.
	 * 
	 * @param    array  $plugins  
	 * @return   array             Difference betwen the two arrays
	 */
	function disable_emojis_tinymce( $plugins ) {
	 if ( is_array( $plugins ) ) {
	 return array_diff( $plugins, [ 'wpemoji' ] );
	 } else {
	 return [];
	 }
	}
	
	// 移除菜单冗余代码
	add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
	add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
	add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
	function my_css_attributes_filter($var) {
	return is_array($var) ? array_intersect($var, ['current-menu-item','current-post-ancestor','current-menu-ancestor','current-menu-parent']) : '';
	}
		
}
}
add_action( 'after_setup_theme', 'akina_setup' );

function admin_lettering(){
    echo'<style>body{font-family: Microsoft YaHei;}</style>';
}
add_action('admin_head', 'admin_lettering');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function akina_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'akina_content_width', 640 );
}
add_action( 'after_setup_theme', 'akina_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function sakura_scripts() {
    if(akina_option('jsdelivr_cdn_test')){ 
        wp_enqueue_script( 'js_lib', get_template_directory_uri() . '/cdn/js/lib.js', array(), NMX_VERSION.akina_option('cookie_version', ''), true );
    } else { 
        wp_enqueue_script( 'js_lib', 'https://cdn.jsdelivr.net/gh/bymoye/Sakura@' . NMX_VERSION . '/cdn/js/lib.min.js', array(), NMX_VERSION, true );
    }
    if (akina_option('app_no_jsdelivr_cdn')) {
        wp_enqueue_style( 'icon_css', get_template_directory_uri() . '/cdn/css/icon.css', array(), NMX_VERSION );
        wp_enqueue_style( 'site_css', get_template_directory_uri() . '/cdn/css/sitelogo.css', array(), NMX_VERSION );
        wp_enqueue_style( 'sakura_css', get_stylesheet_uri(), array(), NMX_VERSION );
        wp_enqueue_script( 'app', get_template_directory_uri() . '/js/sakura-app.js', array(), NMX_VERSION, true );
    } else {
        wp_enqueue_style( 'sakura_css', 'https://cdn.jsdelivr.net/combine/gh/bymoye/Sakura@' . NMX_VERSION . '/style.min.css,gh/bymoye/Sakura/cdn/css/icon.min.css,gh/bymoye/Sakura/cdn/css/sitelogo.min.css', array(), NMX_VERSION );
        wp_enqueue_script( 'app', 'https://cdn.jsdelivr.net/combine/gh/bymoye/Sakura@' . NMX_VERSION . '/js/sakura-app.min.js,gh/bymoye/cdn@1.1.5/sakura/cdn/js/widget.min.js', array(), NMX_VERSION, true );
    } 

    
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// 20161116 @Louie
	$mv_live = akina_option('focus_mvlive') ? 'open' : 'close';
	$movies = akina_option('focus_amv') ? array('url' => akina_option('amv_url'), 'name' => akina_option('amv_title'), 'live' => $mv_live) : 'close';
	$auto_height = akina_option('focus_height') ? 'fixed' : 'auto';
	$code_lamp = 'close';
	//if(wp_is_mobile()) $auto_height = 'fixed'; //拦截移动端
    $reply_link_version = version_compare( $GLOBALS['wp_version'], '5.1', '>=' ) ? 'new' : 'old';
	wp_localize_script('app', 'Poi' , array(
		'pjax' => akina_option('poi_pjax'),
		'movies' => $movies,
		'windowheight' => $auto_height,
		'codelamp' => $code_lamp,
		'ajaxurl' => admin_url('admin-ajax.php'),
		'order' => get_option('comment_order'), // ajax comments
		'formpostion' => 'bottom', // ajax comments 默认为bottom，如果你的表单在顶部则设置为top。
        'reply_link_version' => $reply_link_version,
        'api' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'google_analytics_id' => akina_option('google_analytics_id', '')
	));
}
add_action('wp_enqueue_scripts', 'sakura_scripts');

/**
 * load .php.
 */
require get_template_directory() .'/inc/decorate.php';
require get_template_directory() .'/inc/swicher.php';
require get_template_directory() .'/inc/api.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
// require get_template_directory() . '/inc/customizer.php';

/**
 * function update
 */
require get_template_directory() . '/inc/theme_plus.php';
require get_template_directory() . '/inc/categories-images.php';
use Sakura\API\bgapi;
//Comment Location Start
function convertip($ip)
{
    //error_reporting(E_ALL ^ E_NOTICE);
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
        $file_contents = file_get_contents('https://ip.taobao.com/outGetIpInfo?accessKey=alibaba-inc&ip=' .$ip);
        $result = json_decode($file_contents,true);
        if ($result['data']['country'] != '中国') {
            return $result['data']['country'];
        } else {
            return $result['data']['region'].'&nbsp;·&nbsp;'.$result['data']['city'].'&nbsp;·&nbsp;'.$result['data']['isp'];
        }
    } else {
        $dat_path = dirname(__FILE__) . '/inc/QQWry.Dat';
        if (!$fd = @fopen($dat_path, 'rb')) {
            return 'IP date file not exists or access denied';
        }
        $ip = explode('.', $ip);
        if($ip[0]=='')return false;
        $ipNum = intval($ip[0]) * 16777216 + intval($ip[1]) * 65536 + intval($ip[2]) * 256 + intval($ip[3]);
        $DataBegin = fread($fd, 4);
        $DataEnd = fread($fd, 4);
        $ipbegin = implode('', unpack('L', $DataBegin));
        if ($ipbegin < 0) {
            $ipbegin += pow(2, 32);
        }

        $ipend = implode('', unpack('L', $DataEnd));
        if ($ipend < 0) {
            $ipend += pow(2, 32);
        }

        $ipAllNum = ($ipend - $ipbegin) / 7 + 1;
        $BeginNum = 0;
        $ip1num = $ip2num = $ipAddr1 = $ipAddr2 = '';
        $EndNum = $ipAllNum;
        while ($ip1num > $ipNum || $ip2num < $ipNum) {
            $Middle = intval(($EndNum + $BeginNum) / 2);
            fseek($fd, $ipbegin + 7 * $Middle);
            $ipData1 = fread($fd, 4);
            if (strlen($ipData1) < 4) {
                fclose($fd);
                return 'System Error';
            }
            $ip1num = implode('', unpack('L', $ipData1));
            if ($ip1num < 0) {
                $ip1num += pow(2, 32);
            }

            if ($ip1num > $ipNum) {
                $EndNum = $Middle;
                continue;
            }
            $DataSeek = fread($fd, 3);
            if (strlen($DataSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
            fseek($fd, $DataSeek);
            $ipData2 = fread($fd, 4);
            if (strlen($ipData2) < 4) {
                fclose($fd);
                return 'System Error';
            }
            $ip2num = implode('', unpack('L', $ipData2));
            if ($ip2num < 0) {
                $ip2num += pow(2, 32);
            }

            if ($ip2num < $ipNum) {
                if ($Middle == $BeginNum) {
                    fclose($fd);
                    return 'Unknown';
                }
                $BeginNum = $Middle;
            }
        }
        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(1)) {
            $ipSeek = fread($fd, 3);
            if (strlen($ipSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
            fseek($fd, $ipSeek);
            $ipFlag = fread($fd, 1);
        }
        if ($ipFlag == chr(2)) {
            $AddrSeek = fread($fd, 3);
            if (strlen($AddrSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return 'System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, -1, SEEK_CUR);
            }
            while (($char = fread($fd, 1)) != chr(0)) {
                $ipAddr2 .= $char;
            }

            $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
            fseek($fd, $AddrSeek);
            while (($char = fread($fd, 1)) != chr(0)) {
                $ipAddr1 .= $char;
            }

        } else {
            fseek($fd, -1, SEEK_CUR);
            while (($char = fread($fd, 1)) != chr(0)) {
                $ipAddr1 .= $char;
            }

            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return 'System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, -1, SEEK_CUR);
            }
            while (($char = fread($fd, 1)) != chr(0)) {
                $ipAddr2 .= $char;
            }
        }
        fclose($fd);
        if (preg_match('/http/i', $ipAddr2)) {
            $ipAddr2 = '';
        }
        $ipaddr = "$ipAddr1 $ipAddr2";
        $ipaddr = preg_replace('/CZ88.Net/is', '', $ipaddr);
        $ipaddr = preg_replace('/^s*/is', '', $ipaddr);
        $ipaddr = preg_replace('/s*$/is', '', $ipaddr);
        if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
            $ipaddr = 'Unknown';
        }
        $ipaddr = iconv('gbk', 'utf-8//IGNORE', $ipaddr);
        if ($ipaddr != '  ') {
            return $ipaddr;
        } else {
            $ipaddr = 'Unknown';
        }

        return $ipaddr;
    }
}
//Comment Location End

/**
 * COMMENT FORMATTING
 *
 * 标准的 lazyload 输出头像
 * <?php echo str_replace( 'src=', 'src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.1/img/svg/loader/index.ajax-spinner-preloader.svg" onerror="imgError(this,1)" data-src=', get_avatar( $comment->comment_author_email, '80', '', get_comment_author(), array( 'class' => array( 'lazyload' ) ) ) ); ?>
 *
 * 如果不延时是这样的
 * <?php echo get_avatar( $comment->comment_author_email, '80', '', get_comment_author() ); ?>
 *
 */
if(!function_exists('akina_comment_format')){
	function akina_comment_format($comment, $args, $depth){
        $GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class(); ?> id="comment-<?php echo esc_attr(get_comment_ID()); ?>">
			<div class="contents">
				<div class="comment-arrow">
					<div class="main shadow">
						<div class="profile">
							<a href="<?php comment_author_url(); ?>" target="_blank" rel="nofollow"><?php echo str_replace( 'src=', 'src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.2/img/svg/loader/trans.ajax-spinner-preloader.svg" onerror="imgError(this,1)" data-src=', get_avatar( $comment->comment_author_email, '80', '', get_comment_author(), array( 'class' => array('lazyload')))); ?></a>
						</div>
						<div class="commentinfo">
							<section class="commeta">
								<div class="left">
									<h4 class="author"><a href="<?php comment_author_url(); ?>" target="_blank" rel="nofollow"><?php echo get_avatar( $comment->comment_author_email, '24', '', get_comment_author() ); ?><span class="bb-comment isauthor" title="<?php _e('Author', 'sakura'); ?>"><?php _e('Blogger', 'sakura'); /*博主*/?></span> <?php comment_author(); ?> <?php echo get_author_class($comment->comment_author_email,$comment->user_id); ?></a></h4>
								</div>
								<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
								<div class="right">
									<div class="info"><time datetime="<?php comment_date('Y-m-d'); ?>"><?php echo poi_time_since(strtotime($comment->comment_date_gmt), true );//comment_date(get_option('date_format')); ?></time><?php echo siren_get_useragent($comment->comment_agent); ?><?php echo mobile_get_useragent_icon($comment->comment_agent); ?>&nbsp;<?php _e('Location', 'sakura'); /*来自*/?>: <?php echo convertip(get_comment_author_ip()); ?>
    									<?php if (current_user_can('manage_options') and (wp_is_mobile() == false) ) {
                                            $comment_ID = $comment->comment_ID;
                                            $i_private = get_comment_meta($comment_ID, '_private', true);
                                            $flag = '';
                                            $class = !empty($i_private)? 'private_now' : null;
                                            $flag .= ' <i class="post_icon_svg" style="--svg-name: var(--svg_xuehua);--size: 12px;--color:#7E8892;"></i> <a href="javascript:;" data-actionp="set_private" data-idp="' . get_comment_id() . '" id="sp" class="sm '.$class.'" style="color:rgba(0,0,0,.35)">'.__("Private", "sakura").': <span class="has_set_private">';
                                            if (!empty($i_private)) {
                                                $flag .= __("Yes", "sakura").' <i class="post_icon_svg" style="--svg-name: var(--svg_lock);--size: 12px;--color:#7E8892;"></i>';
                                            } else {
                                                $flag .= __("No", "sakura").' <i class="post_icon_svg" style="--svg-name: var(--svg_unlock);--size: 12px;--color:#7E8892;"></i>';
                                            }
                                            $flag .= '</span></a>';
                                            $flag .= edit_comment_link('<i class="post_icon_svg" style="--svg-name: var(--svg_edit);--size: 12px;--color:#e67474;margin:0"></i> '.__("Edit", "mashiro"), ' <span style="color:rgba(0,0,0,.35)">', '</span>');
                                            echo $flag;
                                        } ?></div>
								</div>
							</section>
						</div>
						<div class="body">
							<?php comment_text(); ?>
						</div>
					</div>
					<div class="arrow-left"></div>
				</div>
			</div>
			<hr>
		<?php
	}
}

/**
 * 获取访客VIP样式
 * @param $comment_author_email
 * @param $user_id
 */
    function get_author_class($comment_author_email, $user_id){
        global $wpdb;
        $author_count = count($wpdb->get_results(
        "SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
        echo match(true){
            $author_count < 5 => '<i class="post_icon_svg" style="--svg-name: var(--svg_level_0);--size:1.5em;--color:#BFBFBF;margin: 0 3px;"></i>',
            $author_count < 10 => '<i class="post_icon_svg" style="--svg-name: var(--svg_level_1);--size:1.5em;--color:#BFBFBF;margin: 0 3px;"></i>',
            $author_count < 20 => '<i class="post_icon_svg" style="--svg-name: var(--svg_level_2);--size:1.5em;--color:#95DDB2;margin: 0 3px;"></i>',
            $author_count < 40 => '<i class="post_icon_svg" style="--svg-name: var(--svg_level_3);--size:1.5em;--color:#92D1E5;margin: 0 3px;"></i>',
            $author_count < 80 => '<i class="post_icon_svg" style="--svg-name: var(--svg_level_4);--size:1.5em;--color:#FFB37C;margin: 0 3px;"></i>',
            $author_count < 160 => '<i class="post_icon_svg" style="--svg-name: var(--svg_level_5);--size:1.5em;--color:#FF6C00;margin: 0 3px;"></i>',
            default => '<i class="post_icon_svg" style="--svg-name: var(--svg_level_6);--size:1.5em;--color:red;margin: 0 3px;"></i>',
        };
    }

/**
 * post views
 * @param $number
 * @return string
 */
function restyle_text($number) {
    return match(akina_option('statistics_format')){
        "type_2" => number_format($number),
        "type_3" => number_format($number, 0, '.', ' '),
        "type_4" => ($number >= 1000 ? round($number/1000,2) . 'k' : $number),
        default => $number,
    };
}

function set_post_views() {
    if (is_singular()) {
        global $post;
        $post_id = intval($post->ID);
        if($post_id) {
            $views = (int)get_post_meta($post_id, 'views', true);
            if(!update_post_meta($post_id, 'views', ($views + 1))) {
                add_post_meta($post_id, 'views', 1, true);
            }
        }
    }
}

add_action('get_header', 'set_post_views');

function get_post_views($post_id) {
	if (akina_option('statistics_api')=='wp_statistics'){
        if (!function_exists('wp_statistics_pages')) {
            return __('Please install pulgin <a href="https://wordpress.org/plugins/wp-statistics/" target="_blank">WP-Statistics</a>', 'sakura');
        } else {
            return restyle_text(wp_statistics_pages('total','uri',$post_id));
        }
    } else {
        $views = get_post_meta($post_id, 'views', true);
        return $views=='' ? 0 : restyle_text($views);
        // if($views == '') {
        //     return 0;
        // }else{
        //     return restyle_text($views);
        // }
    }
} 

/*
 * 友情链接
 */
function get_the_link_items($id = null){
  $bookmarks = get_bookmarks('orderby=date&category=' .$id );
  $output = '';
  if ( !empty($bookmarks) ) {
      $output .= '<ul class="link-items fontSmooth">';
      foreach ($bookmarks as $bookmark) {
		if (empty($bookmark->link_description)) $bookmark->link_description = __('This guy is so lazy ╮(╯▽╰)╭', 'sakura');
		if (empty($bookmark->link_image)) $bookmark->link_image = 'https://view.moezx.cc/images/2017/12/30/Transparent_Akkarin.th.jpg';
        $output .=  '<li class="link-item"><a class="link-item-inner effect-apollo" href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" rel="friend"><img class="lazyload" onerror="imgError(this,1)" data-src="' . $bookmark->link_image . '" src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.2/img/svg/loader/trans.ajax-spinner-preloader.svg"><span class="sitename">'. $bookmark->link_name .'</span><div class="linkdes">'. $bookmark->link_description .'</div></a></li>';
      }
      $output .= '</ul>';
  }
  return $output;
}

function get_link_items(){
  $linkcats = get_terms( 'link_category' );
  	if ( !empty($linkcats) ) {
        $result ='';
      	foreach( $linkcats as $linkcat){            
        	$result .=  '<h3 class="link-title"><span class="link-fix">'.$linkcat->name.'</span></h3>';
        	if( $linkcat->description ) $result .= '<div class="link-description">' . $linkcat->description . '</div>';
        	$result .=  get_the_link_items($linkcat->term_id);
      	}
  	} else {
    	$result = get_the_link_items();
  	}
  return $result;
}


/*
 * Gravatar头像使用中国服务器
 */
function gravatar_cn( $url ){ 
	$gravatar_url = array('0.gravatar.com','1.gravatar.com','2.gravatar.com','secure.gravatar.com');
	return str_replace( $gravatar_url, 'sdn.geekzu.org', $url );
}
add_filter( 'get_avatar_url', 'gravatar_cn', 4 );

/*
 * 自定义默认头像
 */
add_filter( 'avatar_defaults', 'mytheme_default_avatar' );

function mytheme_default_avatar ( $avatar_defaults ) {
    //$new_avatar_url = get_template_directory_uri() . '/images/default_avatar.png';
	$new_avatar_url = 'https://sdn.geekzu.org/avatar/b745710ae6b0ce9dfb13f5b7c0956be1';
    $avatar_defaults[$new_avatar_url] = 'Default Avatar';
    return $avatar_defaults;
}

/*
 * 阻止站内文章互相Pingback 
 */
function theme_noself_ping( &$links ) { 
	$home = get_option( 'home' );
	foreach ( $links as $l => $link){
        if (str_starts_with($link, $home))
        unset($links[$l]); 
    }
}
add_action('pre_ping','theme_noself_ping');


/*
 * 订制body类
*/
function akina_body_classes( $classes ) {
  // Adds a class of group-blog to blogs with more than 1 published author.
  if ( is_multi_author() ) {
    $classes[] = 'group-blog';
  }
  // Adds a class of hfeed to non-singular pages.
  if ( ! is_singular() ) {
    $classes[] = 'hfeed';
  }
  // 定制中文字体class
  $classes[] = 'chinese-font';
  return $classes;
}
add_filter( 'body_class', 'akina_body_classes' );

/*
 * 删除自带小工具
*/
function unregister_default_widgets() {
 	unregister_widget('WP_Widget_Pages');
 	unregister_widget('WP_Widget_Calendar');
 	unregister_widget('WP_Widget_Archives');
 	unregister_widget('WP_Widget_Links');
 	unregister_widget('WP_Widget_Meta');
 	unregister_widget('WP_Widget_Search');
 	unregister_widget('WP_Widget_Categories');
 	unregister_widget('WP_Widget_Recent_Posts');
 	unregister_widget('WP_Nav_Menu_Widget');
}
add_action('widgets_init', 'unregister_default_widgets', 11);


/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 */
function akina_jetpack_setup() {
  // Add theme support for Infinite Scroll.
  add_theme_support( 'infinite-scroll', array(
    'container' => 'main',
    'render'    => 'akina_infinite_scroll_render',
    'footer'    => 'page',
  ) );

  // Add theme support for Responsive Videos.
  add_theme_support( 'jetpack-responsive-videos' );
}
add_action( 'after_setup_theme', 'akina_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function akina_infinite_scroll_render() {
  while ( have_posts() ) {
    the_post();
    if ( is_search() ) :
        get_template_part( 'tpl/content', 'search' );
    else :
        get_template_part( 'tpl/content', get_post_format() );
    endif;
  }
}


/*
 * 编辑器增强
 */
function enable_more_buttons($buttons) { 
	$buttons[] = 'hr'; 
	$buttons[] = 'del'; 
	$buttons[] = 'sub'; 
	$buttons[] = 'sup';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	$buttons[] = 'wp_page';
	$buttons[] = 'anchor'; 
	$buttons[] = 'backcolor'; 
	return $buttons;
} 
add_filter('mce_buttons_3', 'enable_more_buttons');
// 下载按钮
function download($atts, $content = null) {  
return '<a class="download" href="'.$content.'" rel="external"  
target="_blank" title="下载地址">  
<span><i class="post_icon_svg" style="--svg-name: var(--svg_pull_down);--size: 18px;"></i>Download</span></a>';}  
add_shortcode('download', 'download'); 

add_action('after_wp_tiny_mce', 'bolo_after_wp_tiny_mce');  
function bolo_after_wp_tiny_mce($mce_settings) {  
?>  
<script type="text/javascript">  
QTags.addButton( 'download', '下载按钮', "[download]下载地址[/download]" );
</script>  
<?php } 


/*
 * 后台登录页
 * @M.J
 */	
//Login Page style
function custom_login() {
	//echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/inc/login.css" />'."\n";
	echo '<link rel="stylesheet" type="text/css" href="',get_template_directory_uri(),'/inc/login.css?',SAKURA_VERSION,'" />',"\n";
}

add_action('login_head', 'custom_login');

//Login Page Title
function custom_headertitle ( $title ) {
	return get_bloginfo('name');
}
add_filter('login_headertext','custom_headertitle');

//Login Page Link
function custom_loginlogo_url($url) {
	return esc_url( home_url('/') );
}
add_filter( 'login_headerurl', 'custom_loginlogo_url' );

//Login Page Footer
function custom_html() {
    $loginbg = akina_option('login_bg') ?: 'https://cdn.jsdelivr.net/gh/mashirozx/Sakura@3.2.7/images/hd.png';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/js/login.js"></script>'."\n";
	echo '<script type="text/javascript">
    document.body.insertAdjacentHTML("afterbegin","<div class=\"loading\"><img src=\"https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.9/img/Sakura/images/login_loading.gif\" width=\"58\" height=\"10\"></div><div id=\"bg\"><img /></div>");
    const bg_img = document.querySelector("#bg img");
    bg_img.setAttribute("src","',$loginbg, '");
    bg_img.addEventListener("load",function(){
        resizeImage(\'bg\');
        window.onresize = ()=>{resizeImage("bg");}
        fadeOut(document.querySelector(".loading"))
    });
    const forgetmenot = document.querySelector(".forgetmenot");
    if(forgetmenot)forgetmenot.outerHTML = \'<p class="forgetmenot">Remember Me<input name="rememberme" id="rememberme" value="forever" type="checkbox"><label for="rememberme" style="float: right;margin-top: 5px;transform: scale(2);margin-right: -10px;"></label></p>\';
    </script>',"\n";
    echo '<script>
    document.addEventListener("DOMContentLoaded", ()=>{
        const captchaimg = document.getElementById("captchaimg"),
            getcaptcha = ()=>{
                fetch("',rest_url('sakura/v1/captcha/create'),'?_wpnonce=',wp_create_nonce('wp_rest'),'")
                .then(response=>response.json())
                .then(json=>{
                    if (json["msg"] != ""){
                        document.querySelector(".banner.error").classList.add("visible");
                        document.querySelector(".banner-message").innerText = json["msg"];
                        clearTimeout(error);
                        error = setTimeout(()=>{
                            document.querySelector(".banner.error").classList.remove("visible");
                        },3000);
                    }else{
                        captchaimg.src = json["data"];
                        document.querySelector("input[name=\'timestamp\']").value = json["time"];
                        document.querySelector("input[name=\'id\']").value = json["id"];
                    }
                });
                clearTimeout(timer1);
                timer1 = setTimeout(getcaptcha,60000);
            }
        let timer1 = setTimeout(getcaptcha,60000),error;
        if (document.querySelector("input[name=\'timestamp\']").value === "") getcaptcha()
        captchaimg && captchaimg.addEventListener("click",getcaptcha);
    }, false);  
    </script>';
}
add_action('login_footer', 'custom_html');

//Login message
//* Add custom message to WordPress login page
function smallenvelop_login_message( $message ) {
    return $message ?: '<p class="message"><strong>You may try 3 times for every 5 minutes!</strong></p>';
    // if ( empty($message) ){
    //     return '<p class="message"><strong>You may try 3 times for every 5 minutes!</strong></p>';
    // } else {
    //     return $message;
    // }
}
//add_filter( 'login_message', 'smallenvelop_login_message' );

//Fix password reset bug </>
function resetpassword_message_fix( $message ) {
    return str_replace(['<','>'], '', $message);
}
add_filter( 'retrieve_password_message', 'resetpassword_message_fix' );

//Fix register email bug </>
function new_user_message_fix( $message ) {
    $show_register_ip = '注册IP | Registration IP: '.get_the_user_ip()." (".convertip(get_the_user_ip()).")\r\n\r\n如非本人操作请忽略此邮件 | Please ignore this email if this was not your operation.\r\n\r\n";
    $message = str_replace('To set your password, visit the following address:', $show_register_ip.'在此设置密码 | To set your password, visit the following address:', $message);
	$message = str_replace('<', '', $message);
    return str_replace('>', "\r\n\r\n设置密码后在此登陆 | Login here after setting password: ", $message);
}
add_filter( 'wp_new_user_notification_email', 'new_user_message_fix' );

/*
 * 评论邮件回复
 */
function comment_mail_notify($comment_id){
	$mail_user_name = akina_option('mail_user_name') ?: 'poi';
    $comment = get_comment($comment_id);
    $parent_id = $comment->comment_parent ?: '';
    $spam_confirmed = $comment->comment_approved;
    $mail_notify = akina_option('mail_notify') ? get_comment_meta($parent_id,'mail_notify') : false;
    $admin_notify = akina_option('admin_notify') ? '1' : (get_comment($parent_id)?->comment_author_email != get_bloginfo('admin_email') ? '1' : '0');
    if(($parent_id != '') && ($spam_confirmed != 'spam') && ($admin_notify != '0') && (!$mail_notify)){
    $wp_email = $mail_user_name . '@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
    $to = trim(get_comment($parent_id)->comment_author_email);
    $subject = '你在 [' . get_option("blogname") . '] 的留言有了回应';
    $message = '
      <div style="background: white;
      width: 95%;
      max-width: 800px;
      margin: auto auto;
      border-radius: 5px;
      border:orange 1px solid;
      overflow: hidden;
      -webkit-box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.12);
      box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.18);">
        <header style="overflow: hidden;">
            <img style="width:100%;z-index: 666;" src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.4/img/other/head.jpg">
        </header>
        <div style="padding: 5px 20px;">
        <p style="position: relative;
        color: white;
        float: left;
        z-index: 999;
        background: orange;
        padding: 5px 30px;
        margin: -25px auto 0 ;
        box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.30)">Dear&nbsp;' . trim(get_comment($parent_id)->comment_author) . '</p>
        <br>
        <h3>您有一条来自<a style="text-decoration: none;color: orange " target="_blank" href="' .home_url(). '/">'. get_option("blogname") .'</a>的回复</h3>
        <br>
        <p style="font-size: 14px;">您在文章《' . get_the_title($comment->comment_post_ID) . '》上发表的评论：</p>
        <div style="border-bottom:#ddd 1px solid;border-left:#ddd 1px solid;padding-bottom:20px;background-color:#eee;margin:15px 0px;padding-left:20px;padding-right:20px;border-top:#ddd 1px solid;border-right:#ddd 1px solid;padding-top:20px">'
        . trim(get_comment($parent_id)->comment_content) . '</div>
        <div style="font-size: 14px;">' . trim($comment->comment_author) . ' 给您的回复如下：</p>
        <p style="border-bottom:#ddd 1px solid;border-left:#ddd 1px solid;padding-bottom:20px;background-color:#eee;margin:15px 0px;padding-left:20px;padding-right:20px;border-top:#ddd 1px solid;border-right:#ddd 1px solid;padding-top:20px">'
        . trim($comment->comment_content) . '</div>

      <div style="text-align: center;">
          <img src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.4/img/other/hr.png" alt="hr" style="width:100%;
                                                                                                  margin:5px auto 5px auto;
                                                                                                  display: block;">
          <a style="text-transform: uppercase;
                      text-decoration: none;
                      font-size: 14px;
                      border: 2px solid #6c7575;
                      color: #2f3333;
                      padding: 10px;
                      display: inline-block;
                      margin: 10px auto 0; " target="_blank" href="' . htmlspecialchars(get_comment_link($parent_id)) . '">点击查看回复的完整內容</a>
      </div>
        <p style="font-size: 12px;text-align: center;color: #999;">本邮件为系统自动发出，请勿直接回复<br>
        &copy; '.date(Y).' '.get_option("blogname").'</p>
      </div>       
    </div>
';
    $message = convert_smilies($message);
    $message = str_replace("{{",'<img src="https://cdn.jsdelivr.net/gh/bymoye/cdn@1.2/sakura/images/smiliesbilipng/emoji_',$message);
    $message = str_replace("}}",'.png" alt="emoji" style="height: 2em; max-height: 2em;">',$message);
    
    $message =  str_replace('{UPLOAD}', 'https://i.loli.net/', $message); 
    $message =  str_replace('[/img][img]', '[/img^img]', $message);  
    
    $message =  str_replace('[img]', '<img src="', $message); 
    $message =  str_replace('[/img]', '" style="width:80%;display: block;margin-left: auto;margin-right: auto;">', $message); 
    
    $message =  str_replace('[/img^img]', '" style="width:80%;display: block;margin-left: auto;margin-right: auto;"><img src="', $message);
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
  }
}
add_action('comment_post', 'comment_mail_notify');

/*
 * 链接新窗口打开
 */
function rt_add_link_target( $content ){
    $content =  str_replace('<a', '<a rel="nofollow"', $content); 
	// use the <a> tag to split into segments
	$bits = explode( '<a ', $content );
	// loop though the segments
	foreach( $bits as $key=>$bit ){
	    // fix the target="_blank" bug after the link
	    if (!str_contains($bit, 'href')) continue;
	    // fix the target="_blank" bug in the codeblock
	    if (!str_contains(preg_replace('/code([\s\S]*?)\/code[\s]*/m', 'temp', $content), $bit))  continue;
		// find the end of each link
		$pos = strpos( $bit, '>' );
		// check if there is an end (only fails with malformed markup)
		if( $pos !== false ){
			// get a string with just the link's attibutes
			$part = substr( $bit, 0, $pos );
			// for comparison, get the current site/network url
			$siteurl = network_site_url();
			// if the site url is in the attributes, assume it's in the href and skip, also if a target is present
			if( !str_contains($part, $siteurl) && !str_contains($part, 'target=')){
				// add the target attribute
				$bits[$key] = 'target="_blank" ' . $bit;
			}
		}
	}
	// re-assemble the content, and return it
	return implode( '<a ', $bits );
}
add_filter( 'comment_text', 'rt_add_link_target' );

// 评论通过BBCode插入图片
function comment_picture_support($content) {
    $content =  str_replace('http://', 'https://', $content); // 干掉任何可能的 http
    $content =  str_replace('{UPLOAD}', 'https://i.loli.net/', $content); 
    $content =  str_replace('[/img][img]', '[/img^img]', $content); 
    $content =  str_replace('[img]', '<br><img src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.2/img/svg/loader/trans.ajax-spinner-preloader.svg" data-src="', $content); 
    $content =  str_replace('[/img]', '" class="lazyload comment_inline_img" onerror="imgError(this)"><br>', $content);
    return str_replace('[/img^img]', '" class="lazyload comment_inline_img" onerror="imgError(this)"><img src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.2/img/svg/loader/trans.ajax-spinner-preloader.svg" data-src="', $content);
}
add_filter( 'comment_text', 'comment_picture_support' );

/*
 * 修改评论表情调用路径
 */

// 简单遍历系统表情库，今后应考虑标识表情包名——使用增加的扩展名，同时保留原有拓展名
// 还有一个思路是根据表情调用路径来判定<-- 此法最好！
// 贴吧


$wpsmiliestrans = array();
function push_tieba_smilies() {
global $wpsmiliestrans;
// don't bother setting up smilies if they are disabled
if ( !get_option('use_smilies'))return false;
    $tiebaname = ['good','han','spray','Grievance','shui','reluctantly','anger','tongue','se','haha','rmb','doubt','tear','surprised2','Happy','ku','surprised','theblackline','smilingeyes','spit','huaji','bbd','hu','shame','naive','rbq','britan','aa','niconiconi','niconiconi_t','niconiconit','awesome'];
    $return_smiles = '';
    $type = bgapi::check() ? 'webp' : 'png';
    $tiebaimgdir = 'tieba' . $type . '/';
    $smiliesgs='.' . $type;
    foreach ($tiebaname as $tieba_Name){
      // 选择面版
      $return_smiles = $return_smiles . '<span title="'.$tieba_Name.'" onclick="grin('."'".$tieba_Name."'".',type = \'tieba\')"><img loading="lazy" src="https://cdn.jsdelivr.net/gh/bymoye/cdn@1.2/sakura/images/smilies/'. $tiebaimgdir .'icon_'. $tieba_Name . $smiliesgs.'" /></span>';
      // 正文转换
      $wpsmiliestrans['::' . $tieba_Name . '::'] = '<span title="'. $tieba_Name .'" onclick="grin('."'". $tieba_Name ."'".',type = \'tieba\')"><img loading="lazy" src="https://cdn.jsdelivr.net/gh/bymoye/cdn@1.2/sakura/images/smilies/'.$tiebaimgdir.'icon_'. $tieba_Name .$smiliesgs.'" /></span>';
    }
    return $return_smiles;
  }
  push_tieba_smilies();

function tieba_smile_filter($content) {
    global $wpsmiliestrans;
    return str_replace(array_keys($wpsmiliestrans), $wpsmiliestrans, $content);
}
add_filter('the_content', 'tieba_smile_filter'); //替换文章关键词
add_filter('comment_text', 'tieba_smile_filter');//替换评论关键词

function push_emoji_panel() {
    return '
        <a class="emoji-item">(⌒▽⌒)</a>
        <a class="emoji-item">（￣▽￣）</a>
        <a class="emoji-item">(=・ω・=)</a>
        <a class="emoji-item">(｀・ω・´)</a>
        <a class="emoji-item">(〜￣△￣)〜</a>
        <a class="emoji-item">(･∀･)</a>
        <a class="emoji-item">(°∀°)ﾉ</a>
        <a class="emoji-item">(￣3￣)</a>
        <a class="emoji-item">╮(￣▽￣)╭</a>
        <a class="emoji-item">(´_ゝ｀)</a>
        <a class="emoji-item">←_←</a>
        <a class="emoji-item">→_→</a>
        <a class="emoji-item">(&lt;_&lt;)</a>
        <a class="emoji-item">(&gt;_&gt;)</a>
        <a class="emoji-item">(;¬_¬)</a>
        <a class="emoji-item">("▔□▔)/</a>
        <a class="emoji-item">(ﾟДﾟ≡ﾟдﾟ)!?</a>
        <a class="emoji-item">Σ(ﾟдﾟ;)</a>
        <a class="emoji-item">Σ(￣□￣||)</a>
        <a class="emoji-item">(’；ω；‘)</a>
        <a class="emoji-item">（/TДT)/</a>
        <a class="emoji-item">(^・ω・^ )</a>
        <a class="emoji-item">(｡･ω･｡)</a>
        <a class="emoji-item">(●￣(ｴ)￣●)</a>
        <a class="emoji-item">ε=ε=(ノ≧∇≦)ノ</a>
        <a class="emoji-item">(’･_･‘)</a>
        <a class="emoji-item">(-_-#)</a>
        <a class="emoji-item">（￣へ￣）</a>
        <a class="emoji-item">(￣ε(#￣)Σ</a>
        <a class="emoji-item">ヽ(‘Д’)ﾉ</a>
        <a class="emoji-item">（#-_-)┯━┯</a>
        <a class="emoji-item">(╯°口°)╯(┴—┴</a>
        <a class="emoji-item">←◡←</a>
        <a class="emoji-item">( ♥д♥)</a>
        <a class="emoji-item">_(:3」∠)_</a>
        <a class="emoji-item">Σ&gt;―(〃°ω°〃)♡→</a>
        <a class="emoji-item">⁄(⁄ ⁄•⁄ω⁄•⁄ ⁄)⁄</a>
        <a class="emoji-item">(╬ﾟдﾟ)▄︻┻┳═一</a>
        <a class="emoji-item">･*･:≡(　ε:)</a>
        <a class="emoji-item">(笑)</a>
        <a class="emoji-item">(汗)</a>
        <a class="emoji-item">(泣)</a>
        <a class="emoji-item">(苦笑)</a>
    ';
}

// bilibili smiles
$bilismiliestrans = array();
function push_bili_smilies(){
  global $bilismiliestrans;
  $name = array('baiyan','bishi','bizui','chan','dai','daku','dalao','dalian','dianzan','doge','facai','fanu','ganga','guilian','guzhang','haixiu','heirenwenhao','huaixiao','jingxia','keai','koubizi','kun','lengmo','liubixue','liuhan','liulei','miantian','mudengkoudai','nanguo','outu','qinqin','se','shengbing','shengqi','shuizhao','sikao','tiaokan','tiaopi','touxiao','tuxue','weiqu','weixiao','wunai','xiaoku','xieyanxiao','yiwen','yun','zaijian','zhoumei','zhuakuang');
  $return_smiles = '';
  $type = bgapi::check() ? 'webp' : 'png';
  $biliimgdir = 'bili' . $type . '/';
  $smiliesgs='.' . $type;
  foreach($name as $smilies_Name){
    // 选择面版
    $return_smiles = $return_smiles . '<span title="'.$smilies_Name.'" onclick="grin('."'".$smilies_Name."'".',type = \'Math\')"><img loading="lazy" src="https://cdn.jsdelivr.net/gh/bymoye/cdn@1.2/sakura/images/smilies/'. $biliimgdir .'emoji_'. $smilies_Name . $smiliesgs.'" /></span>';
    // 正文转换
    $bilismiliestrans['{{' . $smilies_Name . '}}'] = '<span title="'. $smilies_Name .'" onclick="grin('."'". $smilies_Name ."'".',type = \'Math\')"><img loading="lazy" src="https://cdn.jsdelivr.net/gh/bymoye/cdn@1.2/sakura/images/smilies/'.$biliimgdir.'emoji_'. $smilies_Name .$smiliesgs.'" /></span>';
    }
    return $return_smiles;
}
push_bili_smilies();

function bili_smile_filter($content) {
    global $bilismiliestrans;
    return str_replace(array_keys($bilismiliestrans), $bilismiliestrans, $content);
}
add_filter('the_content', 'bili_smile_filter'); //替换文章关键词
add_filter('comment_text', 'bili_smile_filter');//替换评论关键词

function featuredtoRSS($content) {
    global $post;
    if ( has_post_thumbnail( $post->ID ) ){
        $content = '<div>' . get_the_post_thumbnail( $post->ID, 'medium', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
    }
    return $content;
}
add_filter('the_excerpt_rss', 'featuredtoRSS');
add_filter('the_content_feed', 'featuredtoRSS');

//
function bili_smile_filter_rss($content) {
    $type = bgapi::check() ? 'webp' : 'png';
    $biliimgdir = 'bili' . $type . '/';
    $smiliesgs='.' . $type;
    $content = str_replace('{{','<img src="https://cdn.jsdelivr.net/gh/bymoye/cdn@1.2/sakura/images/smilies/'.$biliimgdir,$content);
    $content = str_replace('}}',$smiliesgs.'" alt="emoji" style="height: 2em; max-height: 2em;">',$content);
    $content =  str_replace('[img]', '<img src="', $content);
    return str_replace('[/img]', '" style="display: block;margin-left: auto;margin-right: auto;">', $content);
}
add_filter( 'comment_text_rss', 'bili_smile_filter_rss' );//替换评论rss关键词

function toc_support($content) {
    $content =  str_replace('[toc]', '<div class="has-toc have-toc"></div>', $content); // TOC 支持
    $content =  str_replace('[begin]', '<span class="begin">', $content); // 首字格式支持
    return str_replace('[/begin]', '</span>', $content);
}
add_filter('the_content', 'toc_support');
add_filter('the_excerpt_rss', 'toc_support');
add_filter('the_content_feed', 'toc_support');

// 显示访客当前 IP
function get_the_user_ip() {
if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
//check ip from share internet
$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
//to check ip is pass from proxy
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}
return apply_filters( 'wpb_get_ip', $ip );
}
 
add_shortcode('show_ip', 'get_the_user_ip');


/*歌词*/
function hero_get_lyric() {
	/** These are the lyrics to Hero */
	$lyrics = "";

	// Here we split it into lines
	$lyrics = explode( "\n", $lyrics );

	// And then randomly choose a line
	return wptexturize( $lyrics[ mt_rand( 0, count( $lyrics ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later
function hello_hero() {
	$chosen = hero_get_lyric();
	echo $chosen;
}

/*私密评论*/
add_action('wp_ajax_nopriv_siren_private', 'siren_private');
add_action('wp_ajax_siren_private', 'siren_private');
function siren_private(){
    $comment_id = $_POST['p_id'];
    $action = $_POST['p_action'];
    if ($action == 'set_private') {
        update_comment_meta($comment_id, '_private', 'true');
        $i_private = get_comment_meta($comment_ID, '_private', true);
        echo !empty($i_private) ? '否' : '是';
    }
    die;
}

//时间序列
function memory_archives_list() {
	if( true ){
		$output = '<div id="archives"><p style="text-align:right;">[<span id="al_expand_collapse">'.__( "All expand/collapse", "sakura" )/*全部展开/收缩*/.'</span>]<!-- (注: 点击月份可以展开)--></p>';
		$the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1&post_type=post' ); //update: 加上忽略置顶文章
		$year=0; $mon=0; $i=0; $j=0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$year_tmp = get_the_time('Y');
            $mon_tmp = get_the_time('m');
            $y=$year; $m=$mon;
            if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
            if ($year != $year_tmp && $year > 0) $output .= '</ul>';
            if ($year != $year_tmp) {
                $year = $year_tmp;
                $output .= '<h3 class="al_year">'. $year . __(" ","year","sakura"). /*年*/' </h3><ul class="al_mon_list">'; //输出年份
            }
            if ($mon != $mon_tmp) {
                $mon = $mon_tmp;
                $output .= '<li class="al_li"><span class="al_mon"><span style="color:#0bf;">'.get_the_time('M').'</span> (<span id="post-num"></span>'.__(" post(s)","sakura") /*篇文章*/.')</span><ul class="al_post_list">'; //输出月份
            }
            $output .= '<li>'.'<a href="'. get_permalink() .'"><span style="color:#0bf;">'/*get_the_time('d'.__(" ","sakura")) 日*/.'</span>'. get_the_title() .' <span>('.get_post_views(get_the_ID()).' <i class="post_icon_svg" style="--svg-name: var(--svg_fire);--size: 15px;--color:#000;margin:0;"></i> / '. get_comments_number('0', '1', '%') .' <i class="post_icon_svg" style="--svg-name: var(--svg_commenting);--size: 15px;--color:#000;margin:0;"></i>)</span></a></li>'; //输出文章日期和标题
        endwhile;
        wp_reset_postdata();
        $output .= '</ul></li></ul> <!--<ul class="al_mon_list"><li><ul class="al_post_list" style="display: block;"><li>博客已经萌萌哒运行了<span id="monitorday"></span>天</li></ul></li></ul>--></div>';
        #update_option('memory_archives_list', $output);
	}
    echo $output;
}

/*
 * 隐藏 Dashboard
 */
/* Remove the "Dashboard" from the admin menu for non-admin users */
function remove_dashboard () {
    global $current_user, $menu, $submenu;
    wp_get_current_user();

    if( ! in_array( 'administrator', $current_user->roles ) ) {
        reset( $menu );
        $page = key( $menu );
        while( ( __( 'Dashboard' ) != $menu[$page][0] ) && next( $menu ) ) {
            $page = key( $menu );
        }
        if( __( 'Dashboard' ) == $menu[$page][0] ) {
            unset( $menu[$page] );
        }
        reset($menu);
        $page = key($menu);
        while ( ! $current_user->has_cap( $menu[$page][1] ) && next( $menu ) ) {
            $page = key( $menu );
        }
        if ( preg_match( '#wp-admin/?(index.php)?$#', $_SERVER['REQUEST_URI'] ) &&
            ( 'index.php' != $menu[$page][2] ) ) {
                wp_redirect( get_option( 'siteurl' ) . '/wp-admin/profile.php');
        }
    }
}
add_action('admin_menu', 'remove_dashboard');

/**
 * Filter the except length to 20 words. 限制摘要长度
 *
 * @param $string
 * @param $start
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */

function GBsubstr($string, $start, $length) {
    if (strlen($string) > $length) {
        $str = null;
        $len = 0;
        $i = $start;
        while ( $len < $length) {
        if (ord(substr($string, $i, 1)) > 0xc0) {
            $str .=substr($string, $i, 3);
            $i+= 3;
        }elseif (ord(substr($string, $i, 1)) > 0xa0) {
            $str .= substr($string, $i, 2);
            $i+= 2;
        }else {
            $str.=substr($string, $i, 1);
            $i++;
        }
        $len ++;
        }
        return $str;
    }else {
        return $string;
    }
}
 
function excerpt_length($exp) {
    if (!function_exists('mb_substr')) {
        $exp = GBsubstr($exp, 0, 80);
    } else {
        /*
         * To use mb_substr() function, you should uncomment "extension=php_mbstring.dll" in php.ini
         */
        $exp = mb_substr($exp, 0, 80);
    }
	return $exp;
}
add_filter( 'the_excerpt', 'excerpt_length' );


/*
 * 后台路径
 */
/*
add_filter('site_url',  'wpadmin_filter', 10, 3);
 function wpadmin_filter( $url, $path, $orig_scheme ) {
  $old  = array( "/(wp-admin)/");
  $admin_dir = WP_ADMIN_DIR;
  $new  = array($admin_dir);
  return preg_replace( $old, $new, $url, 1);
 }
*/

function admin_ini() {
  wp_enqueue_style('admin-styles-fix-icon', get_site_url() . '/wp-includes/css/dashicons.css');
  wp_enqueue_style('cus-styles-fit', get_template_directory_uri() . '/inc/css/dashboard-fix.css');
  wp_enqueue_script( 'lazyload', 'https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.min.js' );
}
add_action('admin_enqueue_scripts', 'admin_ini');

function custom_admin_js() {
    echo '<script>
    window.onload=function(){
        lazyload();
        
        try{
            document.querySelector("#scheme-tip .notice-dismiss").addEventListener("click", function(){
                location.href="?scheme-tip-dismissed'.BUILD_VERSION.'";
            });
        } catch(e){}
    }
    </script>';
}
add_action('admin_footer', 'custom_admin_js');

/*
 * 后台通知
 */
function scheme_tip() {
	$msg = '<b>Why not try the new admin dashboard color scheme <a href="/wp-admin/profile.php">here</a>?</b>';
	if ( get_user_locale( get_current_user_id() ) == "zh_CN") {
		$msg = '<b>试一试新后台界面<a href="/wp-admin/profile.php">配色方案</a>吧？</b>';
	}
	if ( get_user_locale( get_current_user_id() ) == "zh_TW") {
		$msg = '<b>試一試新後台界面<a href="/wp-admin/profile.php">色彩配置</a>吧？</b>';
	}
	if ( get_user_locale( get_current_user_id() ) == "ja") {
		$msg = '<b>新しい<a href="/wp-admin/profile.php">管理画面の配色</a>を試しますか？</b>';
	}
	if ( get_user_locale( get_current_user_id() ) == "ja-JP") {
		$msg = '<b>新しい<a href="/wp-admin/profile.php">管理画面の配色</a>を試しますか？</b>';
	}
    
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'scheme-tip-dismissed'.BUILD_VERSION ) ) {
        echo '<div class="notice notice-success is-dismissible" id="scheme-tip"><p><b>'.$msg.'</b></p></div>';
    }
}

add_action( 'admin_notices', 'scheme_tip' );

function scheme_tip_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['scheme-tip-dismissed'.BUILD_VERSION] ) )
        add_user_meta( $user_id, 'scheme-tip-dismissed'.BUILD_VERSION, 'true', true );
}
add_action( 'admin_init', 'scheme_tip_dismissed' );

//dashboard scheme
function dash_scheme($key, $name, $col1, $col2, $col3, $col4, $base, $focus, $current, $rules=""){
    $hash = 'color_1='.str_replace('#','',$col1).
            '&color_2='.str_replace('#','',$col2).
            '&color_3='.str_replace('#','',$col3).
            '&color_4='.str_replace('#','',$col4).
            '&rules='.urlencode($rules);

    wp_admin_css_color(
        $key,
        $name,
        get_template_directory_uri().'/inc/dash-scheme.php?'.$hash,
        array( $col1, $col2, $col3, $col4 ),
        array( 'base' => $base, 'focus' => $focus, 'current' => $current )
    );
}

//Sakura
dash_scheme($key='sakura', $name='Sakura🌸', 
            $col1='#8fbbb1', $col2='#bfd8d2', $col3='#fedcd2', $col4='#df744a', 
            $base='#e5f8ff', $focus='#fff', $current='#fff',
            $rules='#adminmenu .wp-has-current-submenu .wp-submenu a,#adminmenu .wp-has-current-submenu.opensub .wp-submenu a,#adminmenu .wp-submenu a,#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a,#wpadminbar .ab-submenu .ab-item,#wpadminbar .quicklinks .menupop ul li a,#wpadminbar .quicklinks .menupop.hover ul li a,#wpadminbar.nojs .quicklinks .menupop:hover ul li a,.folded #adminmenu .wp-has-current-submenu .wp-submenu a{color:#f3f2f1}body{background-image:url(https://view.moezx.cc/images/2018/01/03/sakura.png);background-attachment:fixed;}#wpcontent{background:rgba(255,255,255,.0)}.wp-core-ui .button-primary{background:#bfd8d2!important;border-color:#8fbbb1 #8fbbb1 #8fbbb1!important;color:#fff!important;box-shadow:0 1px 0 #8fbbb1!important;text-shadow:0 -1px 1px #8fbbb1,1px 0 1px #8fbbb1,0 1px 1px #8fbbb1,-1px 0 1px #8fbbb1!important}');
       
//custom
dash_scheme($key='custom', $name='Custom', 
            $col1=akina_option('dash_scheme_color_a'), $col2=akina_option('dash_scheme_color_b'), $col3=akina_option('dash_scheme_color_c'), $col4=akina_option('dash_scheme_color_d'), 
            $base=akina_option('dash_scheme_color_base'), $focus=akina_option('dash_scheme_color_focus'), $current=akina_option('dash_scheme_color_current'),
            $rules=akina_option('dash_scheme_css_rules'));

//Set Default Admin Color Scheme for New Users
function set_default_admin_color($user_id) {
    $args = array(
        'ID' => $user_id,
        'admin_color' => 'sunrise'
    );
    wp_update_user( $args );
}
//add_action('user_register', 'set_default_admin_color');            

//Stop Users From Switching Admin Color Schemes
//if ( !current_user_can('manage_options') ) remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

// WordPress Custom Font @ Admin
/*
function custom_admin_open_sans_font() {
    echo '<link href="https://fonts.googleapis.com/css?family=Noto+Serif+SC&display=swap" rel="stylesheet">' . PHP_EOL;
    echo '<style>body, #wpadminbar *:not([class="ab-icon"]), .wp-core-ui, .media-menu, .media-frame *, .media-modal *{font-family:"Noto Serif SC","Source Han Serif SC","Source Han Serif","source-han-serif-sc","PT Serif","SongTi SC","MicroSoft Yahei",Georgia,serif !important;}</style>' . PHP_EOL;
}
add_action( 'admin_head', 'custom_admin_open_sans_font' );

// WordPress Custom Font @ Admin Frontend Toolbar
function custom_admin_open_sans_font_frontend_toolbar() {
    if(current_user_can('administrator')) {
        echo '<link href="https://fonts.googleapis.com/css?family=Noto+Serif+SC&display=swap" rel="stylesheet">' . PHP_EOL;
        echo '<style>#wpadminbar *:not([class="ab-icon"]){font-family:"Noto Serif SC","Source Han Serif SC","Source Han Serif","source-han-serif-sc","PT Serif","SongTi SC","MicroSoft Yahei",Georgia,serif !important;}</style>' . PHP_EOL;
    }
}
add_action( 'wp_head', 'custom_admin_open_sans_font_frontend_toolbar' );

// WordPress Custom Font @ Admin Login
function custom_admin_open_sans_font_login_page() {
    if(stripos($_SERVER["SCRIPT_NAME"], strrchr(wp_login_url(), '/')) !== false) {
        echo '<link href="https://fonts.googleapis.com/css?family=Noto+Serif+SC&display=swap" rel="stylesheet">' . PHP_EOL;
        echo '<style>body{font-family:"Noto Serif SC","Source Han Serif SC","Source Han Serif","source-han-serif-sc","PT Serif","SongTi SC","MicroSoft Yahei",Georgia,serif !important;}</style>' . PHP_EOL;
	}
}
add_action( 'login_head', 'custom_admin_open_sans_font_login_page' );
*/
// 阻止垃圾注册
add_action( 'register_post', 'codecheese_register_post', 10, 3 );
     
function codecheese_register_post( $sanitized_user_login, $user_email, $errors ) {
        
	// Blocked domains  
	$domains = array(   'net.buzzcluby.com', 
                        'buzzcluby.com',
                        'mail.ru',
                        'h.captchaeu.info',
                        'edge.codyting.com');
        
	// Get visitor email domain
        $email = explode( '@', $user_email );
	// Check and display error message for the registration form if exists
	if( empty($email) && in_array( $email[1], $domains ) )
		$errors->add('invalid_email', __('<b>ERROR</b>: This email domain (<b>@'.$email[1].'</b>) has been blocked. Please use another email.'));
}

// html 标签处理器
function html_tag_parser($content) {  
    if(!is_feed()) {  
        if(akina_option('lazyload') && akina_option('lazyload_spinner')){
            $content=preg_replace(
                '/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i',
                "<img $1 class=\"lazyload\" data-src=\"$2\" src=\"".akina_option('lazyload_spinner')."\" onerror=\"imgError(this)\" $3 >\n<noscript>$0</noscript>",
                $content
            ); 
        } 
        //Github cards
        $content=preg_replace(
            '/\[github repo=[\'"]([^\'"]+)[\'"]\]/i',
            '
            <iframe allowtransparency="true" 
                    src="https://api.2heng.xin/github-card/?repo=$1" 
                    width="400" height="153"
                    style="margin-left: 50%; transform: translateX(-50%);"></iframe>
            ',
            $content
        );
    } 
    //html tag parser for rss
    if(is_feed()) {          
        //Github cards
        $content=preg_replace(
            '/\[github repo=[\'"]([^\'"]+)[\'"]\]/i',
            '<a href="https://github.com/$1">',
            $content
        );
    }  
    return $content; 
}
add_filter('the_content', 'html_tag_parser'); //替换文章关键词
//add_filter( 'comment_text', 'html_tag_parser' );//替换评论关键词

/*
 * QQ 评论
 */
// 数据库插入评论表单的qq字段 
add_action('wp_insert_comment','sql_insert_qq_field',10,2);
function sql_insert_qq_field($comment_ID,$commmentdata) {
	$qq = $_POST['new_field_qq'] ?? false;
	update_comment_meta($comment_ID,'new_field_qq',$qq); // new_field_qq 是表单name值，也是存储在数据库里的字段名字
}
// 后台评论中显示qq字段
add_filter( 'manage_edit-comments_columns', 'add_comments_columns' );
add_action( 'manage_comments_custom_column', 'output_comments_qq_columns', 10, 2 );
function add_comments_columns( $columns ){
    $columns[ 'new_field_qq' ] = __( 'QQ' );        // 新增列名称
    return $columns;
}
function output_comments_qq_columns( $column_name, $comment_id ){
    switch( $column_name ) {
		case "new_field_qq" :
		 // 这是输出值，可以拿来在前端输出，这里已经在钩子manage_comments_custom_column上输出了
		echo get_comment_meta( $comment_id, 'new_field_qq', true );
		break;
	}
}
/**
 * 头像调用路径 
 */
add_filter( 'get_avatar', 'change_avatar', 10, 3 );
function change_avatar($avatar){
    global $comment;
    if ($comment) {
        if( get_comment_meta( $comment->comment_ID, 'new_field_qq', true )){
            $qq_number =  get_comment_meta( $comment->comment_ID, 'new_field_qq', true );
            return '<img src="https://q2.qlogo.cn/headimg_dl?dst_uin='.$qq_number.'&spec=100" class="lazyload avatar avatar-24 photo" alt="😀" width="24" height="24" onerror="imgError(this,1)">';
        }else{
            return $avatar ;
        }
    } else{
        return $avatar ;
    }
}

function DEFAULT_FEATURE_IMAGE(): array|bool|string {
if (akina_option('cover_cdn_options') == 'type_2'){
    return get_template_directory_uri() . '/feature/index.php?' . rand(1,1000);
  }elseif(akina_option('cover_cdn_options') == 'type_1'){
    return bgapi::getbg();
  }
    return false;
}

function get_random_bg_url(): array|string {
	if (akina_option('cover_cdn_options') == 'type_2'){
		return get_template_directory_uri() . '/feature/index.php?' . rand(1,1000);
	}elseif(akina_option('cover_cdn_options') == 'type_1'){
		if (wp_is_mobile()){
			return bgapi::getbg('mobile');
		}else{
			return bgapi::getbg();
		}
		// return get_random_image_url();
	}}
//防止设置置顶文章造成的图片同侧bug
add_action( 'pre_get_posts', function($q){
    if ($q->is_home() && $q->is_main_query()){
        $q->set( 'posts_per_page', get_option('posts_per_page') - sizeof(get_option( 'sticky_posts' )) );
        if ( $q->get('paged') > 1 )
            $q->set('post__not_in', get_option( 'sticky_posts'));
    }
});

//评论回复
function sakura_comment_notify($comment_id){
    if ( !isset($_POST['mail-notify']) ) 
        update_comment_meta($comment_id,'mail_notify','false');
}
add_action('comment_post', 'sakura_comment_notify');

//侧栏小工具
if (akina_option('sakura_widget')) {
    if (function_exists('register_sidebar')) {
        register_sidebar(array(
            'name' => __('Sidebar'),//侧栏
            'id' => 'sakura_widget',
            'before_widget' => '<div class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<div class="title"><h2>',
            'after_title' => '</h2></div>'
        ));
    }
}

// 评论Markdown解析
function markdown_parser($incoming_comment) {
    global $wpdb,$comment_markdown_content;
    $re = '/```([\s\S]*?)```[\s]*|`{1,2}[^`](.*?)`{1,2}|\[.*?\]\([\s\S]*?\)/m';
    if(preg_replace($re,'temp',$incoming_comment['comment_content']) != strip_tags(preg_replace($re,'temp',$incoming_comment['comment_content']))){
        siren_ajax_comment_err('评论只支持Markdown啦，见谅╮(￣▽￣)╭<br>Markdown Supported while <i class="post_icon_svg" style="--svg-name: var(--svg_code);--size: 20px;--color:#000;vertical-align: -0.3em;"></i> Forbidden');
        return( $incoming_comment );
    }
    // $myCustomer = $wpdb->get_row("SELECT * FROM wp_comments");
    //Add column if not present.
    $column_names = $wpdb->get_row("SELECT * FROM information_schema.columns where 
    table_name='wp_comments' and column_name = 'comment_markdown' LIMIT 1");
    if (!isset($column_names)) {
        $wpdb->query("ALTER TABLE wp_comments ADD comment_markdown text");
    }
    $comment_markdown_content = $incoming_comment['comment_content'];
    include 'inc/classes/Parsedown.php';
    $Parsedown = new Parsedown();
    $incoming_comment['comment_content'] = $Parsedown->setUrlsLinked(false)->text($incoming_comment['comment_content']);
    return $incoming_comment;
}
add_filter('preprocess_comment' , 'markdown_parser');
remove_filter( 'comment_text', 'make_clickable', 9 );

//保存Markdown评论
function save_markdown_comment($comment_ID, $comment_approved) {
    global $wpdb,$comment_markdown_content;
    $comment = get_comment($comment_ID);
    $comment_content = $comment_markdown_content;
    //store markdow content
    $wpdb->query("UPDATE wp_comments SET comment_markdown='".$comment_content."' WHERE comment_ID='".$comment_ID."';");
}
add_action('comment_post', 'save_markdown_comment', 10, 2);

//WordPress 5.0+移除 block-library CSS
add_action( 'wp_enqueue_scripts', 'fanly_remove_block_library_css', 100 );
function fanly_remove_block_library_css() {
	wp_dequeue_style( 'wp-block-library' );
}

//打开评论HTML标签限制
function allow_more_tag_in_comment() {
	global $allowedtags;
	$allowedtags['pre'] = array('class'=>array());
	$allowedtags['code'] = array('class'=>array());
	$allowedtags['h1'] = array('class'=>array());
	$allowedtags['h2'] = array('class'=>array());
	$allowedtags['h3'] = array('class'=>array());
	$allowedtags['h4'] = array('class'=>array());
	$allowedtags['h5'] = array('class'=>array());
	$allowedtags['ul'] = array('class'=>array());
	$allowedtags['ol'] = array('class'=>array());
	$allowedtags['li'] = array('class'=>array());
	$allowedtags['td'] = array('class'=>array());
	$allowedtags['th'] = array('class'=>array());
	$allowedtags['tr'] = array('class'=>array());
	$allowedtags['table'] = array('class'=>array());
	$allowedtags['thead'] = array('class'=>array());
	$allowedtags['tbody'] = array('class'=>array());
	$allowedtags['span'] = array('class'=>array());
}
add_action('pre_comment_on_post', 'allow_more_tag_in_comment');
//code end 

//新建说说功能 
add_action('init', 'my_custom_init');
function my_custom_init(){
    $labels = array( 'name' => '说说',
                    'singular_name' => '说说', 
                    'add_new' => '发表说说', 
                    'add_new_item' => '发表说说',
                    'edit_item' => '编辑说说', 
                    'new_item' => '新说说',
                    'view_item' => '查看说说',
                    'search_items' => '搜索说说', 
                    'not_found' => '暂无说说',
                    'not_found_in_trash' => '没有已遗弃的说说',
                    'parent_item_colon' => '',
                    'menu_name' => '说说' 
                );
    $args = array( 'labels' => $labels,
                    'public' => true, 
                    'publicly_queryable' => true,
                    'show_ui' => true,
                    'show_in_menu' => true, 
                    'exclude_from_search' =>true,
                    'query_var' => true, 
                    'rewrite' => true,
                    'capability_type' => 'post',
                    'has_archive' => false, 'hierarchical' => false, 
                    'menu_position' => null,
                    'supports' => array('editor','author','title', 'custom-fields')
                );
register_post_type('shuoshuo',$args); 
}

//禁用XML-RPC接口
add_filter('xmlrpc_enabled', '__return_false');

//禁用XML-RPC的pingback接口
add_filter( 'xmlrpc_methods', 'remove_xmlrpc_pingback_ping' );
function remove_xmlrpc_pingback_ping( $methods ) {
    unset( $methods['pingback.ping'] );
    return $methods;
}
/**
 * 登录/注册页添加验证码
 */
function login_CAPTCHA() {
    echo '<p><label for="captcha" class="captcha">验证码<br><img id="captchaimg" width="120" height="40" src=""><input type="text" name="yzm" id="yzm" class="input" value="" size="20" tabindex="4" placeholder="请输入验证码"><input type="hidden" name="timestamp" value=""><input type="hidden" name="id" value=""></label></p>';
}
add_action('login_form','login_CAPTCHA');
add_action('register_form','login_CAPTCHA' );
add_action('lostpassword_form','login_CAPTCHA');
function login_tips() {
    echo '<div class="banner error">
    <div class="banner-icon"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" class="eva eva-alert-circle-outline" fill="#ffffff"><g data-name="Layer 2"><g data-name="alert-circle"><rect width="24" height="24" opacity="0"></rect><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"></path><circle cx="12" cy="16" r="1"></circle><path d="M12 7a1 1 0 0 0-1 1v5a1 1 0 0 0 2 0V8a1 1 0 0 0-1-1z"></path></g></g></svg></div>
    <div class="banner-message"></div>
</div>';
}
add_action('login_init','login_tips');
/**
 * 登录界面验证码验证
 */
function CAPTCHA_CHECK( $user, $password) {
    if (empty($_POST)) {
        return 0;
    }
    if(isset($_POST['yzm']) && !empty(trim($_POST['yzm']))){
        $check = '';
	    include_once('inc/classes/CAPTCHA.php');
	    if (!akina_option('redis_captcha')) {
		    if ( ! isset( $_POST['timestamp'] ) || ! isset( $_POST['id'] ) || ! ctype_xdigit( $_POST['id'] ) || ! ctype_digit( $_POST['timestamp'] ) ) {
			    return new WP_Error( 'prooffail', '<strong>错误</strong>：非法数据' );
		    }
		    $check = Sakura\API\CAPTCHA::check_CAPTCHA($_POST['yzm'],$_POST['timestamp'],$_POST['id']);
	    }else{
		    $check = Sakura\API\CAPTCHA::check_CAPTCHA($_POST['yzm']);
        }
        if (!is_array($check)){
            return new WP_Error('prooffail', '<strong>错误</strong>：内部错误!');
        }
        if($check['code'] == 5){
            return $user;
        }else{
            return new WP_Error('prooffail', '<strong>错误</strong>：'.$check['msg']);
            //return home_url('/wp-admin/');
        }
    }else{
        return new WP_Error('prooffail', '<strong>错误</strong>：验证码为空！');
    }
}
add_filter( 'wp_authenticate_user', 'CAPTCHA_CHECK', 10, 2 );
//add_filter( 'authenticate','CAPTCHA_CHECK',20,3);
/**
 * 忘记密码界面验证码验证
 */
function lostpassword_CHECK( $errors ) {
    if (empty($_POST)) {
        return false;
    }
    if(isset($_POST['yzm']) && !empty(trim($_POST['yzm']))){
        if (!isset($_POST['timestamp']) || !isset($_POST['id']) || !ctype_xdigit($_POST['id']) || !ctype_digit($_POST['timestamp'])){
            return new WP_Error('prooffail', '<strong>错误</strong>：非法数据');
        }
        include_once('inc/classes/CAPTCHA.php');
        $check = Sakura\API\CAPTCHA::check_CAPTCHA($_POST['yzm'],$_POST['timestamp'],$_POST['id']);
        if($check['code'] != 5){
            return $errors->add( 'invalid_department ', '<strong>错误</strong>：'.$check['msg']);
        }
    }else{
        return $errors->add('invalid_department', '<strong>错误</strong>：验证码为空！');
    }
}
 
add_action( 'lostpassword_post', 'lostpassword_CHECK' );
/** 
*   注册界面验证码验证
*/
function registration_CAPTCHA_CHECK($errors, $sanitized_user_login, $user_email) {
    if (empty($_POST)) {
        return new WP_Error();
    }
    if(isset($_POST['yzm']) && !empty(trim($_POST['yzm']))){
        if (!isset($_POST['timestamp']) || !isset($_POST['id']) || !ctype_xdigit($_POST['id']) || !ctype_digit($_POST['timestamp'])){
            return new WP_Error('prooffail', '<strong>错误</strong>：非法数据');
        }
        include_once('inc/classes/CAPTCHA.php');
        // $img = new Sakura\API\CAPTCHA;
        $check = Sakura\API\CAPTCHA::check_CAPTCHA($_POST['yzm'],$_POST['timestamp'],$_POST['id']);
        if($check['code'] == 5){
            return $errors;
        }else{
            return new WP_Error('prooffail', '<strong>错误</strong>：'.$check['msg']);
        }
    }else{
        return new WP_Error('prooffail', '<strong>错误</strong>：验证码为空！');
    }
}
add_filter( 'registration_errors', 'registration_CAPTCHA_CHECK', 2, 3 );
/**
 * 禁用 embeds
 */
if ( !function_exists( 'disable_embeds_init' ) ) :
    function disable_embeds_init(){
        global $wp;
        $wp->public_query_vars = array_diff($wp->public_query_vars, array('embed'));
        remove_action('rest_api_init', 'wp_oembed_register_route');
        add_filter('embed_oembed_discover', '__return_false');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');
        add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
    }
    add_action('init', 'disable_embeds_init', 9999);
 
    function disable_embeds_tiny_mce_plugin($plugins){
        return array_diff($plugins, array('wpembed'));
    }
    function disable_embeds_rewrites($rules){
        foreach ($rules as $rule => $rewrite) {
            if (str_contains($rewrite, 'embed=true')) {
                unset($rules[$rule]);
            }
        }
        return $rules;
    }
    function disable_embeds_remove_rewrite_rules(){
        add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        flush_rewrite_rules();
    }
    register_activation_hook(__FILE__, 'disable_embeds_remove_rewrite_rules');
 
    function disable_embeds_flush_rewrite_rules(){
        remove_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        flush_rewrite_rules();
    }
    register_deactivation_hook(__FILE__, 'disable_embeds_flush_rewrite_rules');
endif;


remove_action( 'wp_head', 'rest_output_link_wp_head');

//自定义邮箱发件人
function new_from_name($email){
    return akina_option('site_name');
}
 
function new_from_email($email) {
    return 'no-reply@' . str_replace(['http://','https://'],'',esc_url(home_url()));
    //return 'no-reply@' . akina_option('email_domain');
}
 
add_filter('wp_mail_from_name', 'new_from_name');
add_filter('wp_mail_from', 'new_from_email');

