<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;
use think\Db;
/**
 * 共享平台
 * 用户发布的共享列表
 */
class Index extends Backend
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
    public function index()
    {

        $order_list= Db::name('order')->alias('a')->join('__GOODS__ c','a.goods_id=c.product_id','LEFT')
            ->join('__USER_ADDRESS__ e','a.address_id=e.id','LEFT')
            ->group('a.order_sn')
            ->order('addtime desc')->paginate(10);

           $page=$order_list->render();

        $this->assign('page',$page);


        $this->assign('order_list',$order_list);



        return $this->view->fetch();
    }

    /**
     * 詳情
     */
    public function detail($ids)
    {

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

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
     /**
     * 编辑
     */
    public function edit($ids = NULL)
    {

        if ($this->request->isPost())
        {
            $params = 1;
            if ($params)
            {
               $this->success();
            }
            $this->error();
        }
        $this->view->assign("row", 1);
        return $this->view->fetch();
    }




}
