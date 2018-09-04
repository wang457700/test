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
        $categoryid = $tree->getChildrenIds(input('categoryid',14),true);
        $getParents = $tree->getParents(input('categoryid',14),true);
        $where['is_on_sale'] = 1;
        $where['cat_id'] = array('in',$categoryid);

        $sort =  input('sort');
        if($sort){
            if($sort == 'new'){
                $order  = 'add_time desc';
            }else{
                $sort =  explode('_',input('sort'));
                $order  = array($sort[1]=>$sort[0]);
            }
        }else{
            $order='price desc';
        }

        $product_list =  Db::name('goods')
            ->where($where)
            ->order($order)
            ->paginate(2);
        $count =  Db::name('goods')->where($where)->count();
        $this->view->assign("product_list", $product_list);
        $page = $product_list->render();

        dump($page);
        $this->assign('page',$page);
        $this->view->assign("count", $count);
        $this->view->assign("title",'产品列表');
        $this->view->assign("getparents",$getParents);
        $this->view->assign("input",input());
       // $input = array('categoryid'=>input('categoryid',14));
       // $this->view->assign("input",$input);
        $style = input('style');
        if($style == 'grid'){
            return $this->view->fetch('index_grid');
        }else{
            return $this->view->fetch();
        }

    }

    public function search()
    {
        $keyword = input('keyword');
        $new_list =  Db::name('article')->where(array('post_type'=>2,'post_title'=>array('like',"%$keyword%")))->select();
        $goods_list =  Db::name('goods')->where(array('is_on_sale'=>1,'product_name'=>array('like',"%$keyword%")))->select();
        $this->view->assign("new_list",$new_list);
        $this->view->assign("product_list",$goods_list);
        $this->view->assign("title",$keyword.' - 搜索結果');
        return $this->view->fetch();
    }

    public function detail()
    {
        $goods_id = input('id');
        $goods =  Db::name('goods')->where('product_id',$goods_id)->find();
        $goods_list =  Db::name('goods')->select();
        $tree = Tree::instance();
        $getparents = $tree->getParents($goods['cat_id'],true);
        $img_url=explode(',',$goods['img_url']);
        $this->view->assign("goods",$goods);
        $this->view->assign("goods_list",$goods_list);
        $this->view->assign("getparents",$getparents);
        $this->view->assign("img_url",$img_url);
        $this->view->assign("title",'商品详情');
        return $this->view->fetch();
    }







}
