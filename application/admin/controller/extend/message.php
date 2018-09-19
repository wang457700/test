<?php

namespace app\admin\controller\Extend;
use think\Db;
use app\common\controller\Backend;

/**
 * banner管理
 *
 * @icon fa fa-table
 * @remark 在使用Bootstrap-table中的常用方式,更多使用方式可查看:http://bootstrap-table.wenzhixin.net.cn/zh-cn/
 */
class Message extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();

    }

    /**
     * 查看
     */
    public function index(){
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(NULL);

            $total = Db::name('message')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = Db::name('message')
                ->limit($offset, $limit)
                ->where($where)
                ->order($sort, $order)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * 删除
     */
    public function del($ids = NULL)
    {
        if ($this->request->isPost())
        {
            $info = Db::name('message')->where('id',$ids)->delete();
            if ($info!==false)
            {
                $this->success('删除成功');
            }
        }
    }





}
