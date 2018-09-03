<?php
/**
 * Created by PhpStorm.
 * User: YXL
 * Date: 2018/8/24
 * Time: 14:14
 */

namespace app\home\controller;
use app\common\controller\Frontend;
use think\Db;
use think\Session;
class Cart extends Frontend
{



    public function checkin_cart()
    {

        $data['product_id'] = input('product_id');
        $data['user_id'] = Session::get('user_id');
        $data['add_time'] = date('Y-m-d H:i:s');
        $res = Db::name('cart_order')->insertGetId($data);
        $total = Db::name('cart_order')->where('user_id',Session::get('user_id'))->count();
        if ($res) {
            $data = array(
                'code' => 1,
                'msg' => '加入购物车成功！',
                'total' => $total
            );
            $this->ajaxReturn($data);
        }else{
            $data = array(
                'code' => 0,
                'msg' => '加入购物车失败！',

            );
            $this->ajaxReturn($data);
        }

    }



    public function order_ok(){

        $action=input('now_pay');
        if($action=='now_pay'){
            $goods_id=input('goods_id');
            $goods_num= input('goods_num');
            $list= Db::name('goods')->where('product_id',$goods_id)->find();
            $list['money_total']=$goods_num*$list['price'];
            $address_list=Db::name('user_address')->where('user_id',Session::get('user_id'))->select();
            foreach ($address_list as $key =>$v){
                $v['province'] = Db::name('region')->where(array('id'=>$v['province']))->value('name');
                $v['city'] =Db::name('region')->where(array('id'=>$v['city']))->value('name');
                $v['district'] =Db::name('region')->where(array('id'=>$v['district']))->value('name');
                $address_list[$key] = $v;
            }
            $this->assign('order_list',$list);
        }else{
            $product_id = input('goods_id/a');
            $goods_num = input('goods_num/a');

            $count = count($product_id);
            if ($count> 0) {
                $i = 0;
                $data = array();
                for ($i = 0; $i < $count; $i++) {
                    $data[$i]['goods_id'] = $product_id[$i];
                    $data[$i]['goods_num'] = $goods_num[$i];
                }
                $total=0;
                $num=0;
                foreach ($data as $k => $v) {
                     $goods= Db::name('goods')->where('product_id',$v['goods_id'])->find();
                    $data[$k]['product_name'] = $goods['product_name'];
                    $data[$k]['brand'] = $goods['brand'];
                    $data[$k]['freight_num'] = $goods['freight_num'];
                    $data[$k]['price'] = $goods['price'];
                    $data[$k]['pricevip'] = $goods['pricevip'];
                    $data[$k]['discount_price'] = $goods['discount_price'];
                    $data[$k]['place_origin'] = $goods['place_origin']; //规格
                    $data[$k]['cover'] = $goods['cover'];
                    $data[$k]['goods_id'] = $v['goods_id'];
                    $data[$k]['money_total'] = $goods['price']*$v['goods_num'];
                    $total+=($goods['price']*$v['goods_num']);
                    $num+=$v['goods_num'];
                }
                $address_list=Db::name('user_address')->where('user_id',Session::get('user_id'))->select();

                /*运费*/
                $freight = 0 ;
                $cofing_freight = config('site')['freight'];
                if(strval($total) > $cofing_freight['no_freight']){
                    $freight = $cofing_freight['freight'];
                }
                $this->assign('all_total',$total);
                $this->assign('freight',$freight);
                $this->assign('num',$num);
                foreach ($address_list as $key =>$v){
                    $v['province'] = Db::name('region')->where(array('id'=>$v['province']))->value('name');
                    $v['city'] =Db::name('region')->where(array('id'=>$v['city']))->value('name');
                    $v['district'] =Db::name('region')->where(array('id'=>$v['district']))->value('name');
                    $address_list[$key] = $v;
                }
                $this->assign('address_list',$address_list);
                $this->assign('order_list',$data);
            }
        }

        $this->assign('title','确认订单');
        return  $this->fetch();
    }


    public function action_cart(){

        $goods_id= input('goods_id/a');
        $goods_num= input('goods_num/a');
        $address_id= input('address_id');
        $integral= input('integral');
        $coupon_sn= input('coupon');
        $user = Db::name('user')->where('id',Session::get('user_id'))->find();
        if($address_id){
            $address = Db::name('user_address')->where('id',$address_id)->find();
            $address['province'] = Db::name('region')->where(array('id'=>$address['province']))->value('name');
            $address['city'] =Db::name('region')->where(array('id'=>$address['city']))->value('name');
            $address['district'] =Db::name('region')->where(array('id'=>$address['district']))->value('name');
            $address = $address['province'].$address['city'].$address['district'].$address['address'];
        }

        if (count($goods_id) > 0) {
            $count=count($goods_id);
            $i = 0;
            $data = array();
            for ($i = 0; $i < $count; $i++) {
                $data[$i]['goods_id'] = $goods_id[$i];
                $data[$i]['goods_num'] = $goods_num[$i];
            }

            $all_total = 0;
            foreach ($data as $k => $v) {
                $price=Db::name('goods')->where('product_id',$v['goods_id'])->find();
                $fat['price'] =$price['price'];
                $fat['money_total']=$v['goods_num']*$price['price'];
                $all_total +=$v['goods_num']*$price['price'];
            }

            $res='';
            $order_sn=date('Ymd').time();
            $score_price = 0;
            if($integral){
                $cofing_integral = config('site')['integral']['use'];
                $user_integral = Db::name('user')->where('id',Session::get('user_id'))->value('score');
                $score_price = sprintf("%.2f",$user_integral/$cofing_integral);
            }

            /*优惠*/
            $coupon  = Db::name('coupon')->where(array('coupon_sn'=>$coupon_sn))->find();
            $coupon_price =  0;
            $coupon_id =  0;
            if($coupon){
                if($coupon['coupon_term']){ //1有限期
                    if(strtotime(date('Y-m-d H:i:s',strtotime('+1 day',strtotime($coupon['coupon_end_time'])))) < time()){
                        $data = array('code' => 0,'msg' => '優惠碼已失效！');
                        $this->ajaxReturn($data);
                    }
                    if(strtotime($coupon['coupon_start_time']) > time()){
                        $data = array('code' => 0,'msg' => '優惠時間還沒有開始！');
                        $this->ajaxReturn($data);
                    }
                }
                if($coupon['coupon_num'] <= 0){
                    $data = array('code' => 0,'msg' => '優惠被抢光了！');
                    $this->ajaxReturn($data);
                }
                if(strstr($coupon['user_level'],strval($user['level'])) ==false && $coupon['user_level']!=0){
                    $data = array('code' => 0,'msg' => '當前會員等級不够！');
                    $this->ajaxReturn($data);
                }

                if($coupon['min_money'] >= ($all_total-$score_price)){
                    $data = array('code' => 0,'msg' => '最低消费不够！');
                    $this->ajaxReturn($data);
                }

                if($coupon['coupon_type'] == 1){ //1现金券 2折扣
                    $coupon_price = $coupon['coupon_cash'];
                }else{
                    $coupon_price = ($coupon['coupon_discount']/100)*($all_total-$score_price);
                }
                $coupon_id = $coupon['coupon_id'];
            }

            /*运费*/
            $freight = 0 ;
            $cofing_freight = config('site')['freight'];
            if($all_total > $cofing_freight['no_freight']){
                $freight = $cofing_freight['freight'];
            }


            foreach ($data as $k => $v) {
                    $price=Db::name('goods')->where('product_id',$v['goods_id'])->find();

                    $fat['price'] =$price['price'];
                    $fat['money_total']=$v['goods_num']*$price['price'];
                    $fat['goods_sn'] =$price['freight_num'];
                    $fat['address_id'] =$address_id;
                    $fat['address'] =$address;
                    $fat['integral_price'] =$score_price;
                    $fat['coupon_price'] =$coupon_price;
                    $fat['coupon_id'] =$coupon_id;
                    $fat['freight'] =$freight;
                    $fat['order_sn'] =$order_sn;
                    $fat['goods_id'] = $v['goods_id'];//这里都要加上下标
                    $fat['goods_num'] = $v['goods_num'];//这里都要加上下标
                    $fat['user_id'] = Session::get('user_id');
                    $fat['addtime'] = date('Y-m-d H:i:s',time());
                    if ($v != "") {
                       $res= db("order")->insert($fat);
                    }
                    $goods_id[]  = $v['goods_id'];
            }

            if ($res) {
                Db::name('cart_order')->where(array('user_id'=>Session::get('user_id'),'product_id'=>array('in',implode(',',$goods_id))))->delete();
                $data = array(
                    'code' => 1,
                    'msg' => '你已经生成订单！',
                    'order_url' =>url('payment/go_pay',array('order_sn'=>base64_encode($order_sn))),
                );
                $this->ajaxReturn($data);
            }else{
                $data = array(
                    'code' => 0,
                    'msg' => '订单生成失败！',
                );
                $this->ajaxReturn($data);
            }

        }else{
            $data = array(
                'code' => 0,
                'msg' => '没有选择商品！',
            );
            $this->ajaxReturn($data);
        }

    }



    public function shopping_cart(){

        $user_id=  Session::get('user_id');
        $list= Db::name('cart_order')->alias('a')->field('c.*,a.product_id as goods_id,a.user_id,a.cart_id')->join('__GOODS__ c','a.product_id=c.product_id','RIGHT')->where('user_id', $user_id)->group('c.product_id')->select();
        $sum=0;
        $goods_sum=0;
        foreach ($list as $key=>$item){
                $total=Db::name('cart_order')->where(array('product_id'=>$item['product_id']))->count();
               $list[$key]['total']=$total;
               $list[$key]['total_price']=$total*$item['price'];
               $sum+=$list[$key]['total_price'];
               $goods_sum+=$list[$key]['total_price'];
        }

        $this->assign('all_price',$sum);
        $this->assign('car_list',$list);
        $this->assign('goods_sum',$goods_sum);
        $this->assign('title','我的购物车');
        return  $this->fetch();
    }

    public function cart_del(){
        $product_id=input('product_id');
        $res=Db::name('cart_order')->where('product_id',$product_id)->delete();
        if ($res) {
            $data = array(
                'code' => 1,
                'msg' => '删除成功',
            );
            $this->ajaxReturn($data);
        }else{
            $data = array(
                'code' => 0,
                'msg' => '删除失败！',

            );
            $this->ajaxReturn($data);
        }
    }


    public function ajaxReturn($data,$type = 'json'){
        exit(json_encode($data));
    }
}