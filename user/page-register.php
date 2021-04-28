<?php 
/**
 Template Name: Register
 */

get_header();
if( !empty($_POST['register_reg']) ) {
	$error = '';
	$sanitized_user_login = sanitize_user( $_POST['user_login'] );
	$user_email = apply_filters( 'user_registration_email', $_POST['user_email'] );

	// Check the username
	if ( $sanitized_user_login == '' ) {
	  $error .= '<strong>'.__("Error","sakura")./*错误*/'</strong>：'.__("Please enter username.","sakura")./*请输入用户名。*/'<br />';
	} elseif ( ! validate_username( $sanitized_user_login ) ) {
	  $error .= '<strong>'.__("Error","sakura")./*错误*/'</strong>：'.__("Invalid characters, please enter a valid username.","sakura")./*此用户名包含无效字符，请输入有效的用户名。*/'<br />';
	  $sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
	  $error .= '<strong>'.__("Error","sakura")./*错误*/'</strong>：'.__("This username has been registered.","sakura")./*该用户名已被注册。*/'<br />';
	}

	// Check the e-mail address
	if ( $user_email == '' ) {
	  $error .= '<strong>'.__("Error","sakura")./*错误*/'</strong>：'.__("Please enter email address.","sakura")./*请填写电子邮件地址。*/'<br />';
	} elseif ( ! is_email( $user_email ) ) {
	  $error .= '<strong>'.__("Error","sakura")./*错误*/'</strong>：'.__("Invalid email address.","sakura")./*电子邮件地址不正确。*/'<br />';
	  $user_email = '';
	} elseif ( email_exists( $user_email ) ) {
	  $error .= '<strong>'.__("Error","sakura")./*错误*/'</strong>：'.__("This email address has been registered.","sakura")./*该电子邮件地址已经被注册。*/'<br />';
	}

	// Check the password
	if(strlen($_POST['user_pass']) < 6){
	  $error .= '<strong>'.__("Error","sakura")./*错误*/'</strong>：'.__("Password length is at least 6 digits.","sakura")./*密码长度至少6位。*/'<br />';
	}elseif($_POST['user_pass'] != $_POST['user_pass2']){
		$error .= '<strong>'.__("Error","sakura")./*错误*/'</strong>：'.__("Inconsistent password entered twice.","sakura")./*两次输入的密码不一致。*/'<br />';
	}

	// verification
	if(akina_option('login_validate') && strlen($_POST['verification']) > 0 ){
		$error .= '<strong>'.__("Error","sakura")./*错误*/'</strong>：'.__("Please drag the slider to verify identity","sakura")./*请拖动滑块验证身份*/'<br />';
	}

	if($error == '') {
		$user_id = wp_create_user( $sanitized_user_login, $_POST['user_pass'], $user_email );
		if ( !$user_id ) {
			$error .= '<strong>'.__("Error","sakura")./*错误*/'</strong>：'.__("Unable to complete registration request...Please contact","sakura")./*无法完成注册请求... 请联系*/'<a href=\"mailto:'. get_option( 'admin_email' ) .'\">'.__("administrator","sakura")./*管理员*/'</a>！<br />';
		}else if (!is_user_logged_in()) {
			$user = get_userdatabylogin($sanitized_user_login);
			$user_id = $user->ID;
			// 自动登录
			wp_set_current_user($user_id, $user_login);
			wp_set_auth_cookie($user_id);
			do_action('wp_login', $user_login);
		}
	}
}
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if(akina_option('ex_register_open')) : ?>
		<?php if(!is_user_logged_in()){ ?>
			<div class="ex-register">
				<form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">  
					<p><input type="text" name="user_login" tabindex="1" id="user_login" class="input" value="<?php if(!empty($sanitized_user_login)) echo $sanitized_user_login; ?>" placeholder="用户名" required /></p>
					<p><input type="text" name="user_email" tabindex="2" id="user_email" class="input" value="<?php if(!empty($user_email)) echo $user_email; ?>" size="25" placeholder="电子邮箱" required /></p>
					<p><input id="user_pwd1" class="input" tabindex="3" type="password" tabindex="21" size="25" value="" name="user_pass" placeholder="密码" required /></p>
					<p><input id="user_pwd2" class="input" tabindex="4" type="password" tabindex="21" size="25" value="" name="user_pass2" placeholder="确认密码" required /></p>
					<?php if(akina_option('login_validate')) : ?>
					<div id="verification-slider">
						<div id="slider">
							<div id="slider_bg"></div>
							<span id="label">»</span><span id="labelTip"><?php _e("Drag the slider to verify","sakura")/*拖动滑块验证*/?></span>
						</div>
						<input type="hidden" name="verification" value="verification" />
					</div>
					<?php endif; ?>
					<input type="hidden" name="register_reg" value="ok" />
					<?php if(!empty($error)) { echo '<p class="user-error">'.$error.'</p>'; } ?>
					<input class="button register-button" name="submit" type="submit" value="<?php _e("Sign up","sakura")/*注 册*/?>">
				</form>
			</div>
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
