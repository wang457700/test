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

    public function action_cart(){

         $goods_id= input('goods_id/a');
        $goods_num= input('goods_num/a');
        if (count($goods_id) > 1) {
            $count=count($goods_id);
            $i = 0;
            $data = array();
            for ($i = 0; $i < $count; $i++) {
                $data[$i]['goods_id'] = $goods_id[$i];
                $data[$i]['goods_num'] = $goods_num[$i];
            }
            $res='';
            foreach ($data as $k => $v) {
                     $price=Db::name('goods')->where('product_id',$v['goods_id'])->find();
                    $fat['price'] =$price['price'];
                    $fat['money_total']=$v['goods_num']*$price['price'];
                    $fat['goods_sn'] =$price['freight_num'];
                    $fat['order_sn'] =date('Y-m-d').time();
                    $fat['goods_id'] = $v['goods_id'];//这里都要加上下标
                    $fat['goods_num'] = $v['goods_num'];//这里都要加上下标
                    $fat['user_id'] = Session::get('user_id');
                    $fat['addtime'] = date('Y-m-d H:i:s',time());
                    if ($v != "") {
                        $res= db("order")->insert($fat);
                    }
            }

            if ($res) {
                $data = array(
                    'code' => 1,
                    'msg' => '你已经生成订单！',
                );
                $this->ajaxReturn($data);
            }else{
                $data = array(
                    'code' => 0,
                    'msg' => '订单生成失败！',

                );
                $this->ajaxReturn($data);
            }

        }

    }



    public function shopping_cart(){


        $user_id=  Session::get('user_id');
        $list= Db::name('cart_order')->alias('a')->field('c.*,a.product_id as goods_id,a.user_id,a.cart_id')->join('__GOODS__ c','a.product_id=c.product_id','RIGHT')->where('user_id', $user_id)->group('c.product_id')->select();
        $sum=0;
        foreach ($list as $key=>$item){

                $total=Db::name('cart_order')->where(array('product_id'=>$item['product_id']))->count();
               $list[$key]['total']=$total;
               $list[$key]['total_price']=$total*$item['price'];
               $sum+=$list[$key]['total_price'];
        }

        $this->assign('all_price',$sum);
        $this->assign('car_list',$list);
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