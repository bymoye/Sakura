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
.feature i , /*.feature-title span ,*/ .download , .navigator i:hover , .links ul li:before , .ar-time i , span.ar-circle , .object , .comment .comment-reply-link , .siren-checkbox-radio:checked + .siren-checkbox-radioInput:after { background: <?php echo akina_option('theme_skin'); ?> }
::-webkit-scrollbar-thumb { background: <?php echo akina_option('theme_skin'); ?> }
.download , .navigator i:hover , .link-title , .links ul li:hover , #pagination a:hover , .comment-respond input[type='submit']:hover { border-color: <?php echo akina_option('theme_skin'); ?> }
.entry-content a:hover , .site-info a:hover , .comment h4 a , #comments-navi a.prev , #comments-navi a.next , .comment h4 a:hover , .site-top ul li a:hover , .entry-title a:hover , #archives-temp h3 , span.page-numbers.current , .sorry li a:hover , .site-title a:hover , i.iconfont.js-toggle-search.iconsearch:hover , .comment-respond input[type='submit']:hover, blockquote:before, blockquote:after { color: <?php echo akina_option('theme_skin'); ?>; }

#aplayer-float .aplayer-lrc-current { color: <?php echo akina_option('theme_skin'); ?> !important}

.is-active-link::before, .commentbody:not(:placeholder-shown)~.input-label, .commentbody:focus~.input-label {
    background-color: <?php echo akina_option('theme_skin'); ?> !important
}

.commentbody:focus,.comment-respond input:focus {
    border-color: <?php echo akina_option('theme_skin'); ?> !important;
    box-shadow: 0 0 5px <?php echo akina_option('theme_skin'); ?>;
}

.author-profile svg,.love,svg.caidan:hover {
    fill: <?php echo akina_option('theme_skin'); ?> !important
}
.aplayer-thumb,.aplayer-played{
    background:<?php echo akina_option('theme_skin'); ?> !important
}
.insert-image-tips:hover, .insert-image-tips-hover{ 
    color: <?php echo akina_option('theme_skin'); ?>;
    border: 1px solid <?php echo akina_option('theme_skin'); ?>;
    box-shadow: 0 0 5px <?php echo akina_option('theme_skin'); ?>;
}

.insert-image-tips:hover svg.picture,.insert-image-tips-hover svg.picture {
    fill:<?php echo akina_option('theme_skin'); ?>;
}

.site-top ul li a:after {
    background-color: <?php echo akina_option('theme_skin'); ?>
}

.butterBar-message {
    background: <?php echo akina_option('theme_skin'); ?> !important
}

#nprogress .spinner-icon{ 
    border-top-color: <?php echo akina_option('theme_skin'); ?>; 
    border-left-color: <?php echo akina_option('theme_skin'); ?>
}

#nprogress .bar {
    background: <?php echo akina_option('theme_skin'); ?>
}

<?php
        if (akina_option('background_style') == 'blur') { ?>
        body::before {
            content: '';
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            will-change: transform;
            z-index: -1;
            <?php $img = get_random_bg_url(); (is_array($img)) ? $img_url = $img[2] : $img_url = $img;?>
            background-image: url('<?php echo $img_url; ?>');
            background-repeat: no-repeat;
            background-position: top right;
            background-size: cover;
            transition: all .8s ease;
        }

        .aplayer .aplayer-list ol::-webkit-scrollbar-thumb {
        background-color: <?php echo akina_option('theme_skin'); ?> !important;
    }
    .top-social_v2 svg:hover {
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

        .pattern-center::before {
            z-index: 1;
        }

        .pattern-center::after {
            z-index: 2;
        }

        .pattern-center header.single-header {
            text-align: center;
            bottom: 0;
        }

        .pattern-center:hover header.single-header {
        position:absolute;
        background-color: rgba(136, 136, 136, 0.5);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px); 
        transform:translateY(80px);
        transition:all .5s linear;
        }

        .single-center .entry-census span img {
            float: none;
            vertical-align: middle;
        }

        #content, .notification, .comments .comments-main, .info-meta, .notice {
            background: rgba(255, 255, 255, .53);
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

        .single-center::before {
            background: rgba(0, 0, 0, 0);
        }

        .single-center .entry-census {
            padding: 8px 0;
        }

        .headertop-bar::after, .pattern-center::after, .comments, .site-footer {
            background: none;
        }

        .pattern-center .pattern-attachment-img {
            -webkit-transition: -webkit-all .5s ease-out;
            -webkit-transition: all .5s ease-out;
            transition: all .5s ease-out;
            -moz-filter: blur(20px);
            -webkit-filter: blur(20px);
            -o-filter: blur(20px);
            -ms-filter: blur(20px);
            filter: blur(20px);
        }

        .pattern-center:hover .pattern-attachment-img {
            -webkit-transform: scale(1.07);
            transform: scale(1.07);
            -ms-transform: scale(1.07);
            -moz-filter: blur(0px);
            -webkit-filter: blur(0px);
            -o-filter: blur(0px);
            -ms-filter: blur(0px);
            filter: blur(0px);
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
        @media (max-width: 860px) {
        <?php if (akina_option('mobile_blur') == '0') { ?>
            body::before {
                background-image: none;
            }

            .headertop-bar::after, .pattern-center::after {
                background: #fff !important;
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
.post-list-thumb{opacity: 0}
.post-list-show {opacity: 1}
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
add_action('wp_head', 'customizer_css');
