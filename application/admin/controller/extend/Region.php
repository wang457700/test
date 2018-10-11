<?php

namespace app\admin\controller\Extend;
use think\Db;
use app\common\controller\Backend;
use fast\Tree;

/**
 * banner管理
 *
 * @icon fa fa-table
 * @remark 在使用Bootstrap-table中的常用方式,更多使用方式可查看:http://bootstrap-table.wenzhixin.net.cn/zh-cn/
 */
class Region extends Backend
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
        if ($this->request->isAjax()){
            $pid = $this->request->get('type',47494);
            //数据输出ajax
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(NULL);
            $info = Db::name('region')
                //->where()
                ->select();
            $tree = Tree::instance();
            $tree->init(collection(Db::name('region')->where(array('level'=>array('in','1,2,3')))->order('id desc')->select())->toArray(), 'parent_id');
            $list = $this->categorylist = $tree->getTreeList($tree->getTreeArray($pid), 'name');
            $result = array("total" => 0, "rows" => $list, "extend" => ['money' => mt_rand(100000,999999), 'price' => 200]);
            return json($result);
        }

        $level1_list = Db::name('region')->where('level',1)->order('id desc')->select();
        $this->assign('level1_list',$level1_list);
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
            $parent = Db::name('region')->where('id',$params['parent_id'])->find();
            $params['level'] = $parent['level'] + 1;
            $info = Db::name('region')->where('id',$ids)->update($params);
            if ($info!==false)
            {
               $this->success('修改成功');
            }
        }

        $newscategory = sp_getTreeList(1);

        $row = Db::name('region')->where('id',$ids)->find();
        $this->view->assign("row", $row);
        $this->view->assign("newscategory", $newscategory);

        $tree = Tree::instance();
        $tree->init(collection(Db::name('region')->where(array('level'=>array('in','1,2')))->order('id desc')->select())->toArray(), 'parent_id');
        $level1_list = $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $this->assign('level1_list',$level1_list);

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
            $parent = Db::name('region')->where('id',$params['parent_id'])->find();
            $params['level'] = $parent['level'] + 1;
            $info = Db::name('region')->insert($params);
            if ($info!==false)
            {
               $this->success('添加成功');
            }
        }

        $tree = Tree::instance();
        $tree->init(collection(Db::name('region')->where(array('level'=>array('in','1,2')))->order('id desc')->select())->toArray(), 'parent_id');
        $level1_list = $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $this->assign('level1_list',$level1_list);
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
