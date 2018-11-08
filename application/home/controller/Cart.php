<?php
/**
 * Created by PhpStorm.
 * User: YXL
 * Date: 2018/8/24
 * Time: 14:14
 */

namespace app\home\controller;
use app\common\controller\Frontend;
use fast\Arr;
use think\Db;
use fast\Tree;
use think\Session;
use app\api\controller\Hksr;

class Cart extends Frontend
{
    protected $noNeedLogin = ['checkin_cart','order_ok'];



    public function checkin_cart()
    {
        //创建游客
        if(empty(is_login())){
            $tourist = create_tourist();
        }

        $data['product_id'] = input('product_id');
        $user_id = Session::get('user_id');
        $data['number'] = input('number',1);
        $goods = Db::name('goods')->where('product_id',$data['product_id'])->find();
        $cart_order = Db::name('cart_order')->where(array('user_id'=>$user_id,'product_id'=>$data['product_id']))->find();


        if(empty($data['number'])){
            $data = array(
                'code' => 0,
                'msg' => '加入購物車失敗！',
            );
            $this->ajaxReturn($data);
        }
//        erun 接口
//        $hksr = new Hksr;
//        $sBC = $goods['freight_num'];
//        $goods['stock'] = $hksr->Product_GetFullStockListByBC($sBC);

        $tree = Tree::instance();
        $china_categoryids = $tree->getChildrenIds(input('categoryid',72),true);

        if(sp_ip_ischina()){
            //内地 is_inland=是否内地可买
            if(!$goods['is_inland'] || in_array($goods['cat_id'],$china_categoryids)){
                $data = array(
                    'code' => 0,
                    'msg' => '提示，只供內地以外購買！',
                );
                $this->ajaxReturn($data);
            }
        }else{
            //香港
            if($goods['is_inland']){
                $data = array(
                    'code' => 0,
                    'msg' => '提示，內地專區只供內地用戶購買！',
                );
                $this->ajaxReturn($data);
            }
        }


        if(($goods['stock'] <= 0 || $goods['stock'] < $cart_order['number'] + $data['number']) && $goods['pre_order'] == 0){
            $data = array(
                'code' => 0,
                'msg' => '庫存不足！',
            );
            $this->ajaxReturn($data);
        }

        $data['user_id'] = Session::get('user_id');
        $data['add_time'] = date('Y-m-d H:i:s');
        $cart_order = Db::name('cart_order')->where(array('user_id'=>$user_id,'product_id'=>$data['product_id']))->find();
        if(empty($cart_order)){
            $res = Db::name('cart_order')->insertGetId($data);
        }else{
            $res = Db::name('cart_order')->where(array('user_id'=>$user_id,'product_id'=>$data['product_id']))->setInc('number',$data['number']);
        }
        $total =count_cart_num($user_id);
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


    public function good_cart_number(){
        $data['product_id'] = input('productid');
        $user_id = Session::get('user_id');
        $data['number'] = input('number',1);
        $goods = Db::name('goods')->where('product_id',$data['product_id'])->find();
        $cart_order = Db::name('cart_order')->where(array('user_id'=>$user_id,'product_id'=>$data['product_id']))->find();

        if($data['number'] <= 0){
            $this->error('数量必须大于0','',array('number'=>1));
        }

        if($goods['pre_order'] != 1){
            if($goods['stock'] <= 0 || $goods['stock'] < $data['number']){
                $this->error('庫存不足！','',array('number'=>$cart_order['number']));
            }
        }
        $cart_order = Db::name('cart_order')->where(array('user_id'=>$user_id,'product_id'=>$data['product_id']))->update(array('number'=>$data['number']));
        if ($cart_order){
            $total =count_cart_num($user_id);
            $this->success('成功！','',array('total'=>$total));
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
                foreach ($data as $k => $v){
                        $goods= Db::name('goods')->where('product_id',$v['goods_id'])->find();
                        $goods['price']= product_price($v['goods_id']);
                        $goods_json[$v['goods_id']] = array('cat_id'=>$goods['cat_id'],'goods_num'=>$v['goods_num'],'goods_id'=>$v['goods_id'],'discount_type'=>$goods['discount_type'],'price'=>$goods['price']);

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
                $this->assign('goods_json',base64_encode(json_encode($goods_json,true))); //输出goods_ids，用于判断优惠券条件

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
            foreach ($data as $k => $v){
                $goods=Db::name('goods')->where('product_id',$v['goods_id'])->find();
                $goods['price']= product_price($v['goods_id']);
                $fat['price'] =$goods['price'];
                $fat['money_total']=$v['goods_num']*$goods['price'];
                $all_total +=$v['goods_num']*$goods['price'];

                $goods_list[$v['goods_id']] = array(
                        'cat_id'=>$goods['cat_id'],
                        'goods_num'=>$v['goods_num'],
                        'goods_id'=>$v['goods_id'],
                        'discount_type'=>$goods['discount_type'],
                        'price'=>$goods['price']
                );

                if($goods['pre_order'] != 1){
                    if($goods['stock'] <= 0 || $goods['stock'] < $v['goods_num']){
                        $this->error('提交失败，當前有商品"'.$goods['product_name'].'"庫存不足，最多可购买數量'.$goods['stock'].'！',url('cart/shopping_cart'));
                    }
                }
            }

            $res='';
            $order_sn=date('Ymd').time();

            /*积分*/
            $score_price = 0;
            if($integral){
                $cofing_integral = config('site')['integral']['use'];
                $user_integral = Db::name('user')->where('id',Session::get('user_id'))->value('score');
                $score_price = sprintf("%.2f",$user_integral/$cofing_integral);
                if($all_total <= $score_price){
                    $score_price = $all_total;
                }
            }

            /*优惠券*/
            $coupon_price =  0;
            $coupon_id =  0;
            $coupon  = Db::name('coupon')->where(array('coupon_sn'=>$coupon_sn))->find();
            if($coupon){
                $coupon_id =  $coupon['coupon_id'];
                $coupon_data = action('api/user/api_cx_coupon',['goods_list'=>$goods_list,'coupon_sn'=>$coupon_sn]);
                if($coupon_data['coupon_price']){
                    $coupon_price = $coupon_data['coupon_price'];
                }
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
                $goods=Db::name('goods')->where('product_id',$v['goods_id'])->find();
                $goods['price']= product_price($v['goods_id']);
                $fat['price'] =$goods['price'];
                $fat['money_total']=$v['goods_num']*$goods['price'];
                $fat['goods_sn'] =$goods['freight_num'];
                $fat['address_id'] =$address_id;
                $fat['address'] =$address['text'];
                $fat['integral_price'] =$score_price;
                $fat['coupon_price'] =$coupon_price;
                $fat['coupon_id'] =$coupon_id;
                $fat['freight'] =$freight;
                $fat['service_price'] =$service_price;
                $fat['order_sn'] =$order_sn;
                $fat['goods_id'] = $v['goods_id'];
                $fat['goods_is_pre_order'] = $goods['pre_order']; //是否pre_order
                $fat['goods_is_inland'] = $goods['is_inland']; //是否内地商品
                $fat['goods_num'] = $v['goods_num'];
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

                $this->success('你已經生成訂單！',url('payment/go_pay',array('order_sn'=>base64_encode($order_sn))));
            }else{
                $this->error('訂單生成失敗！',url('cart/shopping_cart'));
            }

        }else{
            $this->error('沒有選擇商品！',url('cart/shopping_cart'));
        }
    }


    public function shopping_cart(){

        $user_id=  Session::get('user_id');
        $list= Db::name('cart_order')->alias('a')
            ->field('c.*,a.product_id as goods_id,a.user_id,a.cart_id')
            ->join('__GOODS__ c','a.product_id=c.product_id','RIGHT')
            ->where('user_id', $user_id)
            ->where('is_on_sale', 1)
            ->group('c.product_id')->select();
        $sum=0;
        $goods_sum=0;
        foreach ($list as $key=>$item){
               $total=Db::name('cart_order')->where(array('user_id'=>$user_id,'product_id'=>$item['product_id']))->sum('number');
               $list[$key]['total']=$total;
               $list[$key]['total_price']=$total*product_price($item['product_id']);
               $list[$key]['price']= product_price($item['product_id']);
               $sum+=$list[$key]['total_price'];
               $goods_sum+=$total;
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