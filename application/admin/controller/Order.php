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
       // sum_order_price('201809291538190190');
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
            ->field('a.*,e.nickname,(select sum(money_total) from fa_order where order_sn=a.order_sn) as money_total')
            ->alias('a')
            ->join('__USER__ e', 'a.user_id=e.id', 'LEFT')
            ->group('a.order_sn')
            ->order('addtime desc')
            ->paginate(10,false,array('query'=>$request));
        $pay_status = array('0'=>'未支付','2'=>'已支付','3'=>'已发货','6'=>'已取消','7'=>'到付');
        $page = $order_list->render();
        $this->assign('page', $page);
        $this->assign('order_list', $order_list);
        $this->assign('payment', sp_payment_array());

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

    //发货
    public function deliver_order($ids = NULL){

        $order_sn=input('order_sn');
        $res=Db::name('order')->where('order_sn',$order_sn)->update(array('pay_status'=>'3'));
        if($res){
            $this->success('發貨成功');
        }
    }

    //取消訂單
    public function cancel_order($ids = NULL){
        $order_sn=input('order_sn');
        $res=Db::name('order')->where('order_sn',$order_sn)->update(array('pay_status'=>'6'));
        if($res){
            $this->success('取消訂單成功');
        }
    }


    //pre_order 商品列表
    public function pre_order_list(){

        $request = input('request.');
        $where = [];
        $keywordComplex = [];

        if (!empty($request['keyword'])) {
            $keyword = $request['keyword'];
            $keywordComplex['order_sn|product_name|goods_sn'] = ['like', "%$keyword%"];
        }

        $where['goods_is_pre_order'] = 1;
        $where['pay_status'] = array('in','1,2,3');
        $goods_list = Db::name('order')->alias('a')
            ->field('a.*,c.product_name,c.cover')
            ->join('__GOODS__ c', 'a.goods_id=c.product_id', 'LEFT')
            ->whereOr($keywordComplex)
            ->where($where)
           // ->field('a.*,e.nickname,(select sum(money_total) from fa_order where order_sn=a.order_sn) as money_total')
            ->alias('a')
            //->join('__USER__ e', 'a.user_id=e.id', 'LEFT')
            ->order('addtime desc')
            ->paginate(10,false,array('query'=>$request));
        $page = $goods_list->render();
        $this->assign('page', $page);
        $this->assign('goods_list', $goods_list);
        $this->assign('request', $request);
        $this->assign('paystatus', sp_paystatus_array());
        return $this->view->fetch();



    }


    public function detail(){

        $order_sn = input('order_sn');
        $order = Db::name('order')->alias('a')
        ->field('a.*,(select sum(money_total) from fa_order where order_sn=a.order_sn) as all_total,(select sum(goods_num) from fa_order where order_sn=a.order_sn) as all_goods_num,(select username from fa_user where id=a.user_id) as username,(select coupon_name from fa_coupon where coupon_id=a.coupon_id) as coupon_name,(select name from fa_user_address where id=a.address_id) as address_username,(select phone from fa_user_address where id=a.address_id) as address_phone,(select coupon_name from fa_coupon where coupon_id=a.coupon_id) as coupon_name')
        ->where('order_sn',$order_sn)->find();

        $goods_list = Db::name('order')
            ->alias('a')
            ->field('a.*,(select place_origin from fa_goods where product_id=a.goods_id) as place_origin,(select product_name from fa_goods where product_id=a.goods_id) as product_name')

            ->where('order_sn',$order_sn)->select();
        $this->view->assign("order", $order);
        $this->view->assign("goods_list", $goods_list);
        $this->view->assign("payment", sp_payment_array());
        $this->view->assign("pay_status", sp_paystatus_array());
        return $this->view->fetch();

    }
}