<?php
/**
 * Created by PhpStorm.
 * User: YXL
 * Date: 2018/8/25
 * Time: 19:17
 */

namespace app\api\controller;
use app\common\controller\Frontend;
use fast\Arr;
use think\Db;
use app\common\library\Token;
use think\Session;
use app\common\library\Email;

class Eftpayment extends  Frontend
{


    protected $noNeedLogin = ['test','signature','check','transaction','transaction2','notify_url','return_url','check_query'];

    //EOPG 支付平台
    const mid='852000054990012';
    const secret='812909e980c953cf6d3b8bf6ac1760f9077324f9';
    const GET_AUTH_CODE_URL='http://eopg.eftpay.com.hk/EFTPayOnline/ForexTradeRecetion.jsp';

/**
 * 创建訂單
 * $merch_ref_no 訂單号 $trans_amount 支付金额
 * $goods_body 产品詳情 $goods_subject产品描述显示在支付页面上
 **/
    public function transaction($merch_ref_no,$trans_amount = '0.01',$goods_subject = '测试',$goods_body = '测试'){

        if(sp_ip_ischina()){
            $wallet = 'CN';
        }else{
            $wallet = 'HK';
        }

        $notify_url = url('api/eftpayment/notify_url','','',true);   // 通知地址
        $return_url = url('home/payment/alipay_return_url','','',true);// 跳转地址
        $signature = $this->signature($merch_ref_no,'ALIPAY',$trans_amount);
        $data = array(
                'merch_ref_no'=>$merch_ref_no,
                'service'=>'SALE',
                'payment_type'=>'ALIPAY',
                'mid'=>self::mid,
                'signature'=>$signature,
                'return_url'=>$return_url,
                'goods_subject'=>$goods_subject,
                'goods_body'=>$goods_body,
                'trans_amount'=>$trans_amount,
                'notify_url'=>$notify_url,
                'wallet'=>$wallet,
                'app_pay'=>'WEB',
        );

        $url = self::GET_AUTH_CODE_URL;
        $post = curl_post_https($url,http_build_query($data));
        return $post;
    }



    /**
     * 创建訂單
     * $merch_ref_no 訂單号 $trans_amount 支付金额
     * $goods_body 产品詳情 $goods_subject产品描述显示在支付页面上
     **/
    public function transaction2(){
        $a = rand(100,10000);
        $signature = $this->signature($a,'ALIPAY','0.01');

       dump($a);
       dump($signature);
    }



    public function test($merch_ref_no,$trans_amount = '0.01',$goods_subject = '测试',$goods_body = '测试'){

        transaction(round());

    }
    //接收通知
    public function notify_url(){

        $post = input('');
        $data = array('address'=>json_encode($post,true));
        $res = Db::name('order')->where(array('order_sn'=>'201809121536724002','pay_time'=>date('Y-m-d H:i:s',time())))->update($data);
//        $post = input('');
//        $payment =  Db::name('payment')->where(array('order_sn'=>$post['merch_ref_no']))->find();
//        $verify_sign = $this->signature($post['merch_ref_no'],'ALIPAY',$post['trans_amount']);
//
//        if($verify_sign == $payment['signature']){
//            $verify_result = "Verify_SUCCESS";
//            $data = array(
//                'pay_status'=>2,
//            );
//
//        }else{
//            $verify_result = "Verify_FAIL";
//        }
//        if($verify_result == "Verify_SUCCESS"){
//            echo "SUCCESS";
//        }
    }

    public function check_query(){

        $merch_ref_no = '201811021541137972';  //訂單号
        $trans_amount = '0.02'; // 金额
        $return_url = url('api/payment/return_url','','',true);// 跳转地址
        $signature = $this->signature($merch_ref_no,'ALIPAY',$trans_amount);
        $data = array(
            'service'=>'QUERY',
            'payment_type'=>'ALIPAY',
            'mid'=>self::mid,
            'return_url'=>$return_url,
            'signature'=>$signature,
            'merch_ref_no'=>$merch_ref_no,
        );

        $url = self::GET_AUTH_CODE_URL;
        $post = curl_post_https($url,http_build_query($data));
        print_r($post);die;

        dump($data);
        dump($post);
    }

    //生成签名
    public function signature($merch_ref_no,$payment_type,$trans_amount){
        $data = array(
            'merch_ref_no'=> $merch_ref_no,
            'mid'=> '852201810240001',
            'payment_type'=> $payment_type,
            'service'=> 'SALE',
            'trans_amount'=>$trans_amount,
        );
        return hash("sha256",self::secret.http_build_query($data));
    }




}