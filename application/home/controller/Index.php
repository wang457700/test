<?php

namespace app\home\controller;

use app\common\controller\Frontend;
use think\Db;
use app\common\library\Token;
use app\common\model\Category as CategoryModel;
use fast\Tree;
use think\Session;
use think\Request;


class Index extends Frontend
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
        $slide =  Db::name('slide')->where(array('slide_status'=>1,'start_time'=>array('LT',time()),'end_time'=>array('GT',time())))->order('listorder asc')->select();
        $where['is_on_sale'] = 1;
        $top_ten= Db::name('goods')->where(array('is_best_status'=>0,'is_on_sale'=>1))->limit(10)->order('add_time desc')->select();
        $product_list= Db::name('goods')
            ->where(array('cat_id'=>array('in',$tree->getChildrenIds(input('categoryid',15))),'is_on_sale'=>1))
            ->order('add_time desc')
            ->limit(9)->select();
        $health_food= Db::name('goods')
            ->where(array('cat_id'=>array('in',$tree->getChildrenIds(input('categoryid',16))),'is_on_sale'=>1))
            ->order('add_time desc')
            ->limit(9)->select();
        $science_food= Db::name('goods')
            ->where(array('cat_id'=>array('in',$tree->getChildrenIds(input('categoryid',17))),'is_on_sale'=>1))
            ->order('add_time desc')
            ->limit(9)->select();
        $promotion= Db::name('goods')
            ->where(array('cat_id'=>array('in',$tree->getChildrenIds(input('categoryid',63))),'is_on_sale'=>1))
            ->order('add_time desc')
            ->limit(9)->select();

        $news= Db::name('article')->where('post_type',2)->order('post_date desc')->limit(3)->select();

        //浏览记录排序
        $product_history_list = Session::get('product_history');
        if($product_history_list){
            foreach ($product_history_list as $k => $v) {
                $find = Db::name('goods')->field('product_name,product_id,product_name,cover,is_on_sale')->where(array('product_id'=>$v['product_history']))->find();
                if($find['is_on_sale'] !=1){
                    unset($product_history_list[$k]);
                }

                $product_history_list[$k] = $find;
                $product_history_list[$k]['time'] = $v['time'];
                if($find['is_on_sale'] !=1){
                    unset($product_history_list[$k]);
                }else{
                    $time[] = $v['time'];
                }
            }
            array_multisort($time, SORT_DESC, $product_history_list);
        }


        $share= Db::name('user_share')->limit(4)->select();
        $this->view->assign("top_ten", $top_ten);   //Top Ten
        $this->view->assign("share", $share); //共享产品
        $this->view->assign("product_list", $product_list); //復康產品
        $this->view->assign("health_food", $health_food);   //健康及有機產品
        $this->view->assign("science_food", $science_food);   //科技產品
        $this->view->assign("promotion", $promotion);   //節日促銷
        $this->view->assign("news", $news);   //最新消息
        $this->view->assign("product_history_list",$product_history_list);   //浏览产品记录
        $this->view->assign("slide", $slide);
        $this->view->assign("title", '首頁');
        return $this->view->fetch();
    }

    public function language(){
        $language = Session::get('language');
        if($language == 's'){
            Session::set('language','t');
        }else{
            Session::set('language','s');
        }
        echo Session::get('language');
    }
    //单页面
    public function page()
    {
        $id = $this->request->param('id', 0, 'intval');
        $page =  Db::name('article')->where(array('id'=>$id,))->find();
        $this->view->assign("page", $page);
        $this->view->assign("title", $page['post_title']);
        return $this->view->fetch();
    }

    //手机端 - contact_us
    public function contact_us()
    {
        $id = $this->request->param('id', 0, 'intval');
        $page =  Db::name('article')->where(array('id'=>$id,))->find();
        $this->view->assign("page", $page);
        $this->view->assign("title", '聯繫我們');
        return $this->view->fetch();
    }

    //商务合作 - 留言板
    public function message()
    {
       $data = input('post.');

        if(empty($data['phone'])){
            $this->error('請輸入聯絡電話！');
        }

       if(empty($data['name'])){
        $this->error('請輸入聯絡人！');
       }
        if(empty($data['content'])){
            $this->error('請輸入諮詢內容！');
        }
       $data['datetime'] = date('Y-m-d H:i:s',time());
       $data['type'] = 1;
       $res = Db::name('message')->insert($data);
       if($res){
           $this->success('發送成功！');
       }else{
           $this->error('發送失敗！');
       }
    }



    //搜索 - 留言板
    public function search_message()
    {
       $data = input('post.');
        if(empty($data['content'])){
            $this->error('請輸入貨品描述！');
        }
       if(empty($data['phone'])){
           $this->error('請輸入聯絡電郵地址，微信賬戶！');
       }

       $data['datetime'] = date('Y-m-d H:i:s',time());
       $data['type'] = 2;
       $res = Db::name('message')->insert($data);
       if($res){
           $this->success('提交成功！');
       }else{
           $this->error('提交失敗！');
       }
    }



}
