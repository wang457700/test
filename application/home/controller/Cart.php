<?php
/**
 * Created by PhpStorm.
 * User: YXL
 * Date: 2018/8/24
 * Time: 14:14
 */

namespace app\home\controller;
use app\common\controller\Frontend;
use EasyWeChat\Staff\Session;
use think\Db;
use think\Session;
class Cart extends Frontend
{



    public function checkin_cart()
    {

        $data['product_id'] = input('product_id');
        $data['user_id'] = session('user_id');
        $data['add_time'] = date('Y-m-d H:i:s');
        $res = Db::name('cart_order')->insertGetId($data);
        $total = Db::name('cart_order')->where('user_id',session('user_id'))->count();
        if ($res) {
            $data = array(
                'code' => 1,
                'msg' => '加入购物车成功！',
                'total' => $total
            );
     $this->ajaxReturn($data);
        }

    }


    public function ajaxReturn($data,$type = 'json'){
        exit(json_encode($data));
    }
}