<?php
/**
 * @Author: bymoye
 * @Date:   2021-09-14 00:22:56
 * @Last Modified by:   bymoye
 * @Last Modified time: 2022-03-25 22:40:22
 */

declare(strict_types=1);
namespace Sakura\API;

class bgapi
{
    const Version = [
        "Firefox"=>65,
        "Chrome"=>32,
        "Edg"=>18,
        "Version"=>14,
        "Opera"=>19
    ];
	/**
	 * @param string $url
	 * @param string|null $type
	 *
	 * @return string
	 */
	private static function PrivateKeyC(string $url,string $type=null): string{
        $time = dechex(time());
        $key = akina_option('cdn_key');
		$domain = 'https://pan.nmxc.ltd/';
        if ($type === 'mobile'){
	        $filename = '/moblie/' . $url;
        }else{
	        $filename = '/' . $url;
        }
        $sstring = $key . $filename . $time;
        $md5 = md5($sstring);
        return $domain.$md5 . '/' . $time . $filename;
    }

	/**
	 * @return bool
	 */
	public static function check():bool{
        $match = preg_match_all ("/(Firefox|Chrome|Version|Opera)\/(\d+)/i", $_SERVER['HTTP_USER_AGENT'], $result);
        $flag = false;
        if ($match){
            foreach ($result[1] as $key => $value) {
                if ((int)$result[2][$key] > self::Version[$value]){
                    $flag = true;
                    break;
                }
            }
        }
        return $flag;
    }

	/**
	 * @param string|null $type
	 *
	 * @return array
	 */
	private static function privateapi(string $type=null):array{
        $randomurl_file = file_get_contents(STYLESHEETPATH . '/inc/' . ($type == 'mobile' ? 'manifest_mobile' : 'manifest') . '.json');
        $fileurl = 'https://file.nmxc.ltd/';
        $randomurl_list = json_decode($randomurl_file,true);
        $k = array_rand($randomurl_list);
        $format = self::check() ? 'webp' : 'jpeg';
        return [
            'md' => "${fileurl}${format}/${k}.md.${format}",
            'th' => "${fileurl}${format}/${k}.th.${format}",
            'large' => "${fileurl}${format}/${k}.source.${format}",
        ];
        // if (self::check()){
        //     if (isset($urllist['webp_md']) && isset($urllist['webp_th'])){
        //         $md = self::PrivateKeyC($urllist['webp_md'],$type);
        //         $th = self::PrivateKeyC($urllist['webp_th'],$type);
        //     }
        //     $webp = self::PrivateKeyC($urllist['webp'],$type);
        // }else{
        //     if (isset($urllist['jpeg_md']) && isset($urllist['jpeg_th'])){
        //     $md = self::PrivateKeyC($urllist['jpeg_md'],$type);
        //     $th = self::PrivateKeyC($urllist['jpeg_th'],$type);
        //     }
        //     $webp = self::PrivateKeyC($urllist['jpeg'],$type);
        // }
        // return [
        //     'md' => $md??null,
        //     'th' => $th??null,
        //     'large' => $webp
        // ];
    }

	/**
	 * @param string|null $type
	 *
	 * @return array
	 */
	private static function fghrshapi(string $type=null):array{
        if ($type == 'mobile'){
            $randomurl_file = get_template_directory() .'/inc/randombg_mb.txt';
        }else{
            $randomurl_file = get_template_directory() .'/inc/randombg_pc.txt';
        }
        $randomurl_list = file($randomurl_file);
        $k = array_rand($randomurl_list);
        $html = str_replace(PHP_EOL , '' , $randomurl_list[$k]);
        $gs = self::check() ? 'webp' : 'jpg';
        return [
            'md' => $html . '!q80.150p.' . $gs,
            'th' => $html . '!q80.300i.' . $gs,
            'large' => $html . '!q80.' . $gs
        ];
    }

	/**
	 * @param string|null $type
	 *
	 * @return array
	 */
	public static function getbg(?string $type=''):array{
        if (akina_option('randomimg_api') === 'cdn'){
            return self::privateapi($type);
        }else{
            return self::fghrshapi($type);
        }
    }

}