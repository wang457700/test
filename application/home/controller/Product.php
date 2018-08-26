<?php

namespace app\home\controller;

use app\common\controller\Frontend;
use think\Db;
use app\common\library\Token;
use app\common\model\Category as CategoryModel;
use fast\Tree;


class Product extends Frontend
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
    }

    public function index()
    {
        $tree = Tree::instance();
        $categoryid = $tree->getChildrenIds(input('categoryid',14));
        $getParents = $tree->getParents(input('categoryid',14));

        $where['is_on_sale'] = 1;
        $where['cat_id'] = array('in',$categoryid);
        $product_list =  Db::name('goods')->where($where)->select();
        $count =  Db::name('goods')->where($where)->count();
        $this->view->assign("product_list", $product_list);
        $this->view->assign("count", $count);
        $this->view->assign("title",'产品列表');
        $this->view->assign("getparents",$getParents);
        $input = array('categoryid'=>input('categoryid',14));
        $this->view->assign("input",$input);
        $style = input('style');
        if($style == 'grid'){
            return $this->view->fetch('index_grid');
        }else{
            return $this->view->fetch();
        }

    }


    public function index_grid()
    {
        $slide =  Db::name('slide')->where(array('slide_status'=>1,))->select();
        $this->view->assign("slide", $slide);
        $this->view->assign("title",'产品列表');
        return $this->view->fetch();
    }


    public function search()
    {
        $keyword = input('get.keyword');

        $new_list =  Db::name('article')->where(array('post_type'=>2,'post_title'=>array('like',"%$keyword%")))->select();
        dump($new_list);
        $this->view->assign("new_list",$new_list);
        $this->view->assign("title",$keyword.' - 搜索結果');
        return $this->view->fetch();
    }

    public function detail()
    {
        $goods_id = input('id');
        $goods =  Db::name('goods')->where('product_id',$goods_id)->find();
        $goods_list =  Db::name('goods')->select();

        $img_url=explode(',',$goods['img_url']);
        $this->view->assign("goods",$goods);
        $this->view->assign("goods_list",$goods_list);
        $this->view->assign("img_url",$img_url);
        $this->view->assign("title",'商品详情');
        return $this->view->fetch();
    }







}
