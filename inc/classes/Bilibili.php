<?php

namespace Sakura\API;

class Bilibili
{
    private int $uid;
    private string $cookies;

    public function __construct() {
        $this->uid = akina_option('bilibili_id');
        $this->cookies = akina_option('bilibili_cookie');
    }

    private function get_the_bgm_items(int $page = 1): string {
        $uid = $this->uid;
        $cookies = $this->cookies;
        $url = 'https://api.bilibili.com/x/space/bangumi/follow/list?type=1&pn=' . $page . '&ps=15&follow_status=0&vmid=' . $uid;
        $args = array(
            'headers' => array(
                'Cookie' => $cookies,
                'Host' => 'api.bilibili.com',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36'
            )
        );
        $response = wp_remote_get($url, $args);
        $bgmdata = json_decode($response["body"])->data;
        return json_encode($bgmdata);
    }

    public function get_bgm_items(int $page = 1): string
    {
        $bgm = json_decode($this->get_the_bgm_items($page), true);
        $totalpage = $bgm["total"] / 15;
        if ($totalpage - $page < 0) {
            $next = '<span>共追番' . $bgm["total"] . '部，继续加油吧！٩(ˊᗜˋ*)و</span>';
        } else {
            $next = '<a class="bangumi-next" href="' . rest_url('sakura/v1/bangumi/bilibili') . '?page=' . ++$page . '" onclick="return false"><i class="post_icon_svg" style="--svg-name: var(--svg_bolt);--size: 35px;vertical-align: -0.1em;"></i>NEXT </a>';
        }
        $lists = $bgm["list"];
        $html = "";
        foreach ((array)$lists as $list) {
            if (preg_match('/看完/m', $list["progress"], $matches_finish)) {
                $percent = 100;
            } else {
                preg_match('/第(\d+)./m', $list['progress'], $matches_progress);
                preg_match('/第(\d+)./m', $list["new_ep"]['index_show']??null, $matches_new);
                $progress = !empty($matches_progress[1]) ? (is_numeric($matches_progress[1]) ? $matches_progress[1] : 0) : 0;
                $total = !empty($matches_progress[1]) ? (is_numeric($matches_new[1]) ? $matches_new[1] : $list['total_count']) : $list['total_count'];
                $percent = $progress / $total * 100;
            }
            if (isset($list['new_ep']['index_show'])){
                $html .= '<div class="column">
                    <a class="bangumi-item" href="https://bangumi.bilibili.com/anime/' . $list['season_id'] . '/" target="_blank" rel="nofollow">
                        <img class="bangumi-image" src="' . str_replace('http://', 'https://', $list['cover']) . '"/>
                        <div class="bangumi-info">
                            <h3 class="bangumi-title" title="' . $list['title'] . '">' . $list['title'] . '</h2>
                            <div class="bangumi-summary"> ' . $list['evaluate'] . ' </div>
                            <div class="bangumi-status">
                                <div class="bangumi-status-bar" style="width: ' . $percent . '%"></div>
                                <p>' . $list['new_ep']['index_show'] . '</p>         
                            </div>
                        </div>
                    </a>
                </div>';
            }
        }
        $html .= '</div><br><div id="bangumi-pagination">' . $next . '</div>';
        return $html;
    }
} 