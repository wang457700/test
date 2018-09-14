<?php

namespace app\home\controller;

use app\common\controller\Frontend;
use think\Db;
use app\common\model\Category as CategoryModel;
use fast\Tree;


class Share extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags']);
        $this->model = model('app\common\model\Category');

        $tree = Tree::instance();
        $tree->init(collection($this->model->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->categorylist as $k => $v)
        {
            $categorydata[$v['id']] = $v;
        }

        $this->view->assign("flagList", $this->model->getFlagList());
        $this->view->assign("typeList", CategoryModel::getTypeList());
        $this->view->assign("parentList", $categorydata);

        parent::_initialize();
        $this->user_share_model = Db::name('user_share');
    }

    public function index()
    {

        $tree = Tree::instance();
        $categoryid = $tree->getChildrenIds(input('categoryid',14),true);
        $getParents = $tree->getParents(input('categoryid',14),true);

        $input['categoryid'] = input('categoryid',14);



        $where['cat_id'] = array('in',$categoryid);
        $where['status'] =1;
        $list = $this->user_share_model
            ->where($where)
            ->paginate(10);

        //手机端ajax数据
        if ($this->request->isPost() && input('search',false) ==false) {
            $this->success('发布成功',url('user/share_success'),$list);
        }

        $this->view->assign("input",$input);
        $this->view->assign("getchild",$tree->getChild(14));//mobile
        $this->view->assign("list", $list);
        $this->assign('title','共享平台');
        return $this->view->fetch();
    }

}
