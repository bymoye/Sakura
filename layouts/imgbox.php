<?php

//https://api.mashiro.top/cover

?>
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
                <li><a href="<?php echo akina_option('github', ''); ?>" target="_blank" class="social-github" title="github"><svg class="github"><use xlink:href="#svg_github"/></svg></a></li>
                <?php } ?>	
                <?php if (akina_option('sina')){ ?>
                <li><a href="<?php echo akina_option('sina', ''); ?>" target="_blank" class="social-sina" title="sina"><svg class="weibo"><use xlink:href="#svg_weibo"/></svg></a></li>
                <?php } ?>
                <?php if (akina_option('telegram')){ ?>
                <li><a href="<?php echo akina_option('telegram', ''); ?>" target="_blank" class="social-lofter" title="telegram"><svg class="telegram"><use xlink:href="#svg_telegram"/></svg></a></li>
                <?php } ?>	
                <?php if (akina_option('qq')){ ?>
                <li class="qq"><a href="<?php echo akina_option('qq', ''); ?>" title="Initiate chat ?"><svg class="qq"><use xlink:href="#svg_qq"/></svg></a></li>
                <?php } ?>	
                <?php if (akina_option('qzone')){ ?>
                <li><a href="<?php echo akina_option('qzone', ''); ?>" target="_blank" class="social-qzone" title="qzone"><svg class="qzone"><use xlink:href="#svg_qzone"/></svg></a></li>
                <?php } ?>
                <?php if (akina_option('wechat')){ ?>
                <li class="wechat"><a href="#"><svg class="wechat"><use xlink:href="#svg_wechat"/></svg></a>
                    <div class="wechatInner">
                        <img src="<?php echo akina_option('wechat', ''); ?>" alt="WeChat">
                    </div>
                </li>
                <?php } ?> 
                <?php if (akina_option('lofter')){ ?>
                <li><a href="<?php echo akina_option('lofter', ''); ?>" target="_blank" class="social-lofter" title="lofter"><svg class="lofter"><use xlink:href="#svg_lofter"/></svg></a></li>
                <?php } ?>	
                <?php if (akina_option('bili')){ ?>
                <li><a href="<?php echo akina_option('bili', ''); ?>" target="_blank" class="social-bili" title="bilibili"><svg class="bilibili"><use xlink:href="#svg_bilibili"/></svg></a></li>
                <?php } ?>
                <?php if (akina_option('youku')){ ?>
                <li><a href="<?php echo akina_option('youku', ''); ?>" target="_blank" class="social-youku" title="youku"><svg class="youku"><use xlink:href="#svg_youku"/></svg></a></li>
                <?php } ?>
                <?php if (akina_option('wangyiyun')){ ?>
                <li><a href="<?php echo akina_option('wangyiyun', ''); ?>" target="_blank" class="social-wangyiyun" title="CloudMusic"><svg class="wangyiyun"><use xlink:href="#svg_CloudMusic"/></svg></a></li>
                <?php } ?>
                <?php if (akina_option('twitter')){ ?>
                <li><a href="<?php echo akina_option('twitter', ''); ?>" target="_blank" class="social-wangyiyun" title="Twitter"><svg class="twitter"><use xlink:href="#svg_Twitter"/></svg></a></li>
                <?php } ?>	
                <?php if (akina_option('facebook')){ ?>
                <li><a href="<?php echo akina_option('facebook', ''); ?>" target="_blank" class="social-wangyiyun" title="Facebook"><svg class="facebook"><use xlink:href="#svg_facebook"/></svg></a></li>
                <?php } ?>	
                <?php if (akina_option('jianshu')){ ?>
                <li><a href="<?php echo akina_option('jianshu', ''); ?>" target="_blank" class="social-wangyiyun" title="Jianshu"><svg class="jianshu"><use xlink:href="#svg_jianshu"/></svg></a></li>
                <?php } ?>
                <?php if (akina_option('zhihu')){ ?>
                <li><a href="<?php echo akina_option('zhihu', ''); ?>" target="_blank" class="social-wangyiyun" title="Zhihu"><svg class="zhihu"><use xlink:href="#svg_zhihu"/></svg></a></li>
                <?php } ?>	
                <?php if (akina_option('csdn')){ ?>
                <li><a href="<?php echo akina_option('csdn', ''); ?>" target="_blank" class="social-wangyiyun" title="CSDN"><svg class="csdn"><use xlink:href="#svg_csdn"/></svg></a></li>
                <?php } ?>		
                <?php if (akina_option('email_name') && akina_option('email_domain')){ ?>
                <li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><svg class="email" ><use xlink:href="#svg_email"/></svg></a></li>
                <?php } ?>	
            </div>
            <?php endif; ?>
        </div>
        <?php if (akina_option('social_style')=="v1"): ?>
		<div class="top-social_v1">
		<?php if (akina_option('github')){ ?>
		<li><a href="<?php echo akina_option('github', ''); ?>" target="_blank" class="social-github" title="github"><svg class="github"><use xlink:href="#svg_github"/></svg></a></li>
		<?php } ?>	
		<?php if (akina_option('sina')){ ?>
		<li><a href="<?php echo akina_option('sina', ''); ?>" target="_blank" class="social-sina" title="sina"><svg class="weibo"><use xlink:href="#svg_weibo"/></svg></a></li>
		<?php } ?>
		<?php if (akina_option('telegram')){ ?>
		<li><a href="<?php echo akina_option('telegram', ''); ?>" target="_blank" class="social-lofter" title="telegram"><svg class="telegram"><use xlink:href="#svg_telegram"/></svg></a></li>
		<?php } ?>	
		<?php if (akina_option('qq')){ ?>
		<li class="qq"><a href="<?php echo akina_option('qq', ''); ?>" title="Initiate chat ?"><svg class="qq"><use xlink:href="#svg_qq"/></svg></a></li>
		<?php } ?>	
		<?php if (akina_option('qzone')){ ?>
		<li><a href="<?php echo akina_option('qzone', ''); ?>" target="_blank" class="social-qzone" title="qzone"><svg class="qzone"><use xlink:href="#svg_qzone"/></svg></a></li>
		<?php } ?>
		<?php if (akina_option('wechat')){ ?>
		<li class="wechat"><a href="#"><svg class="wechat"><use xlink:href="#svg_wechat"/></svg></a>
			<div class="wechatInner">
				<img src="<?php echo akina_option('wechat', ''); ?>" alt="WeChat">
			</div>
		</li>
		<?php } ?> 
		<?php if (akina_option('lofter')){ ?>
		<li><a href="<?php echo akina_option('lofter', ''); ?>" target="_blank" class="social-lofter" title="lofter"><svg class="lofter"><use xlink:href="#svg_lofter"/></svg></a></li>
		<?php } ?>	
		<?php if (akina_option('bili')){ ?>
		<li><a href="<?php echo akina_option('bili', ''); ?>" target="_blank" class="social-bili" title="bilibili"><svg class="bilibili"><use xlink:href="#svg_bilibili"/></svg></a></li>
		<?php } ?>
		<?php if (akina_option('youku')){ ?>
		<li><a href="<?php echo akina_option('youku', ''); ?>" target="_blank" class="social-youku" title="youku"><svg class="youku"><use xlink:href="#svg_youku"/></svg></a></li>
		<?php } ?>
		<?php if (akina_option('wangyiyun')){ ?>
		<li><a href="<?php echo akina_option('wangyiyun', ''); ?>" target="_blank" class="social-wangyiyun" title="CloudMusic"><svg class="wangyiyun"><use xlink:href="#svg_CloudMusic"/></svg></a></li>
		<?php } ?>
		<?php if (akina_option('twitter')){ ?>
		<li><a href="<?php echo akina_option('twitter', ''); ?>" target="_blank" class="social-wangyiyun" title="Twitter"><svg class="twitter"><use xlink:href="#svg_Twitter"/></svg></a></li>
		<?php } ?>	
		<?php if (akina_option('facebook')){ ?>
		<li><a href="<?php echo akina_option('facebook', ''); ?>" target="_blank" class="social-wangyiyun" title="Facebook"><svg class="facebook"><use xlink:href="#svg_facebook"/></svg></a></li>
		<?php } ?>	
		<?php if (akina_option('jianshu')){ ?>
		<li><a href="<?php echo akina_option('jianshu', ''); ?>" target="_blank" class="social-wangyiyun" title="Jianshu"><svg class="jianshu"><use xlink:href="#svg_jianshu"/></svg></a></li>
		<?php } ?>
		<?php if (akina_option('zhihu')){ ?>
		<li><a href="<?php echo akina_option('zhihu', ''); ?>" target="_blank" class="social-wangyiyun" title="Zhihu"><svg class="zhihu"><use xlink:href="#svg_zhihu"/></svg></a></li>
		<?php } ?>	
		<?php if (akina_option('csdn')){ ?>
		<li><a href="<?php echo akina_option('csdn', ''); ?>" target="_blank" class="social-wangyiyun" title="CSDN"><svg class="csdn"><use xlink:href="#svg_csdn"/></svg></a></li>
		<?php } ?>		
		<?php if (akina_option('email_name') && akina_option('email_domain')){ ?>
		<li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><svg class="email" ><use xlink:href="#svg_email"/></svg></a></li>
		<?php } ?>	
        <?php endif; ?>
	</div>
	<?php } ?>
</figure>
<?php
echo bgvideo(); //BGVideo 
