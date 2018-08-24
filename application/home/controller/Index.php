<?php

namespace app\home\controller;

use app\common\controller\Frontend;
use think\Db;
use app\common\library\Token;


class Index extends Frontend
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
        $top_ten= Db::name('goods')->where('is_best_status',0)->limit(10)->select();
        $product_list= Db::name('goods')->where('cat_id',15)->limit(9)->select();
        $health_food= Db::name('goods')->where('cat_id',16)->limit(9)->select();

        $share= Db::name('user_share')->limit(4)->select();
        $this->view->assign("share", $share); //復康產品
        $this->view->assign("product_list", $product_list); //復康產品
        $this->view->assign("top_ten", $top_ten);   //Top Ten
        $this->view->assign("health_food", $health_food);   //健康及有機產品
        $this->view->assign("slide", $slide);
        return $this->view->fetch();
    }

    public function page()
    {
        $id = $this->request->param('id', 0, 'intval');
        $page =  Db::name('article')->where(array('id'=>$id,))->find();
        $this->view->assign("page", $page);
        $this->view->assign("title", $page['post_title']);
        return $this->view->fetch();
    }


}
