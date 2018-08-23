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
