<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
/**
 * 共享平台
 * 用户发布的共享列表
 */
class Order extends Backend
{


    /**
     * 查看
     */
    public function index()
    {




        sum_order_price('201809291538190190');


        $where   = [];
        $keywordComplex = [];
        $request = input('request.');
        $request['payment'] = input('payment','');
        $request['pay_status'] = input('pay_status','all');

        if (!empty($request['keyword'])) {
            $keyword = $request['keyword'];
            $keywordComplex['order_sn'] = ['like', "%$keyword%"];
        }
        if (!empty($request['payment'])) {
            $where['payment'] = $request['payment'];
        }
        if ($request['pay_status'] !='all') {
            $where['pay_status'] = $request['pay_status'];
        }
        $order_list = Db::name('order')->alias('a')

            //->join('__GOODS__ c', 'a.goods_id=c.product_id', 'LEFT')
            ->whereOr($keywordComplex)
            ->where($where)
            ->field('a.*,e.username,(select sum(money_total) from fa_order where order_sn=a.order_sn) as money_total')
            ->alias('a')
            ->join('__USER__ e', 'a.user_id=e.id', 'LEFT')
            ->group('a.order_sn')
            ->order('addtime desc')
            ->paginate(10,false,array('query'=>$request));

        $payment = array('0'=>'','1'=>'微信','2'=>'支付宝','3'=>'其他银行');
        $pay_status = array('0'=>'未支付','2'=>'已支付','3'=>'已发货','6'=>'已取消',);
        $page = $order_list->render();
        $this->assign('page', $page);
        $this->assign('order_list', $order_list);
        $this->assign('payment', $payment);
        $this->assign('request', $request);
        $this->assign('pay_status', $pay_status);

        return $this->view->fetch();
    }


    public function del($ids = NULL){

        $order_sn=input('order_sn');
        $res=Db::name('order')->where('order_sn',$order_sn)->delete();
        if($res){
            $this->success('删除成功');
        }
    }


    public function detail(){

        $row = array(
            'order_id'=>201807001,
            'order_time'=>'2018/06/30',
            'user_id'=>'152',
            'user_name'=>'Alan Fox',
            'service_charge'=>'$10.00',
            'total_amount'=>'$1,210.40',
            'amount_payable'=>'$1,210.40',
            'take_name'=>'Alan Fox',
            'take_phone'=>'13111111111',
            'take_address'=>'廣東省深圳市龍翔大道志聯佳大厦',
            'freight'=>'$45.00',
            'donated_amount'=>'$180.00',
            'payment_type'=>'信用卡',
            'goods_count'=>'2',
            'coupon'=>'XXXXX XXXX',
            'coupon_amount'=>'$100.00',
            'status'=>'未發貨',
        );

        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isAjax()) {
            $this->success("Ajax请求成功", null, ['id' => $ids]);
        }
        $order_sn = input('order_sn');
        $order = Db::name('order')->alias('a')
        ->field('a.*,(select sum(money_total) from fa_order where order_sn=a.order_sn) as all_total,(select sum(goods_num) from fa_order where order_sn=a.order_sn) as all_goods_num,(select username from fa_user where id=a.user_id) as username,(select coupon_name from fa_coupon where coupon_id=a.coupon_id) as coupon_name,(select name from fa_user_address where id=a.address_id) as address_username,(select phone from fa_user_address where id=a.address_id) as address_phone,(select coupon_name from fa_coupon where coupon_id=a.coupon_id) as coupon_name')
        ->where('order_sn',$order_sn)->find();

        $goods_list = Db::name('order')
            ->alias('a')
            ->field('a.*,(select place_origin from fa_goods where product_id=a.goods_id) as place_origin,(select product_name from fa_goods where product_id=a.goods_id) as product_name')

            ->where('order_sn',$order_sn)->select();
        $payment = array('0'=>'未知','1'=>'微信','2'=>'支付宝','3'=>'其他银行');
        $pay_status = array('0'=>'未支付','2'=>'已支付','3'=>'已发货','6'=>'已取消',);
        $this->view->assign("row", $row);
        $this->view->assign("order", $order);
        $this->view->assign("goods_list", $goods_list);
        $this->view->assign("payment", $payment);
        $this->view->assign("pay_status", $pay_status);
        return $this->view->fetch();

    }
}