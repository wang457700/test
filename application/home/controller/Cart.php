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

    public function shopping_cart(){


        $user_id=  Session::get('user_id');
        $list= Db::name('cart_order')->alias('a')->field('c.*,a.product_id as goods_id,a.user_id,a.cart_id')->join('__GOODS__ c','a.product_id=c.product_id','RIGHT')->where('user_id',$user_id)->group('c.product_id')->select();

        foreach ($list as $key=>$item){
            $list[$key]['total']=Db::name('cart_order')->where(array('product_id'=>$item['product_id']))->count();
        }

        $this->assign('car_list',$list);

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