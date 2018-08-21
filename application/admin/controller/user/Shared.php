<?php

namespace app\admin\controller\User;

use app\common\controller\Backend;

/**
 * 共享平台
 * 用户发布的共享列表
 */
class Shared extends Backend
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
        if ($this->request->isAjax()) {

            $total = 1;
            $list =array(
                array(
                    'id'=>1,'title'=>'Bremed Tens電療機2Channels',
                    'category_name'=>'電療機',
                    'img'=>'/uploads/20180807/1f80094b66870d833a6073d2fbee9116.jpg',
                    'user_name'=>'NAME',
                    'user_id'=>'861675',
                    'status'=>'稽核中',
                ),
                array(
                    'id'=>1,'title'=>'Bremed Tens電療機2Channels',
                    'category_name'=>'電療機',
                    'img'=>'/uploads/20180807/1f80094b66870d833a6073d2fbee9116.jpg',
                    'user_name'=>'NAME',
                    'user_id'=>'861675',
                    'status'=>'顯示',
                ),
            );
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 详情
     */
    public function detail($ids)
    {
       
       $row = array(
            'id'=>1,
            'title'=>'Bremed Tens電療機2Channels',
            'category_name'=>'電療機',
            'img'=>'/uploads/20180807/1f80094b66870d833a6073d2fbee9116.jpg',
            'user_name'=>'NAME',
            'user_id'=>'861675',
            'add_time'=>'2018-8-7 17:22:08',
            'status'=>'稽核中',
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
