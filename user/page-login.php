<?php 
/**
 Template Name: Login
 */

get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if(!is_user_logged_in()){ ?>
			<div class="ex-login">
				<div class="ex-login-title">
					<p><img src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.2.9/img/Sakura/images/none.png"></p>
				</div>
				<form action="<?php echo home_url(); ?>/wp-login.php" method="post">  
					<p><input type="text" name="log" id="log" value="<?php echo $_POST['log'] ?? null; ?>" size="25" placeholder="Name" required /></p>
					<p><input type="password" name="pwd" id="pwd" value="<?php echo $_POST['pwd'] ?? null; ?>" size="25" placeholder="Password" required /></p>
    				<p><img id="captchaimg" width="120" height="40" src=""><input type="text" name="yzm" id="yzm" class="input" value="" size="20" tabindex="4" placeholder="请输入验证码" required><input type="hidden" name="timestamp" value=""><input type="hidden" name="id" value=""></p>
					<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ?? null ); ?> /> <?php esc_html_e( 'Remember Me' ); ?></label></p>
					<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
					<input class="button login-button" name="submit" type="submit" value="登 入">
				</form>
				<div class="ex-new-account" style="padding: 0;"><p>请先注册！Register first, plz!</p><p><a href="<?php echo akina_option('exregister_url') ? akina_option('exregister_url') : bloginfo('url'); ?>" target="_blank">Register</a>|<a href="<?php echo site_url(); ?>/wp-login.php?action=lostpassword" target="_blank">Lost your password?</a></p></div>
			</div>
			<script>
			{
				const get_captcha = ele=>fetch('<?php echo rest_url('sakura/v1/captcha/create')?>')
				.then(response=>response.json())
				.then(json=>{
					ele.src = json['data'];
					document.querySelector("input[name='timestamp']").value = json["time"];
                	document.querySelector("input[name='id']").value = json["id"];
				}),
					captcha = document.getElementById("captchaimg");
					captcha && captcha.addEventListener("click",e=>get_captcha(e.target));
				get_captcha(captcha);
			}
			</script>
		<?php }else{ echo Exuser_center(); } ?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
