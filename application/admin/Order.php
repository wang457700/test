<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;
use think\Db;
/**
 * 共享平台
 * 用户发布的共享列表
 */
class Order extends Backend
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

        $order_list = Db::name('order')->alias('a')->join('__GOODS__ c', 'a.goods_id=c.product_id', 'LEFT')
            ->join('__USER_ADDRESS__ e', 'a.address_id=e.id', 'LEFT')
            ->group('a.order_sn')
            ->order('addtime desc')->paginate(10);

        $page = $order_list->render();

        $this->assign('page', $page);


        $this->assign('order_list', $order_list);


        return $this->view->fetch();
    }
}