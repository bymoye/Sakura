<?php
function font_end_js_control() { 
    $check = fn($a):bool => $a ? true : false;
    $x = akina_option('app_no_jsdelivr_cdn');
    $app_cdn_url = $x ? get_template_directory_uri() : 'https://proxy.nmxc.ltd/gh/bymoye/Sakura';
    $app_file = '/cdn/theme/' . akina_option('entry_content_theme').($x ? '.css' : '.min.css');
    $x = akina_option('jsdelivr_cdn_test');
    $lib_cdn_url = $x ? get_template_directory_uri() : 'https://proxy.nmxc.ltd/gh/bymoye/Sakura';
    $lib_file = '/cdn/css/lib' . ($x ? '.css' : '.min.css');
	wp_add_inline_script('app', 'const mashiro_option = '. json_encode([
    'NProgressON' => $check(akina_option('nprogress_on')),
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
    'live_search' => $check(akina_option('live_search')),
    'land_at_home' => $check(is_home()),
    'baguetteBoxON' => $check(akina_option('image_viewer')),
    'clipboardCopyright' => $check(akina_option('clipboard_copyright')),
    'entry_content_theme_src' => $app_cdn_url . $app_file,
    'entry_content_theme' => akina_option('entry_content_theme'),
    'jsdelivr_css_src' => $lib_cdn_url . $lib_file,
    'windowheight' => 'auto'
    ],JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE),'before');
    wp_add_inline_script('app','const mashiro_global = {};','before');
}
add_action('wp_enqueue_scripts', 'font_end_js_control');