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

        if ($this->request->isPost()){
            $post= input('post.');
            $order_sn = base64_decode(base64_decode($post['order_sn']));

            $data = array(
                'payment'=>$post['payment'],
                'contribution_price'=>$post['contribution_price'],
                'pay_time'=>date('Y-m-d H:i:s',time()),
                'pay_status'=>2,
            );
            $res = Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->update($data);
            if($res){

                $all_price = Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->sum('price');
                $cofing_integral = config('site')['integral']['obtain'];
                //支付成功处理
                $int = array(
                    'order_sn'=>$order_sn,
                    'user_id'=>Session::get('user_id'),
                    'integral'=>$all_price/$cofing_integral,
                    'type'=>'add',
                    'createtime'=>date('Y-m-d H:i:s',time()),
                );
                $res= Db::name("integral_log")->insertGetId($int);
                if($res!==false){
                    $res= Db::name("user")->where(array('id'=>Session::get('user_id')))->setInc('score',$int['integral']);
                }
                $this->success('支付成功！',url('payment/payment_done',array('order_sn'=>base64_encode($order_sn))));
            }else{
                $this->error('支付失败！');
            }
        }


        $order_sn=base64_decode(input('order_sn'));
        $order_info= Db::name('order')
        ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))
        ->find();

        if($order_info['pay_status']!=0 && $order_info['pay_status']!=1){
            $this->error('订单已成功支付！',url('user/center'));
        }
        $address_info= Db::name('user_address')
        ->where(array('user_id'=>Session::get('user_id'),'id'=>$order_info['address_id']))
        ->find();

        $all_total= Db::name('order')
            ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))
            ->sum('money_total');
        $integral = Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->value('integral');

        $this->assign('integral',$integral);
        $this->assign('address_info',$address_info);
        $this->assign('all_total',$all_total);
        $this->assign('order_info',$order_info);
        $this->assign('order_sn',base64_encode(input('order_sn')));
        $this->assign('title','支付订单');
       return $this->fetch();
    }

    public function payment_done(){
        $order_sn=base64_decode(input('order_sn'));


        $integral= Db::name('integral_log')
            ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))
            ->value('integral');
        dump($integral);
        $this->assign('order_sn',$order_sn);
        $this->assign('integral',$integral);
        $this->assign('title','完成支付');
        return $this->fetch();
    }

}