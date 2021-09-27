<?php
declare(strict_types=1);
namespace Sakura\API;
use Redis;
class CAPTCHA
{
	/**
	 * @return object|array
	 */
	private static function redis_conn(): object|array
    {
		if (class_exists('Redis')){
			try {
				$redis = new Redis();
				$redis->pconnect( '127.0.0.1', 6379);
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
            $info = $redis->zRevRange($key,0,-1,true);
			$count = current($info);
            if ($count>=3){
                return ['msg'=>'请求次数过多.请一分钟后重试'];
            }
			$str = self::create_CAPTCHA();
            $redis->zRemRangeByScore($key,'0','2');
            $redis->zAdd($key, $count+1, $str);
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
     * @return array
     */
    private static function create_captcha_img(string $str): string {
        $font = get_stylesheet_directory() . '/inc/KumoFont.ttf';
        //创建画布
        $img = imagecreatetruecolor(120, 40);
        //填充背景色
        $backcolor = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(0, 255));
        imagefill($img, 0, 0, $backcolor);
        //绘制文字
        for ( $i = 1; $i <= 5; $i++ ) {
            $span = 20;
            $string_color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 100), mt_rand(0, 80));
            imagefttext( $img, 25, 2, $i*$span, 30, $string_color, $font, $str[$i-1] );
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
	    $captcha_img = 'data:image/png;base64,' . base64_encode($captcha_img);
		return $captcha_img;
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
			'code' => isset($str['msg']) ? 1 :0,
			'data' => isset($str['msg']) ? 'Error' :self::create_captcha_img($str),
			'msg' => $str['msg'] ?? '',
			'time' => $timestamp ?? '',
			'id' => $uniq_id ?? ''
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
    public static function check_CAPTCHA(string $captcha,?int $timestamp,?string $id): array{
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