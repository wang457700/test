<?php

namespace app\home\controller;

use app\common\controller\Frontend;
use think\Config;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Hook;
use think\Session;
use think\Validate;
use app\common\library\Email;

/**
 * 会员中心
 */
class User extends Controller
{

    const APPKEY='4119214287';
    const URL ="www.baidu.com";



    public function Login(){




        $this->assign('title','会员登录');
        return $this->view->fetch();
    }


    public function register(){

      if($_POST){
         $data= input('post.');
          if(empty($data['username'])){
              $this->error('请填写用户名',url('user/register'));
          };
          if($data['password2'] != $data['password']){
              $this->error('二次输入的密码不一致',url('user/register'),2);
          };
          unset($data['password2']);
          $data['password']=md5(input('password'));
          $res=Db::name('user')->insertGetId($data);
          if($res){
              Session::set('user_id',$res);
              $this->redirect(url('user/user_email_activation'));
          }else{
              $this->error('注册失败！',url('user/register'));
          }

      }

        $this->assign('title','会员注册');
        return $this->view->fetch();
    }

    public function user_email_activation(){


        $user_id= Session::get('user_id');
        $list=Db::name('user')->where(array('id'=>$user_id))->find();
        if(input('email')==1){
            $email = new Email;
            $encodeurl=url('User/is_email',array('token'=>base64_encode($user_id)));
            $message= 'https://wsstest.teamotto.me/'.urlencode($encodeurl);
            $url=self::getShort($message);

            $result = $email
                ->to($list['email'])
                ->subject(__("请激活您的邮箱"))
                ->message($url)
                ->send();
            if($result){
                $this->success('发送成功！');
            }else{
                $this->error('发送失败！');
            }
        }


       $this->assign('list',$list);
        return $this->view->fetch();
    }

    /***
     * 邮箱激活
     */
    public function is_email(){

          $user_id=base64_decode(input('token'));

          $res= Db::name('user')->where(array('id'=>$user_id))->update(array('is_email_status'=>1));
          if($res){
              $this->success('激活成功！');
          }else{
              $this->error('激活失败！');
          }

    }

    /***
     * 用户编辑页面
     * author：MR:daly
     * addting:2018/8/13
     */

    public function user_edit(){


        return $this->view->fetch();

    }

    /***
     * @return string
     * 用户会员中心
     */
    public function user_center(){




        return $this->view->fetch();
    }


    /***
     * @return string
     * 用户中心我的订单！
     */

    public function user_my_order(){




        return $this->view->fetch();
    }

    /**
     * @return string
     * 订单详情
     */
    public function user_order_detail(){


        return $this->view->fetch();
    }

    /**
     * @return string
     * 订单共享
     */
    public function user_publish_share(){



        return $this->view->fetch();
    }


    public function user_share_product_add(){


        return $this->view->fetch();
    }

    public function user_share_product_ok(){




        return $this->view->fetch();
    }


    //CURL
    private static function CURLQueryString($url){
        //设置附加HTTP头
        $addHead=array("Content-type: application/json");
        //初始化curl
        $curl_obj=curl_init();
        //设置网址
        curl_setopt($curl_obj,CURLOPT_URL,$url);
        //附加Head内容
        curl_setopt($curl_obj,CURLOPT_HTTPHEADER,$addHead);
        //是否输出返回头信息
        curl_setopt($curl_obj,CURLOPT_HEADER,0);
        //将curl_exec的结果返回
        curl_setopt($curl_obj,CURLOPT_RETURNTRANSFER,1);
        //设置超时时间
        curl_setopt($curl_obj,CURLOPT_TIMEOUT,8);
        //执行
        $result=curl_exec($curl_obj);
        //关闭curl回话
        curl_close($curl_obj);
        return $result;
    }
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
