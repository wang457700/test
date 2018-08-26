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

        $order_info= Db::name('order')
        ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))
        ->find();

        $address_info= Db::name('user_address')
        ->where(array('user_id'=>Session::get('user_id'),'id'=>$order_info['address_id']))
        ->find();

        $all_total= Db::name('order')
            ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))
            ->sum('money_total');
        $this->assign('address_info',$address_info);
        $this->assign('all_total',$all_total);
        $this->assign('order_info',$order_info);
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