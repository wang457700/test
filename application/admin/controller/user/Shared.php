<?php

namespace app\admin\controller\User;
use think\Db;
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
            $where['status'] = array('neq',3);
            $list =  Db::name('user_share')->where($where)->select();
            $status =array('0'=>'未审核','1'=>'已审核','2'=>'下架');
            foreach ($list as $k => &$v)
            {
                $v['product_category']  = explode('-',$v['product_category']);
                foreach ($v['product_category'] as $kk => &$vv){
                    $category_name[$k][] = Db::name('category')->where('id',$vv)->value('name');
                }

                $v['status_text'] = $status[$v['status']];
                $v['product_pic'] = $v['product_pic'];
                $v['user_name'] = Db::name('user')->where('id',$v['user_id'])->value('username');
                $v['category_name'] = implode(' ',$category_name[$k]);
            }
            unset($v);

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 详情
     */
    public function detail($ids=null)
    {

        $row = Db::name('user_share')->where('id',$ids)->find();
        $row['product_category']  = explode('-',$row['product_category']);
        foreach ($row['product_category'] as $kk => &$vv){
            $category_name[] = Db::name('category')->where('id',$vv)->value('name');
        }
        $row['category_name'] = implode(' ',$category_name);
        $row['user_name'] = Db::name('user')->where('id',$row['user_id'])->value('username');
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isAjax()) {
            $this->success("Ajax请求成功", null, ['id' => $ids]);
        }


        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 详情
     */
    public function status($ids=null)
    {
        $row = Db::name('user_share')->where('id',$ids)->update(array('status'=>1));
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isAjax()) {
            $this->success("审核成功！", null, ['id' => $ids]);
        }
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
