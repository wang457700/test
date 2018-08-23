<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Db;

/**
 * 首页接口
 */
class Index extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     *
     */
    public function index()
    {
        $this->success('请求成功');
    }

    public function get_category(){
        $parent_id = input('get.parent_id/d'); // 商品分类 父id
        $list = Db::name('category')->where("pid", $parent_id)->select();
        if($list){
            $this->ajaxReturn(['status'=>1 ,'msg'=>'获取成功！','result'=>$list]);
        }
        $this->ajaxReturn(['status'=>-1 ,'msg'=>'获取失败！']);
    }

    public function ajaxReturn($data)
    {
        exit(json_encode($data));
    }

}
