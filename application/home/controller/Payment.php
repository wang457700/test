<?php
/**
 * Created by PhpStorm.
 * User: YXL
 * Date: 2018/8/25
 * Time: 19:17
 */

namespace app\home\controller;
use app\common\controller\Frontend;
use think\Db;
use app\common\library\Token;
use think\Session;
class Payment extends  Frontend
{

    public function go_pay(){

        $order_sn=base64_decode(input('order_sn'));

        $order_list= Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->find();

        dump($order_list);
        $this->assign('order_list',$order_list);
        $this->assign('title','支付订单');
       return $this->fetch();
    }

    public function Payment_done(){

        $order_sn=base64_decode(input('order_sn'));
        $this->assign('order_sn',$order_sn);
        $this->assign('title','完成支付');
        return $this->fetch();
    }

}