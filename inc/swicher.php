<?php
function font_end_js_control() { 
    function check($a){
        if($a){
            return true;
        }
        return false;
    }
    $x = akina_option('app_no_jsdelivr_cdn');
    $entry_content_theme = akina_option('entry_content_theme','sakura');
    $app_cdn_url = $x ? get_template_directory_uri() : 'https://proxy.nmxc.ltd/gh/bymoye/Sakura@1.3.7';
    $app_file = "/cdn/theme/{$entry_content_theme}.css";
    $x = akina_option('jsdelivr_cdn_test');
    $lib_cdn_url = $x ? get_template_directory_uri() : 'https://proxy.nmxc.ltd/gh/bymoye/Sakura';
    $lib_file = '/cdn/css/lib' . ($x ? '.css' : '.min.css');
    //Poi
    $mv_live = akina_option('focus_mvlive') ? 'open' : 'close';
	$movies = akina_option('focus_amv') ? ['url' => akina_option('amv_url'), 'name' => akina_option('amv_title'), 'live' => $mv_live] : 'close';
	$auto_height = akina_option('focus_height') ? 'fixed' : 'auto';
	wp_add_inline_script('app', 'const nazo_option = '. json_encode([
    'global' => [],
    'NProgressON' => check(akina_option('nprogress_on')),
    'mail_domain' => akina_option('email_domain', ''),
    'email_name' => akina_option('email_name', ''),
    'cookie_version_control' => akina_option('cookie_version', ''),
    'qzone_autocomplete' => false,
    'site_name' => akina_option('site_name', ''),
    'author_name' => akina_option('author_name', ''),
    'template_url' => get_template_directory_uri(),
    'site_url' => site_url(),
    'qq_api_url' => akina_option('qq_api_url'),
    'qq_avatar_api_url' => akina_option('qq_avatar_api_url'),
    'live_search' => check(akina_option('live_search')),
    'land_at_home' => check(is_home()),
    'baguetteBoxON' => check(akina_option('image_viewer')),
    'clipboardCopyright' => check(akina_option('clipboard_copyright')),
    'entry_content_theme_src' => $app_cdn_url . $app_file,
    'entry_content_theme' => $entry_content_theme,
    'jsdelivr_css_src' => $lib_cdn_url . $lib_file,
    // Poi
    'pjax' => akina_option('poi_pjax'),
    'movies' => $movies,
    'windowheight' => $auto_height,
    'ajaxurl' => admin_url('admin-ajax.php'),
    'order' => get_option('comment_order'), // ajax comments
    'formpostion' => 'bottom', // ajax comments 默认为bottom，如果你的表单在顶部则设置为top。
    'api' => esc_url_raw(rest_url()),
    'nonce' => wp_create_nonce('wp_rest'),
    'google_analytics_id' => akina_option('google_analytics_id', '')
    ],JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE),'before');
}
add_action('wp_enqueue_scripts', 'font_end_js_control');