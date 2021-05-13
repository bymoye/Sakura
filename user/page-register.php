<?php 
/**
 Template Name: Register
 */

get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if(akina_option('ex_register_open')) : ?>
		<?php if(!is_user_logged_in()){ ?>
			<div class="ex-register">
				<form action="<?php echo home_url(); ?>/wp-login.php?action=register" method="post">  
					<p><input type="text" name="user_login" tabindex="1" id="user_login" class="input" value="<?php if(!empty($sanitized_user_login)) echo $sanitized_user_login; ?>" placeholder="用户名" required /></p>
					<p><input type="text" name="user_email" tabindex="2" id="user_email" class="input" value="<?php if(!empty($user_email)) echo $user_email; ?>" size="25" placeholder="电子邮箱" required /></p>
					<p><img id="captchaimg" width="120" height="40" src=""><input type="text" name="yzm" id="yzm" class="input" value="" size="20" tabindex="4" placeholder="请输入验证码" required><input type="hidden" name="timestamp" value=""><input type="hidden" name="id" value=""></p>
					<?php if(!empty($error)) { echo '<p class="user-error">'.$error.'</p>'; } ?>
					<input class="button register-button" name="submit" type="submit" value="<?php _e("Sign up","sakura")/*注 册*/?>">
				</form>
			</div>
			<script>
			{
				const get_captcha = ele=>fetch('/wp-json/sakura/v1/captcha/create')
									.then(response=>response.json())
									.then(json=>{
										ele.src = json['data'];
										document.querySelector("input[name='timestamp']").value = json["time"];
                						document.querySelector("input[name='id']").value = json["id"];
									}),
					captcha = document.getElementById("captchaimg");
				captcha.addEventListener("click",e=>get_captcha(e.target));
				get_captcha(captcha);
			}
			</script>
		<?php }else{ 
		//$loadurl = akina_option('exlogin_url') ? akina_option('exlogin_url') : get_bloginfo('url');
		$loadurl = akina_option('new_login_url') ? akina_option('new_login_url') : get_bloginfo('url').'/wp-login.php';
		?>
			<div class="ex-register-title">
				<h3><?php _e("Success! Redirecting......","sakura")/*注册成功！正在跳转...*/?></h3>
			</div>
			<script>window.location.href='<?php echo $loadurl; ?>';</script>
		<?php } ?>
		<?php else : ?>
			<div class="register-close"><p><?php _e("Registration is not open yet.","sakura")/*暂未开放注册。*/?></p></div>
		<?php endif; ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
?>
