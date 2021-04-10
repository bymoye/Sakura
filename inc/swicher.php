<?php
function font_end_js_control() { 
    $check = fn($a) => $a ? 'true' : 'false';
    $x = akina_option('app_no_jsdelivr_cdn');
    $app_cdn_url = $x ? get_template_directory_uri() : 'https://cdn.jsdelivr.net/gh/bymoye/Sakura';
    $app_file = '/cdn/theme/' . akina_option('entry_content_theme').($x ? '.css' : 'min.css');
    $x = akina_option('jsdelivr_cdn_test');
    $lib_cdn_url = $x ? get_template_directory_uri() : 'https://cdn.jsdelivr.net/gh/bymoye/Sakura';
    $lib_file = '/cdn/css/lib' . ($x ? '.css' : 'min.css');
    ?>
<script>
/*Initial Variables*/
const mashiro_option = {
      NProgressON : <?php echo $check(akina_option('nprogress_on'));?>,
      mail_domain : "<?php echo akina_option('email_domain', ''); ?>",
      email_name : "<?php echo akina_option('email_name', ''); ?>",
      cookie_version_control : "<?php echo akina_option('cookie_version', ''); ?>",
      qzone_autocomplete : false,
      site_name : "<?php echo akina_option('site_name', ''); ?>",
      author_name : "<?php echo akina_option('author_name', ''); ?>",
      template_url : "<?php echo get_template_directory_uri(); ?>",
      site_url : "<?php echo site_url(); ?>",
      qq_api_url : <?php echo '"',akina_option('qq_api_url'),'"';?>,
      qq_avatar_api_url : <?php echo '"',akina_option('qq_avatar_api_url'),'"';?>,
      live_search : <?php echo $check(akina_option('live_search')) ?>,
      land_at_home : <?php echo $check(is_home()) ?>,
      themebg : "<?php $img = get_random_bg_url();echo is_array($img) ? $img[2] : $img;?>",
      baguetteBoxON : <?php echo $check(akina_option('image_viewer')) ?>,
      clipboardCopyright : <?php echo $check(akina_option('clipboard_copyright')) ?>,
      entry_content_theme_src : <?php echo '"',$app_cdn_url,$app_file,'"';?>,
      entry_content_theme : "<?php echo akina_option('entry_content_theme'); ?>",
      jsdelivr_css_src : <?php echo '"',$lib_cdn_url,$lib_file,'"';?>,
      windowheight : 'auto'
};
const mashiro_global = {};
/*End of Initial Variables*/
</script>
<?php }
add_action('wp_head', 'font_end_js_control');
