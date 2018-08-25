<?php

namespace app\home\controller;

use app\common\controller\Frontend;
use think\Db;
use app\common\library\Token;


class Product extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $slide =  Db::name('slide')->where(array('slide_status'=>1,))->select();
        $this->view->assign("slide", $slide);
        $this->view->assign("title",'产品列表');
        return $this->view->fetch();
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
