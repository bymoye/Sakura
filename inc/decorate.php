<?php
function customizer_css() { ?>
<style type="text/css">
<?php // Style Settings
if ( akina_option('shownav') ) { ?>
.site-top .lower nav {display: block !important;}
<?php } // Style Settings ?>
<?php // theme-skin
if ( akina_option('theme_skin') ) { ?>
.author-profile i , .post-like a , .post-share .show-share , .sub-text , .we-info a , span.sitename , .post-more i:hover , #pagination a:hover , .post-content a:hover , .float-content i:hover{ color: <?php echo akina_option('theme_skin'); ?> }
.feature i ,  .download , .navigator i:hover , .links ul li:before , .ar-time i , span.ar-circle , .object , .comment .comment-reply-link , .siren-checkbox-radio:checked + .siren-checkbox-radioInput:after { background: <?php echo akina_option('theme_skin'); ?> }
::-webkit-scrollbar-thumb { background: <?php echo akina_option('theme_skin'); ?> }
.download , .navigator i:hover , .link-title , .links ul li:hover , #pagination a:hover , .comment-respond input[type='submit']:hover {border-color: <?php echo akina_option('theme_skin'); ?> }
.entry-content a:hover , .site-info a:hover , .comment h4 a , #comments-navi a.prev , #comments-navi a.next , .comment h4 a:hover , .site-top ul li a:hover , .entry-title a:hover , #archives-temp h3 , span.page-numbers.current , .sorry li a:hover , .site-title a:hover , .comment-respond input[type='submit']:hover, blockquote:before, blockquote:after { color: <?php echo akina_option('theme_skin'); ?>; }
.is-active-link::before, .commentbody:not(:placeholder-shown)~.input-label, .commentbody:focus~.input-label {
    background-color: <?php echo akina_option('theme_skin'); ?> !important
}
.commentbody:focus,.comment-respond input:focus {
    border-color: <?php echo akina_option('theme_skin'); ?> !important;
    box-shadow: 0 0 5px <?php echo akina_option('theme_skin'); ?>;
}


.insert-image-tips-hover{ 
    color: <?php echo akina_option('theme_skin'); ?>;
    border: 1px solid <?php echo akina_option('theme_skin'); ?>;
    box-shadow: 0 0 5px <?php echo akina_option('theme_skin'); ?>;
}

.insert-image-tips:hover svg.picture,.insert-image-tips-hover svg.picture,.author-profile svg,.love,svg.caidan:hover{
    fill:<?php echo akina_option('theme_skin'); ?> !important;
}

.site-top ul li a:after {
    background-color: <?php echo akina_option('theme_skin'); ?>
}

<?php if (akina_option('background_style') == 'blur') {$img = get_random_bg_url();?>
       body {
            background-image: url('<?php echo is_array($img) ? $img[2] : $img;?>');
            background-repeat: no-repeat;
            background-position: top right;
            background-size: cover;
        } 

    .top-social_v2 svg:hover,.top-social svg:hover {
    -webkit-transition: fill .5s ease-out;
    transition: fill .5s ease-out;
    fill: <?php echo akina_option('theme_skin'); ?>;
}

        .pattern-center {
            max-width: 900px;
            max-height: 300px;
            margin: auto;
            box-shadow: 3px 1px 5px rgba(0, 0, 0, 0.28);
        }


        .pattern-center header.single-header {
            text-align: center;
            bottom: 0;

        }

        .pattern-center:hover h1.cat-title, .pattern-center:hover h1.entry-title{
            padding-top:0%;
        }

        .single-center .entry-census span img {
            float: none;
            vertical-align: middle;
        }

        .notification span {
            color: #6F6F6F;
        }

        .info-meta {
            border-radius: 8px;
            border: none;
        }

        .info-meta a, .info-meta span {
            color: #6F6F6F;
        }
        #content, .comments .comments-main {
            border-radius: 0 0 10px 10px;
            box-shadow: 3px 1px 5px rgba(0, 0, 0, 0.28);
        }

        .meme_btn, .form-submit .submit, #comments_edit, .notification, #pagination a {
            background-color: rgba(255,255,255,0.3);
        }

        .author-profile p {
            border-top: 1px solid #545454;
            border-bottom: 1px solid #545454;
        }

        .post-footer {
            border-bottom: 1px dashed #545454;
            border-top: 1px dashed #545454;
        }


        .single-center .entry-census {
            padding: 8px 0;
        }

        .headertop-bar::after, .pattern-center::after, .comments, .site-footer {
            background: none;
        }

        .headertop::before {
            position: unset;
        }

        .linkpage li {
            background-color: rgba(0, 0, 0, 0.3);
        }

        .linkpage li a p {
            color: #ddd;
        }
<?php } ?>
        


<?php if(akina_option('entry_content_theme') == "sakura"){ ?>
.entry-content th {
    background-color: <?php echo akina_option('theme_skin'); ?>
}
<?php } ?>
<?php if(akina_option('live_search')){ ?>
.search-form--modal .search-form__inner {
    bottom: unset !important;
    top: 10% !important;
}
<?php } ?>
<?php } // theme-skin ?>
<?php // Custom style
if ( akina_option('site_custom_style') ) {
  echo akina_option('site_custom_style');
} 
// Custom style end ?>
<?php // liststyle
if ( akina_option('list_type') == 'square') { ?>
.feature img{ border-radius: 0px; !important; }
.feature i { border-radius: 0px; !important; }
<?php } // liststyle ?>
<?php // comments
if ( akina_option('toggle-menu') == 'no') { ?>
.comments .comments-main {display:block !important;}
.comments .comments-hidden {display:none !important;}
<?php } // comments ?>
</style>
<?php }
add_action('wp_head','customizer_css');
