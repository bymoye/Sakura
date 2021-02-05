<?php
function font_end_js_control() { ?>
<script>
/*Initial Variables*/
var mashiro_option = new Object();
var mashiro_global = new Object();
mashiro_option.NProgressON = <?php if ( akina_option('nprogress_on') ){ echo 'true'; } else { echo 'false'; } ?>;
mashiro_option.email_domain = "<?php echo akina_option('email_domain', ''); ?>";
mashiro_option.email_name = "<?php echo akina_option('email_name', ''); ?>";
mashiro_option.cookie_version_control = "<?php echo akina_option('cookie_version', ''); ?>";
mashiro_option.qzone_autocomplete = false;
mashiro_option.site_name = "<?php echo akina_option('site_name', ''); ?>";
mashiro_option.author_name = "<?php echo akina_option('author_name', ''); ?>";
mashiro_option.template_url = "<?php echo get_template_directory_uri(); ?>";
mashiro_option.site_url = "<?php echo site_url(); ?>";
mashiro_option.qq_api_url = "https://api.nmxc.ltd/x"; 
mashiro_option.qq_avatar_api_url = "https://api.nmxc.ltd/x";
mashiro_option.live_search = <?php if ( akina_option('live_search') ){ echo 'true'; } else { echo 'false'; } ?>;
<?php if( is_home() ){ ?>
mashiro_option.land_at_home = true;
<?php }else {?>
mashiro_option.land_at_home = false;
<?php } ?>
<?php if(akina_option('image_viewer') == 0){ ?>
mashiro_option.baguetteBoxON = false;
<?php }else {?>
mashiro_option.baguetteBoxON = true;
<?php } ?>
<?php if(akina_option('clipboard_copyright') == 0){ ?>
mashiro_option.clipboardCopyright = false;
<?php }else {?>
mashiro_option.clipboardCopyright = true;
<?php } ?>
<?php if (akina_option('app_no_jsdelivr_cdn')) {?>
<?php if(akina_option('entry_content_theme') == "sakura"){ ?>
mashiro_option.entry_content_theme_src = "<?php echo get_template_directory_uri() ?>/cdn/theme/sakura.css";
<?php }elseif(akina_option('entry_content_theme') == "github") {?>
mashiro_option.entry_content_theme_src = "<?php echo get_template_directory_uri() ?>/cdn/theme/github.css";
<?php } ?>
<?php } else {if(akina_option('entry_content_theme') == "sakura"){?>
mashiro_option.entry_content_theme_src = "https://cdn.jsdelivr.net/combine/gh/bymoye/Sakura@0.0.1/cdn/theme/sakura.min.css";
<?php }elseif(akina_option('entry_content_theme') == "github") {?>
mashiro_option.entry_content_theme_src = "https://cdn.jsdelivr.net/combine/gh/bymoye/Sakura@0.0.1/cdn/theme/github.min.css";
<?php } ?>
<?php } ?>
mashiro_option.entry_content_theme = "<?php echo akina_option('entry_content_theme'); ?>";
<?php if(akina_option('jsdelivr_cdn_test')){ ?>
mashiro_option.jsdelivr_css_src = "<?php echo get_template_directory_uri() ?>/cdn/css/lib.css";
<?php } else { ?>
mashiro_option.jsdelivr_css_src = "https://cdn.jsdelivr.net/gh/bymoye/Sakura@<?php echo NMX_VERSION; ?>/cdn/css/lib.min.css";
<?php } ?>
<?php
$imgurl = DEFAULT_FEATURE_IMAGE();
if(is_array($imgurl)){
    $img_url = $imgurl[2];
}else{
    $img_url = $imgurl;
}?>
mashiro_option.cover_api = "<?php echo $img_url; ?>";
mashiro_option.windowheight = /Mobile|Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent) ? 'auto' : 'auto';
/*End of Initial Variables*/
</script>
<?php }
add_action('wp_head', 'font_end_js_control');
