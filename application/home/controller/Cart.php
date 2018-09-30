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


    protected $noNeedLogin = ['checkin_cart','order_ok'];


    public function checkin_cart()
    {

        if(empty(is_login())){
            $tourist = create_tourist();
        }

        $data['product_id'] = input('product_id');
        $data['user_id'] = Session::get('user_id');
        $data['add_time'] = date('Y-m-d H:i:s');
        $res = Db::name('cart_order')->insertGetId($data);
        $total = Db::name('cart_order')->where('user_id',Session::get('user_id'))->count();
        if ($res) {
            $data = array(
                'code' => 1,
                'msg' => '加入購物車成功！',
                'total' => $total
            );
            $this->ajaxReturn($data);
        }else{
            $data = array(
                'code' => 0,
                'msg' => '加入購物車失敗！',

            );
            $this->ajaxReturn($data);
        }

    }



    public function order_ok(){

            $user_id = Session::get('user_id');
            if(empty($user_id)){
                $tourist = create_tourist();
            }

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
                     $goods['price']= product_price($v['goods_id']);
                    $data[$k]['product_name'] = $goods['product_name'];
                    $data[$k]['brand'] = $goods['brand'];
                    $data[$k]['freight_num'] = $goods['freight_num'];
                    $data[$k]['model'] = $goods['model'];
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

                $address_list=Db::name('user_address')->where(array('user_id'=>$user_id,'status'=>1))->select();
                $address_default=Db::name('user_address')->where(array('user_id'=>$user_id,'status'=>1,'default'=>1))->find();
                /*运费*/
                $freight = 0 ;
                $cofing_freight = config('site')['freight'];
                if(strval($total) < $cofing_freight['no_freight']){
                    $freight = $cofing_freight['freight'];
                }
                /*服务费*/
                $service_price = 0;
                $is_remote_area = 1;
                if($is_remote_area){
                    $service_price = $cofing_freight['remote_area'];
                }
                $this->assign('all_total',$total);
                $this->assign('freight',$freight);
                $this->assign('num',$num);
                $this->assign('address_list',$address_list);
                $this->assign('address_default',$address_default);
                $this->assign('order_list',$data);
            }else{
                $this->error('請選擇商品！',url('cart/shopping_cart'));

            }

        $this->assign('title','確認訂單');
        return  $this->fetch();
    }


    public function action_cart(){

        if(empty(is_login())){
            $tourist = create_tourist();
        }

        $goods_id= input('goods_id/a');
        $goods_num= input('goods_num/a');
        $address_id= input('address_id');
        $integral= input('integral');
        $coupon_sn= input('coupon');
        $user = Db::name('user')->where('id',Session::get('user_id'))->find();
        if($address_id){
            $address = Db::name('user_address')->where('id',$address_id)->find();
            $address['province_text'] = sp_region_value($address['province'],'name');
            $address['city_text'] = sp_region_value($address['city'],'name');
            $address['district_text'] = sp_region_value($address['district'],'name');
            $address['text'] = $address['province_text'].$address['city_text'].$address['district_text'].$address['address'];
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
                $price['price']= product_price($v['goods_id']);
                $fat['price'] =$price['price'];
                $fat['money_total']=$v['goods_num']*$price['price'];
                $all_total +=$v['goods_num']*$price['price'];
            }

            $res='';
            $order_sn=date('Ymd').time();

            /*积分*/
            $score_price = 0;
            if($integral){
                $cofing_integral = config('site')['integral']['use'];
                $user_integral = Db::name('user')->where('id',Session::get('user_id'))->value('score');
                $score_price = sprintf("%.2f",$user_integral/$cofing_integral);
            }

            /*优惠券*/
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
                    $data = array('code' => 0,'msg' => '優惠券被抢光了！');
                    $this->ajaxReturn($data);
                }

                $level = array('1'=>'普通會員','2'=>'白金會員','3'=>'金牌會員','4'=>'商业會員');
                if(strstr($coupon['user_level'],strval($user['level'])) == false && $coupon['user_level']!=0){
                    $data = array('code' => 0,'msg' => '優惠碼不適合'.$level[$user['level']].'使用！');
                    $this->ajaxReturn($data);
                }

                if($coupon['min_money'] > ($all_total-$score_price)){
                    $data = array('code' => 0,'msg' => '最低消费'.$coupon['min_money'].'，才能使用優惠碼！');
                    $this->ajaxReturn($data);
                }

                if($coupon['coupon_type'] == 1){ //1现金券 2折扣
                    $coupon_price = $coupon['coupon_cash'];
                }else{
                    $coupon_price = ($coupon['coupon_discount']/100)*($all_total-$score_price);
                }

                if(strval($coupon_price) >= strval($all_total-$score_price)){
                    $coupon_price = ($all_total-$score_price);
                }
                $coupon_id = $coupon['coupon_id'];
            }

            /*运费*/
            $freight = 0 ;
            $cofing_freight = config('site')['freight'];
            if($all_total < $cofing_freight['no_freight']){
                $freight = $cofing_freight['freight'];
            }

            /*服务费*/
            $service_price = 0;
            $is_remote_area = Db::name('region')->where(array('id'=>array('in',array($address['city'],$address['district']))))->column('is_remote_area');
            $is_remote_area = array_filter($is_remote_area);

            if($is_remote_area){
                $service_price = $cofing_freight['remote_area'];
            }


            foreach ($data as $k => $v) {
                    $price=Db::name('goods')->where('product_id',$v['goods_id'])->find();
                    $price['price']= product_price($v['goods_id']);
                    $fat['price'] =$price['price'];
                    $fat['money_total']=$v['goods_num']*$price['price'];
                    $fat['goods_sn'] =$price['freight_num'];
                    $fat['address_id'] =$address_id;
                    $fat['address'] =$address['text'];
                    $fat['integral_price'] =$score_price;
                    $fat['coupon_price'] =$coupon_price;
                    $fat['coupon_id'] =$coupon_id;
                    $fat['freight'] =$freight;
                    $fat['service_price'] =$service_price;
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

                /* 成功下单处理 */
                /* 优惠券减1 */
                $res= Db::name("coupon")->where('coupon_id',$coupon_id)->setDec('coupon_num',1);

                $data = array(
                    'code' => 1,
                    'msg' => '你已經生成訂單！',
                    'order_url' =>url('payment/go_pay',array('order_sn'=>base64_encode($order_sn))),
                );
                $this->ajaxReturn($data);
            }else{
                $data = array(
                    'code' => 0,
                    'msg' => '訂單生成失敗！',
                );
                $this->ajaxReturn($data);
            }

        }else{
            $data = array(
                'code' => 0,
                'msg' => '沒有選擇商品！',
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
               $list[$key]['total_price']=$total*product_price($item['product_id']);
               $list[$key]['price']= product_price($item['product_id']);
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