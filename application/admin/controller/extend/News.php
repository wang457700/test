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
class News extends Backend
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

            $where = array('post_type'=>2);
            $total = Db::name('Article')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = Db::name('Article')
            ->limit($offset, $limit)
            ->where($where)
            ->select(); 
            

             $status =array('0'=>'隐藏','1'=>'顯示');
            foreach ($list as $k => &$v)
            {
                $v['post_status'] = $status[$v['post_status']];
                $v['post_terms'] = Db::name('category')->where('id',$v['post_term_id'])->value('name');
                $v['post_date'] = date('Y/m/d',strtotime($v['post_date']));
            }
            unset($v);

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

        return $this->view->fetch();
    }


    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
         
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $params['post_type'] = 2;
           

            $info = Db::name('Article')->where('id',$ids)->update($params);
            if ($info!==false)
            {
               $this->success('修改成功');
            }
        }
        $newscategory = sp_getTreeList(1);
        $row = Db::name('Article')->where('id',$ids)->find();
        $this->view->assign("row", $row);
        $this->view->assign("newscategory", $newscategory);
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $params['post_type'] = 2;
           /* if ($params){
                $params['start_time'] = strtotime($params['start_time']);
                $params['end_time'] = strtotime($params['end_time']);
            }*/
            $params['post_date'] = date('Y-m-d H:i:s', time());
            $info = Db::name('Article')->insert($params);
            if ($info!==false)
            {
               $this->success('添加成功');
            }
        }

        $newscategory = sp_getTreeList(1);
        $this->view->assign("newscategory", $newscategory);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = NULL)
    {
        if ($this->request->isPost())
        {
            $info = Db::name('Article')->where('id',$ids)->delete();
            if ($info!==false)
            {
               $this->success('删除成功');
            }
        }
    }


    /**
     * 隐藏
     */
    public function status_hide($ids = NULL)
    {
        if ($this->request->isPost())
        {
            $info = Db::name('Article')->where('id',$ids)->update('post_status',0);
            if ($info!==false)
            {
               $this->success();
            }
        }
    }


    /**
     * 显示
     */
    public function status_display($ids = NULL)
    {
        if ($this->request->isPost())
        {
           $info = Db::name('Article')->where('id',$ids)->update('post_status',1);
            if ($info!==false)
            {
               $this->success('');
            }
        }
    }


  

}
