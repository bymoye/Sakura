<?php
function customizer_css() { ?>
<style>
<?php // Style Settings
if ( akina_option('shownav') ) { ?>
.site-top .lower nav {display: block !important;}
<?php } // Style Settings ?>
<?php // theme-skin
if ( akina_option('theme_skin') ) { ?>
:root{
    --themecolor:<?php echo akina_option('theme_skin'); ?>;
}
<?php } ?>
<?php if(akina_option('entry_content_theme') == "sakura"){ ?>
.entry-content th {background-color: var(--themecolor);}
<?php } ?>
<?php if(akina_option('live_search')){ ?>
.search-form--modal .search-form__inner {
    bottom: unset !important;
    top: 10% !important;
}
<?php } // theme-skin ?>
<?php // Custom style
if ( akina_option('site_custom_style') ) {
  echo akina_option('site_custom_style');
} 
// Custom style end ?>
<?php // liststyle
if ( akina_option('list_type') == 'square') { ?>
.feature img,.feature i{ border-radius: 0px; !important; }
<?php } // liststyle ?>
<?php // comments
if ( akina_option('toggle-menu') == 'no') { ?>
.comments .comments-main {display:block !important;}
.comments .comments-hidden {display:none !important;}
<?php } // comments ?>
</style>
<?php }
add_action('wp_head','customizer_css');
