<figure id="centerbg" class="centerbg">
<?php
$Version = akina_option('social_style');
$top_social_Version = 'top-social_' . $Version;
$icon_Version = 'icon_' . $Version . '_svg';
?>
<?php if ( !akina_option('focus_infos') ){ ?>
	<div class="focusinfo">
        <?php if (akina_option('focus_logo_text')):?>
        <h1 class="center-text glitch is-glitching Ubuntu-font" data-text="<?php echo akina_option('focus_logo_text', ''); ?>"><?php echo akina_option('focus_logo_text', ''); ?></h1>
   		<?php elseif (akina_option('focus_logo')):?>
	     <div class="header-tou"><a href="<?php bloginfo('url');?>" ><img src="<?php echo akina_option('focus_logo', ''); ?>" alt=""></a></div>
	  	<?php else :?>
         <div class="header-tou"><a href="<?php bloginfo('url');?>"><img src="<?php bloginfo('template_url'); ?>/images/avatar.jpg" alt=""></a></div>	
      	<?php endif; ?>
		<div class="header-info">
            <p><?php echo akina_option('admin_des'); ?></p>
            <?php if($Version =="v1")echo '</div>'?>
            <div class="<?php echo $top_social_Version;?>">
            <?php if (akina_option('github')){ ?>
                <li><a href="<?php echo akina_option('github', ''); ?>" rel="noopener" target="_blank" title="github"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_github);--color: #0073aa;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('sina')){ ?>
                <li><a href="<?php echo akina_option('sina', ''); ?>" rel="noopener" target="_blank" title="sina"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_sina);--color: red;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('telegram')){ ?>
                <li><a href="<?php echo akina_option('telegram', ''); ?>" rel="noopener" target="_blank" title="telegram"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_telegram);--color: #0088cc;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('qq')){ ?>
                <li class="qq"><a href="<?php echo akina_option('qq', ''); ?>" rel="noopener" title="Initiate chat ?"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_qq);--color: #0073aa;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('qzone')){ ?>
                <li><a href="<?php echo akina_option('qzone', ''); ?>" rel="noopener" target="_blank" title="qzone"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_qzone);--color: #ffbf00;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('wechat')){ ?>
                <li class="wechat"><a href="#"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_wechat);--color: #02bb0e;"></i></a>
                    <div class="wechatInner">
                        <img src="<?php echo akina_option('wechat', ''); ?>" alt="WeChat">
                    </div>
                </li>
            <?php } ?> 
            <?php if (akina_option('lofter')){ ?>
                <li><a href="<?php echo akina_option('lofter', ''); ?>" rel="noopener" target="_blank" title="lofter"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_lofter);--color: #26706c;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('bili')){ ?>
                <li><a href="<?php echo akina_option('bili', ''); ?>" rel="noopener" target="_blank" title="bilibili"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_bilibili);--color: #f689aa;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('youku')){ ?>
                <li><a href="<?php echo akina_option('youku', ''); ?>" rel="noopener" target="_blank" title="youku"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_youku);--color: #1aaba8;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('wangyiyun')){ ?>
                <li><a href="<?php echo akina_option('wangyiyun', ''); ?>" rel="noopener" target="_blank" title="CloudMusic"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_CloudMusic);--color: red;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('twitter')){ ?>
                <li><a href="<?php echo akina_option('twitter', ''); ?>" rel="noopener" target="_blank" title="Twitter"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_Twitter);--color: #1ab2e8;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('facebook')){ ?>
                <li><a href="<?php echo akina_option('facebook', ''); ?>" rel="noopener" target="_blank" title="Facebook"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_facebook);--color: #405d9b;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('jianshu')){ ?>
                <li><a href="<?php echo akina_option('jianshu', ''); ?>" rel="noopener" target="_blank" title="Jianshu"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_jianshu);--color: #e16049;"></i></a></li>
            <?php } ?>
            <?php if (akina_option('zhihu')){ ?>
                <li><a href="<?php echo akina_option('zhihu', ''); ?>" rel="noopener" target="_blank" title="Zhihu"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_zhihu);--color: #eae9e7;"></i></a></li>
            <?php } ?>	
            <?php if (akina_option('csdn')){ ?>
                <li><a href="<?php echo akina_option('csdn', ''); ?>" rel="noopener" target="_blank" title="CSDN"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_csdn);--color: #d51f07;"></i></a></li>
            <?php } ?>		
            <?php if (akina_option('email_name') && akina_option('email_domain')){ ?>
                <li><a onclick="mail_me()" rel="noopener" title="E-mail"><i class=<?php echo $icon_Version;?> style="--svg-name: var(--svg_email);--color: #ffbf00;"></i></a></li>
            <?php } ?>	
            <?php if($Version =="v2")echo '</div>'?>
        </div>
	</div>
	<?php } ?>
</figure>
<?php
echo bgvideo(); //BGVideo 
