<?php
function customizer_css() {
  $head_css = '';
  if ( akina_option('shownav') ) {
    $head_css .='.site-top .lower nav {display: block !important;}';
  }
  if ( akina_option('theme_skin') ) {
    $head_css .= ':root{--themecolor:'.akina_option('theme_skin').';}';
  }
  if(akina_option('entry_content_theme') == "sakura"){
    $head_css .= '.entry-content th {background-color: '.akina_option('theme_skin').';}';
  }
  if(akina_option('live_search')){
    $head_css .= '
    .search-form--modal .search-form__inner {
        bottom: unset !important;
        top: 10% !important;
    }';
  }
  if ( akina_option('site_custom_style') ) {
    $head_css .= akina_option('site_custom_style');
  } 
  if ( akina_option('list_type') == 'square') {
    $head_css .= '
    .feature img{ border-radius: 0px; !important; }
    .feature i { border-radius: 0px; !important; }';
    }
  if ( akina_option('toggle-menu') == 'no') {
    $head_css .= '
    .comments .comments-main {display:block !important;}
    .comments .comments-hidden {display:none !important;}
    ';}?>
  
    <style type="text/css" id= "sakura_css_inline-css" nonce="<?php echo wp_create_nonce('wp_rest')?>">
    .headertop{display:<?php echo !is_home() ? 'none' : 'block'?>}
    <?php echo $head_css;?>
    </style>
<?php }
add_action('wp_head','customizer_css');