<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 共享平台
 * 用户发布的共享列表
 */
class Myadminjson extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AdminLog');
    }

    /**
     * 查看
     */
    public function product_index()
    {
         $total = 1;
        $list =array(
            array(
                'data1'=>'1',
                'data2'=>'透气裤子宽松时尚',
                'data3'=>'裤子',
                'data4'=>'馬克華菲',
                'data5'=>'$50.00',
                'data6'=>'http://7xss96.com2.z0.glb.clouddn.com/20170112_58769745340b4.jpg',
                'data7'=>'10p片',
                'data8'=>'14',
                'data9'=>'100622',
                'data10'=>'上架',
            ), 
        );
        $result = array("total" => $total, "rows" => $list);
        return json($result);
    }

    /**
     * 分类管理
     */
    public function category_index()
    {
        $total = 1 ;
        $list =array(
            array(
                'data1'=>'粮油调味',
                'id'=>2,
            ), 
            array(
                'data1'=>'食品飲料',
                'id'=>4,
            ), 
            array(
                'data1'=>'粮油调味1',
                'id'=>6,
            ), 
            array(
                'data1'=>'粮油调味2',
                'id'=>8,
            ), 
            array(
                'data1'=>'粮油调味3',
                'id'=>10,
            ), 
        );
        $result = array("total" => $total, "rows" => $list);

            return json($result);

    }

     /**
     * 会员管理
     */
    public function user_index()
    {
        $total = 1 ;
        $list =array(
            array(
                'id'=>'6',
                'username'=>'LINJIAHONG',
                'email'=>'572580083@qq.com',
                'mobile'=>'13192963666',
                'data1'=>'商业',
                'score'=>'1000',
                'data2'=>'微信',
                'data3'=>'2018/07/01',
            ), 
        );
        $result = array("total" => $total, "rows" => $list);

        return json($result);
    }



      /**
     * 会员管理
     */
    public function coupon_index()
    {
        $total = 1 ;
        $list =array(
            array(
                'data1'=>'536435',
                'data2'=>'10%折扣',
                'data3'=>'长期',
                'data4'=>'916050070',
                'data5'=>'满100元即可获得10%折扣',
                'data6'=>'8296',
                'data7'=>'不限',
            ), 
            array(
                'data1'=>'431070',
                'data2'=>'20%折扣',
                'data3'=>'2018/12/31',
                'data4'=>'4613146641',
                'data5'=>'满100元即可获得20%折扣',
                'data6'=>'8296',
                'data7'=>'白金',
            ), 
        );
        $result = array("total" => $total, "rows" => $list);

        return json($result);
    }


      /**
     * 通用优惠券
     */
    public function coupon_detail()
    {
        $total = 1 ;
        $list =array(
            array(
                'data1'=>'20180705',
                'data2'=>'85654621',
                'data3'=>'jiahong',
                'data4'=>'2018/06/26',
                'data5'=>'$139.06',
            ), 
            array(
                'data1'=>'20180705',
                'data2'=>'85654621',
                'data3'=>'jiahong',
                'data4'=>'2018/06/26',
                'data5'=>'$139.06',
            ), 
        );
        $result = array("total" => $total, "rows" => $list);

        return json($result);
    }

         

}
