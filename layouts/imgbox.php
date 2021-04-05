<figure id="centerbg" class="centerbg">
<?php if ( !akina_option('focus_infos') ){ ?>
	<div class="focusinfo">
        <?php if (akina_option('focus_logo_text')):?>
        <h1 class="center-text glitch is-glitching Ubuntu-font" data-text="<?php echo akina_option('focus_logo_text', ''); ?>"><?php echo akina_option('focus_logo_text', ''); ?></h1>
   		<?php elseif (akina_option('focus_logo')):?>
	     <div class="header-tou"><a href="<?php bloginfo('url');?>" ><img src="<?php echo akina_option('focus_logo', ''); ?>"></a></div>
	  	<?php else :?>
         <div class="header-tou" ><a href="<?php bloginfo('url');?>"><img src="<?php bloginfo('template_url'); ?>/images/avatar.jpg"></a></div>	
      	<?php endif; ?>
		<div class="header-info">
            <p><?php echo akina_option('admin_des', 'Hi, Mashiro?'); ?></p>
        <?php if (akina_option('social_style')=="v2"): ?>
            <div class="top-social_v2">
            <?php if (akina_option('github')){ ?>
                <li><a href="<?php echo akina_option('github', ''); ?>" target="_blank" title="github"><i class="icon_v2_svg" style="--svg-name: var(--svg_github);--color: #0073aa;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('sina')){ ?>
                <li><a href="<?php echo akina_option('sina', ''); ?>" target="_blank" title="sina"><i class="icon_v2_svg" style="--svg-name: var(--svg_weibo);--color: red;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('telegram')){ ?>
                <li><a href="<?php echo akina_option('telegram', ''); ?>" target="_blank" title="telegram"><i class="icon_v2_svg" style="--svg-name: var(--svg_telegram);--color: #0088cc;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('qq')){ ?>
                <li class="qq"><a href="<?php echo akina_option('qq', ''); ?>" title="Initiate chat ?"><i class="icon_v2_svg" style="--svg-name: var(--svg_qq);--color: #0073aa;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('qzone')){ ?>
                <li><a href="<?php echo akina_option('qzone', ''); ?>" target="_blank" title="qzone"><i class="icon_v2_svg" style="--svg-name: var(--svg_qzone);--color: #ffbf00;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('wechat')){ ?>
                <li class="wechat"><a href="#"><i class="icon_v2_svg" style="--svg-name: var(--svg_wechat);--color: #02bb0e;"></i></a>
                    <div class="wechatInner">
                        <img src="<?php echo akina_option('wechat', ''); ?>" alt="WeChat">
                    </div>
                </li>
            <?php } ?> 
            <?php if (akina_option('lofter')){ ?>
                <li><a href="<?php echo akina_option('lofter', ''); ?>" target="_blank" title="lofter"><i class="icon_v2_svg" style="--svg-name: var(--svg_lofter);--color: #26706c;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('bili')){ ?>
                <li><a href="<?php echo akina_option('bili', ''); ?>" target="_blank" title="bilibili"><i class="icon_v2_svg" style="--svg-name: var(--svg_bilibili);--color: #f689aa;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('youku')){ ?>
                <li><a href="<?php echo akina_option('youku', ''); ?>" target="_blank" title="youku"><i class="icon_v2_svg" style="--svg-name: var(--svg_youku);--color: #1aaba8;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('wangyiyun')){ ?>
                <li><a href="<?php echo akina_option('wangyiyun', ''); ?>" target="_blank" title="CloudMusic"><i class="icon_v2_svg" style="--svg-name: var(--svg_CloudMusic);--color: red;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('twitter')){ ?>
                <li><a href="<?php echo akina_option('twitter', ''); ?>" target="_blank" title="Twitter"><i class="icon_v2_svg" style="--svg-name: var(--svg_Twitter);--color: #1ab2e8;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('facebook')){ ?>
                <li><a href="<?php echo akina_option('facebook', ''); ?>" target="_blank" title="Facebook"><i class="icon_v2_svg" style="--svg-name: var(--svg_facebook);--color: #405d9b;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('jianshu')){ ?>
                <li><a href="<?php echo akina_option('jianshu', ''); ?>" target="_blank" title="Jianshu"><i class="icon_v2_svg" style="--svg-name: var(--svg_jianshu);--color: #e16049;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('zhihu')){ ?>
                <li><a href="<?php echo akina_option('zhihu', ''); ?>" target="_blank" title="Zhihu"><i class="icon_v2_svg" style="--svg-name: var(--svg_zhihu);--color: #eae9e7;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('csdn')){ ?>
                <li><a href="<?php echo akina_option('csdn', ''); ?>" target="_blank" title="CSDN"><i class="icon_v2_svg" style="--svg-name: var(--svg_csdn);--color: #d51f07;"></i></a></li>
            <?php } ?>		
            <?php if (akina_option('email_name') && akina_option('email_domain')){ ?>
                <li><a onclick="mail_me()" title="E-mail"><i class="icon_v2_svg" style="--svg-name: var(--svg_email);--color: #ffbf00;"></i></a></li>
            <?php } ?>	
            </div>
        <?php endif; ?>
        </div>
        <?php if (akina_option('social_style')=="v1"): ?>
		    <div class="top-social_v1">
		    <?php if (akina_option('github')){ ?>
               <li><a href="<?php echo akina_option('github', ''); ?>" target="_blank" title="github"><i class="icon_v1_svg" style="--svg-name: var(--svg_github);--color: #0073aa;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('sina')){ ?>
                <li><a href="<?php echo akina_option('sina', ''); ?>" target="_blank" title="sina"><i class="icon_v1_svg" style="--svg-name: var(--svg_weibo);--color: red;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('telegram')){ ?>
                <li><a href="<?php echo akina_option('telegram', ''); ?>" target="_blank" title="telegram"><i class="icon_v1_svg" style="--svg-name: var(--svg_telegram);--color: #0088cc;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('qq')){ ?>
                <li class="qq"><a href="<?php echo akina_option('qq', ''); ?>" title="Initiate chat ?"><i class="icon_v1_svg" style="--svg-name: var(--svg_qq);--color: #0073aa;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('qzone')){ ?>
                <li><a href="<?php echo akina_option('qzone', ''); ?>" target="_blank" title="qzone"><i class="icon_v1_svg" style="--svg-name: var(--svg_qzone);--color: #ffbf00;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('wechat')){ ?>
                <li class="wechat"><a href="#"><i class="icon_v1_svg" style="--svg-name: var(--svg_wechat);--color: #02bb0e;"></i></a>
                    <div class="wechatInner">
                        <img src="<?php echo akina_option('wechat', ''); ?>" alt="WeChat">
                    </div>
                </li>
            <?php } ?> 
            <?php if (akina_option('lofter')){ ?>
                <li><a href="<?php echo akina_option('lofter', ''); ?>" target="_blank" title="lofter"><i class="icon_v1_svg" style="--svg-name: var(--svg_lofter);--color: #26706c;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('bili')){ ?>
                <li><a href="<?php echo akina_option('bili', ''); ?>" target="_blank" title="bilibili"><i class="icon_v1_svg" style="--svg-name: var(--svg_bilibili);--color: #f689aa;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('youku')){ ?>
                <li><a href="<?php echo akina_option('youku', ''); ?>" target="_blank" title="youku"><i class="icon_v1_svg" style="--svg-name: var(--svg_youku);--color: #1aaba8;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('wangyiyun')){ ?>
                <li><a href="<?php echo akina_option('wangyiyun', ''); ?>" target="_blank" title="CloudMusic"><i class="icon_v1_svg" style="--svg-name: var(--svg_CloudMusic);--color: red;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('twitter')){ ?>
                <li><a href="<?php echo akina_option('twitter', ''); ?>" target="_blank" title="Twitter"><i class="icon_v1_svg" style="--svg-name: var(--svg_Twitter);--color: #1ab2e8;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('facebook')){ ?>
                <li><a href="<?php echo akina_option('facebook', ''); ?>" target="_blank" title="Facebook"><i class="icon_v1_svg" style="--svg-name: var(--svg_facebook);--color: #405d9b;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('jianshu')){ ?>
                <li><a href="<?php echo akina_option('jianshu', ''); ?>" target="_blank" title="Jianshu"><i class="icon_v1_svg" style="--svg-name: var(--svg_jianshu);--color: #e16049;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('zhihu')){ ?>
                <li><a href="<?php echo akina_option('zhihu', ''); ?>" target="_blank" title="Zhihu"><i class="icon_v1_svg" style="--svg-name: var(--svg_zhihu);--color: #eae9e7;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('csdn')){ ?>
                <li><a href="<?php echo akina_option('csdn', ''); ?>" target="_blank" title="CSDN"><i class="icon_v1_svg" style="--svg-name: var(--svg_csdn);--color: #d51f07;"></i></a></li>
            <?php } ?>		
            <?php if (akina_option('email_name') && akina_option('email_domain')){ ?>
                <li><a onclick="mail_me()" title="E-mail"><i class="icon_v1_svg" style="--svg-name: var(--svg_email);--color: #ffbf00;"></i></a></li>
            <?php } ?>	
        <?php endif; ?>
	</div>
	<?php } ?>
</figure>
<?php
echo bgvideo(); //BGVideo 
