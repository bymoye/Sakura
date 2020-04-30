<?php
/**
 * Classes
 */
include_once('classes/Aplayer.php');
include_once('classes/Bilibili.php');
include_once('classes/Cache.php');
include_once('classes/Images.php');

use Sakura\API\Images;
use Sakura\API\Cache;

/**
 * Router
 */
add_action('rest_api_init', function () {
    register_rest_route('sakura/v1', '/image/upload', array(
        'methods' => 'POST',
        'callback' => 'upload_image',
    ));
    register_rest_route('sakura/v1', '/cache_search/json', array(
        'methods' => 'GET',
        'callback' => 'cache_search_json',
    ));
    register_rest_route('sakura/v1', '/bangumi/bilibili', array(
        'methods' => 'POST',
        'callback' => 'bgm_bilibili',
    ));
    register_rest_route('sakura/v1', '/meting/aplayer', array(
        'methods' => 'GET',
        'callback' => 'meting_aplayer',
    ));
});

/**
 * Image uploader response
 */
function upload_image(WP_REST_Request $request) {
    // see: https://developer.wordpress.org/rest-api/requests/

    // handle file params $file === $_FILES
    /**
     * curl \
     *   -F "filecomment=This is an img file" \
     *   -F "cmt_img_file=@screenshot.jpg" \
     *   https://dev.2heng.xin/wp-json/sakura/v1/image/upload
     */
    // $file = $request->get_file_params();
    if (!check_ajax_referer('wp_rest', '_wpnonce', false)) {
        $output = array(
            'status' => 403,
            'success' => false,
            'message' => 'Unauthorized client.',
            'link' => "https://view.moezx.cc/images/2019/11/14/step04.md.png",
            'proxy' => akina_option('cmt_image_proxy') . "https://view.moezx.cc/images/2019/11/14/step04.md.png",
        );
        $result = new WP_REST_Response($output, 403);
        $result->set_headers(array('Content-Type' => 'application/json'));
        return $result;
    }

    $images = new \Sakura\API\Images();
    switch (akina_option("img_upload_api")) {
        case 'imgur':
            $image = file_get_contents($_FILES["cmt_img_file"]["tmp_name"]);
            $API_Request = $images->Imgur_API($image);
            break;
        case 'smms':
            $image = $_FILES;
            $API_Request = $images->SMMS_API($image);
            break;
        case 'chevereto':
            $image = file_get_contents($_FILES["cmt_img_file"]["tmp_name"]);
            $API_Request = $images->Chevereto_API($image);
            break;
    }

    $result = new WP_REST_Response($API_Request, $API_Request->status);
    $result->set_headers(array('Content-Type' => 'application/json'));
    return $result;
}

/*
 * 定制实时搜索 rest api
 * @rest api接口路径：https://sakura.2heng.xin/wp-json/sakura/v1/cache_search/json
 * @可在cache_search_json()函数末尾通过设置 HTTP header 控制 json 缓存时间
 */
function cache_search_json() {
    if (!check_ajax_referer('wp_rest', '_wpnonce', false)) {
        $output = array(
            'status' => 403,
            'success' => false,
            'message' => 'Unauthorized client.'
        );
        $result = new WP_REST_Response($output, 403);
    } else {
        $output = Cache::search_json();
        $result = new WP_REST_Response($output, 200);
    }
    $result->set_headers(
        array(
            'Content-Type' => 'application/json',
            'Cache-Control' => 'max-age=3600', // json 缓存控制
        )
    );
    return $result;
}

function bgm_bilibili() {
    if (!check_ajax_referer('wp_rest', '_wpnonce', false)) {
        $output = array(
            'status' => 403,
            'success' => false,
            'message' => 'Unauthorized client.'
        );
        $response = new WP_REST_Response($output, 403);
    } else {
        $page = $_GET["page"] ?: 2;
        $bgm = new \Sakura\API\Bilibili();
        $html = preg_replace("/\s+|\n+|\r/", ' ', $bgm->get_bgm_items($page));
        $response = new WP_REST_Response($html, 200);
    }
    return $response;
}

function meting_aplayer() {
    $type = $_GET['type'];
    $id = $_GET['id'];
    $wpnonce = $_GET['_wpnonce'];
    $meting_pnonce = $_GET['meting_pnonce'];
    if ((isset($wpnonce) && !check_ajax_referer('wp_rest', $wpnonce, false)) || (isset($nonce) && !wp_verify_nonce($nonce, $type . '#:' . $id))) {
        $output = array(
            'status' => 403,
            'success' => false,
            'message' => 'Unauthorized client.'
        );
        $response = new WP_REST_Response($output, 403);
    } else {
        $Meting_API = new \Sakura\API\Aplayer();
        $data = $Meting_API->get_data($type, $id);
        if ($type === 'playlist') {
            $response = new WP_REST_Response($data, 200);
            $response->set_headers(array('cache-control' => 'max-age=3600'));
        } elseif ($type === 'lyric') {
            $response = new WP_REST_Response();
            $response->set_headers(array('cache-control' => 'max-age=3600'));
            echo $data;
        } else {
            $response = new WP_REST_Response();
            $response->set_status(301);
            $response->header('Location', $data);
        }
    }
    return $response;
} 