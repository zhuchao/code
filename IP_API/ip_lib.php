<?php
/**
 * ip search
 * Created by PhpStorm.
 * User: saint
 * Date: 14-10-23
 * Time: 下午4:28
 */
 
class ip_lib
{
    // 淘宝ip地址库
    public function get_ip_info_taobao($ip)
    {
        $server_api = 'http://ip.taobao.com/service/getIpInfo.php?ip=';
        $uri = $server_api . $ip;
 
        $json_string = $this->get_remote_data($uri);
 
        $array = json_decode($json_string, true);
        $ret = array();
        if($array['code'])
        {
            $ret = array(
                'country' => '未知',
                'city' => '未知',
                'isp' => '未知'
            );
        }
        else
        {
            $ret['country'] = $array['data']['country'] ? $array['data']['country'] : '未知';
            $ret['city'] = $array['data']['city'] ? $array['data']['city'] : '未知';
            $ret['isp'] = $array['data']['isp'] ? $array['data']['isp'] : '未知';
        }
 
        return $ret;
    }
 
    // 新浪ip地址库
    public function get_ip_info_sina($ip)
    {
        $server_api = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=';
        $uri = $server_api . $ip;
 
        $json_string = $this->get_remote_data($uri);
 
        $array = json_decode($json_string, true);
        $ret = array();
        if($array['ret'] != 1)
        {
            $ret = array(
                'country' => '未知',
                'city' => '未知',
                'isp' => '未知'
            );
        }
        else
        {
            $ret['country'] = $array['country'] ? $array['country'] : '未知';
            $ret['city'] = $array['province'] ? $array['province'] : '未知';
            $ret['isp'] = $array['city'] ? $array['city'] : '未知';
        }
 
        return $ret;
    }
 
    private function get_remote_data($uri)
    {
        $ch = curl_init($uri) ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        return $output = curl_exec($ch) ;
    }
}
