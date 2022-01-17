<figure id="centerbg" class="centerbg">
<?php
$Version = akina_option('social_style');
$icon_Version = 'icon_' . $Version . '_svg';
?>
<?php if ( !akina_option('focus_infos') ){ ?>
	<div class="focusinfo">
        <?php if (akina_option('focus_logo_text')): ?>
            <h1 class="center-text glitch is-glitching Ubuntu-font" data-text="<?=akina_option('focus_logo_text', '')?>">
                <?=akina_option('focus_logo_text', '')?>
            </h1>
        <?php else: ?>
            <div class="header-tou">
                <a href="<?php bloginfo('url')?>">
                    <img src="<?=akina_option('focus_logo', '') ?: (bloginfo('template_url').'/images/avatar.jpg')?>" alt="">
                </a>
            </div>
        <?php endif; ?>
		<div class="header-info">
            <p><?=akina_option('admin_des'); ?></p>
            <?php if ($Version === 'v1'):?>
                </div>
            <?php endif; ?>
            <div class="top-social_<?=$Version;?>">
            <?php 
                include('all_opt.php');
                foreach($opt as $key => $value):
                    if(!empty($value['href'])):?>
                        <li>
                            <a href="<?=$value['href']?>" rel="noopener" target="_blank" title="<?=$value['title'] ?? $key?>">
                                <i class="<?=$icon_Version?>" style="--svg-name: var(--svg_<?=$value['icon'] ?? $key?>);--color: <?=$value['color']?>;"></i>
                            </a>
                        </li>
                    <?php endif;
                endforeach;
            if (akina_option('wechat')): ?>
                <li class="wechat"><a href="#"><i class=<?=$icon_Version;?> style="--svg-name: var(--svg_wechat);--color: #02bb0e;"></i></a>
                    <div class="wechatInner">
                        <img src="<?=akina_option('wechat', ''); ?>" alt="WeChat">
                    </div>
                </li>
            <?php endif;
            if (akina_option('email_name') && akina_option('email_domain')){ ?>
                <li><a onclick="mail_me()" rel="noopener" title="E-mail"><i class=<?=$icon_Version;?> style="--svg-name: var(--svg_email);--color: #ffbf00;"></i></a></li>
            <?php } ?>	
            <?php if($Version ==='v2'):?>
                </div>
            <?php endif;?>
        </div>
	</div>
	<?php } ?>
</figure>
<?php
echo bgvideo(); //BGVideo 