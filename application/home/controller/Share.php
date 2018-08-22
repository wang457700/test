<?php

namespace app\home\controller;

use app\common\controller\Frontend;
use think\Db;


class Share extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->user_share_model = Db::name('user_share');
    }

    public function index()
    {

        $where = array(
            'status'=>1,
        );
        $list = $this->user_share_model
            ->where($where)
            ->select();
        dump($list);
        $this->view->assign("list", $list);
        $this->assign('title','共享平台');
        return $this->view->fetch();
    }

}
