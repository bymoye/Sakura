<figure id="centerbg" class="centerbg">
<?php
$Version = akina_option('social_style');
$top_social_Version = 'top-social_' . $Version;
$icon_Version = 'icon_' . $Version . '_svg';
function li(String $href, String $icon,String $icon_Version, String $title){
    return "<li><a href='$href' title='$title' rel='noopener' target='_blank'><i class='$icon_Version svg_$icon'></i></a></li>";
}
?>
<?php if ( !akina_option('focus_infos') ){ ?>
	<div class="focusinfo">
        <?php if (akina_option('focus_logo_text')):?>
        <h1 class="center-text glitch is-glitching Ubuntu-font" data-text="<?php echo akina_option('focus_logo_text', ''); ?>"><?php echo akina_option('focus_logo_text', ''); ?></h1>
   		<?php elseif (akina_option('focus_logo')):?>
	     <div class="header-tou"><a href="<?php bloginfo('url');?>" ><img src="<?php echo akina_option('focus_logo', ''); ?>" draggable="false" alt=""></a></div>
	  	<?php else :?>
         <div class="header-tou"><a href="<?php bloginfo('url');?>"><img src="<?php bloginfo('template_url'); ?>/images/avatar.jpg" draggable="false" alt=""></a></div>	
      	<?php endif; ?>
		<div class="header-info">
            <p><?php echo akina_option('admin_des'); ?></p>
            <?php if($Version =="v1")echo '</div>'?>
            <div class="<?php echo $top_social_Version;?>">
            <?php if (akina_option('github')){
                echo li($href = akina_option('github'), $icon = 'github',$icon_Version, $title = 'GitHub');
            }
            if (akina_option('sina')){
                echo li($href = akina_option('sina'), $icon = 'sina',$icon_Version, $title = 'sina');
            }
            if (akina_option('telegram')){
                echo li($href = akina_option('telegram'), $icon = 'telegram',$icon_Version, $title = 'Telegram');
            }
            if (akina_option('qq')){
                echo li($href = 'tencent://Message/?Uin='.akina_option('qq').'&websiteName=qzone.qq.com&Menu=yes', $icon = 'qq',$icon_Version, $title = 'Initiate chat ?');
            } if (akina_option('qzone')){
                echo li($href = akina_option('qzone'), $icon = 'qzone',$icon_Version, $title = 'Qzone');
            } ?>
            <?php if (akina_option('wechat')){ ?>
                <li class="wechat"><a href="#"><i class="<?php echo $icon_Version;?> svg_wechat"></i></a>
                    <div class="wechatInner">
                        <img src="<?php echo akina_option('wechat', ''); ?>" alt="WeChat">
                    </div>
                </li>
            <?php } ?> 
            <?php if (akina_option('lofter')){
                echo li($href = akina_option('lofter'), $icon = 'lofter',$icon_Version, $title = 'Lofter');
            }
            if (akina_option('bili')){
                echo li($href = akina_option('bili'), $icon = 'bilibili',$icon_Version, $title = 'BiliBili');
            }
            if (akina_option('youku')){
                echo li($href = akina_option('youku'), $icon = 'youku',$icon_Version, $title = 'Youku');
            }
            if (akina_option('wangyiyun')){
                echo li($href = akina_option('wangyiyun'), $icon = 'CloudMusic',$icon_Version, $title = 'CloudMusic');
            }
            if (akina_option('twitter')){
                echo li($href = akina_option('twitter'), $icon = 'Twitter',$icon_Version, $title = 'Twitter');
            }
            if (akina_option('facebook')){
                echo li($href = akina_option('facebook'), $icon = 'facebook',$icon_Version, $title = 'Facebook');
            }
            if (akina_option('jianshu')){
                echo li($href = akina_option('jianshu'), $icon = 'jianshu',$icon_Version, $title = 'Jianshu');
            }
            if (akina_option('zhihu')){
                echo li($href = akina_option('zhihu'), $icon = 'zhihu',$icon_Version, $title = 'Zhihu');
            }
            if (akina_option('csdn')){
                echo li($href = akina_option('csdn'), $icon = 'csdn',$icon_Version, $title = 'CSDN');
            }
            if (akina_option('email_name') && akina_option('email_domain')){
                echo li($href = 'mailto:'.akina_option('email_name').'@'.akina_option('email_domain'), $icon = 'email',$icon_Version, $title = 'Email');
            } ?>	
            <?php if($Version =="v2")echo '</div>'?>
        </div>
	</div>
	<?php } ?>
</figure>
<?php
echo bgvideo(); //BGVideo 
