<?php
namespace app\index\controller;

use app\common\controller\Frontend;
use think\Config;
use think\Controller;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;

/**
 * 会员中心
 */
class User extends Controller
{







    //处理返回结果
    private static function doWithResult($result,$field){
        $result=json_decode($result,true);
        return isset($result[0][$field])?$result[0][$field]:'';
    }
    //获取短链接
    public static function getShort($url){
        $url='http://api.t.sina.com.cn/short_url/shorten.json?source='.self::APPKEY.'&url_long='.$url;
        $result=self::CURLQueryString($url);
        return self::doWithResult($result,'url_short');
    }
    //获取长链接
    public static function getLong($url){
        $url='http://api.t.sina.com.cn/short_url/expand.json?source='.self::APPKEY.'&url_short='.$url;
        $result=self::CURLQueryString($url);
        return self::doWithResult($result,'url_long');
    }


}
