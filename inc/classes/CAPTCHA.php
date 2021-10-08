<?php
declare(strict_types=1);
namespace Sakura\API;
include_once('redis.php');

class CAPTCHA
{
	/**
	 * @return object|array
	 */
	private static function redis_conn(): object|array
    {
		if (class_exists('Redis')){
			try {
				$redis = _Redis::getRedis();
				return $redis;
			} catch (Exception $e) {
				return ['msg'=>$e->getMessage()];
			}
		}
		return ['msg'=>'未检测到redis类'];
    }

	/**
	 * @return array|string
	 */
    private static function redis_create_CAPTCHA(): array|string {
        $redis = self::redis_conn();
        if (is_object($redis)){
            $key = 'CAPTCHA_'.$_SERVER['REMOTE_ADDR'];
			$info = $redis->hGet($key, 'count');
			$count = $info ?? '0';
            if ($count>=3){
                return ['msg'=>"验证码请求次数过多.请 {$redis->ttl($key)}s 后重试"];
            }
			$str = self::create_CAPTCHA();
	        $redis->hSet($key, 'captcha', $str);
	        $redis->hSet($key, 'count', $count+1);
            $redis->expire($key, 20);
        return $str;
        }
        return $redis;
    }
    /**
     * create_CAPTCHA
     *
     * @param  int $time
     * @param  string $iqid 
     * @return string
     */
    private static function create_CAPTCHA(int $time=0,string $iqid=''): string {
		if (!akina_option('redis_captcha')){
			$seed = hexdec($iqid) + $time;
			mt_srand($seed);
		}
        $arr = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
        shuffle( $arr );
        $rand_keys = array_rand( $arr, 5 );
        $str = '';
        foreach ( $rand_keys as $value ) {
            $str .= $arr[$value];
        }
        return $str;
    }

	/**
	 * create_captcha_img
	 *
	 * @param string $str
	 *
	 * @return string
	 */
    private static function create_captcha_img(string $str): string {
        $font = get_stylesheet_directory() . '/inc/font26.ttf';
        //创建画布
        $img = imagecreatetruecolor(120, 40);
        //填充背景色
        $backcolor = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(0, 255));
        imagefill($img, 0, 0, $backcolor);
        //绘制文字
        for ( $i = 1; $i <= 5; $i++ ) {
			$span = $i==1 ? 5 : 18;
            $string_color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 100), mt_rand(0, 80));
            imagefttext( $img, 24, mt_rand(-15,15), $i*$span, 30, $string_color, $font, $str[$i-1] );
        }

        //添加干扰线
        for ( $i = 1; $i <= 8; $i++ ) {
            $line_color = imagecolorallocate( $img, mt_rand( 0, 150 ), mt_rand( 0, 250 ), mt_rand( 0, 255 ) );
            imageline( $img, mt_rand( 0, 179 ), mt_rand( 0, 39 ), mt_rand( 0, 179 ), mt_rand( 0, 39 ), $line_color );
        }

        //添加干扰点
        for ( $i = 1; $i <= 180*40*0.02; $i++ ) {
            $pixel_color = imagecolorallocate( $img, mt_rand( 100, 150 ), mt_rand( 0, 120 ), mt_rand( 0, 255 ) );
            imagesetpixel( $img, mt_rand( 0, 179 ), mt_rand( 0, 39 ), $pixel_color );
        }

        //打开缓存区
        ob_start ();
        imagejpeg($img);
        //输出图片
        $captcha_img =  ob_get_contents();
        //销毁缓存区
        ob_end_clean();
        //销毁图片(释放资源)
        imagedestroy($img);
        // 以json格式输出
	    return 'data:image/png;base64,' . base64_encode($captcha_img);
    }

	/**
	 * @return array
	 */
	public static function create_captcha_result():array{

		if (akina_option('redis_captcha')){
			$str = self::redis_create_CAPTCHA();
		}else{
			$timestamp = time();
			$uniq_id = uniqid();
			$str = self::create_CAPTCHA($timestamp,$uniq_id);
		}
		return [
			'code' => isset($str['msg']) ? 1 : 0,
			'data' => isset($str['msg']) ? 'Error' :self::create_captcha_img($str),
			'msg' => $str['msg'] ?? '',
			'time' => $timestamp ?? '',
			'id' => $uniq_id ?? ''
		];
	}

	/**
	 * @param string $captcha
	 * @param int $timestamp
	 * @param string $id
	 *
	 * @return array
	 */
	public static function check_CAPTCHA(string $captcha = '',int $timestamp = 0,string $id = ''): array {
        if (akina_option('redis_captcha')){
	        $result = self::check_CAPTCHA_Redis($captcha);
        }else{
	        $result = self::_check_CAPTCHA($captcha,$timestamp,$id);
        }
		return $result;
    }

	/**
	 * @param string $captcha
	 *
	 * @return array
	 */
	private static function check_CAPTCHA_Redis(string $captcha): array {
		$redis = self::redis_conn();
		$key = 'CAPTCHA_'.$_SERVER['REMOTE_ADDR'];
		$comparison = $redis->hGet($key, 'captcha');
		if (!$comparison){
			$code = 1;
			$msg = '寄,可能超时了哦!';
		}
		elseif (strtolower($captcha) === strtolower($comparison)) {
			$code = 5;
			$msg = '验证码正确!';
			$redis->hdel($key,'captcha');
		}else{
			$code = 1;
			$msg = '验证码错误!';
			$redis->hdel($key,'captcha');
		}
		return [
			'code' => $code,
			'data' => '',
			'msg' => $msg
		];
	}
    /**
     * check_CAPTCHA
     *
     * @param string $captcha
     * @param int|null $timestamp
     * @param string|null $id
     * @return array
     */
    private static function _check_CAPTCHA(string $captcha,int|null $timestamp,string|null $id): array{
        $temp = time();
        $temp1 = $temp-60;
        if (!isset($timestamp) || !isset($id) || !ctype_xdigit($id) || !ctype_digit($timestamp)){
            $code = 3;
            $msg = '非法请求';
        }
        elseif (!$captcha || isset($captcha[5]) || !isset($captcha[4])){
            $code = 3;
            $msg = '请输入正确的验证码!';
        }
        elseif($timestamp < $temp1){
            $code = 2;
            $msg = '超时!';
        }
        elseif($timestamp >= $temp1 && $timestamp <= $temp){
            $comparison = self::create_CAPTCHA($timestamp,$id);
            if (strtolower($captcha) === strtolower($comparison)){
                $code = 5;
                $msg = '验证码正确!';
            }else{
                $code = 1;
                $msg = '验证码错误!';
        }
        }else{
            $code = 1;
            $msg = '错误!';
        }
        return [
            'code' => $code,
            'data' => '',
            'msg' => $msg
        ]; 
    }
}