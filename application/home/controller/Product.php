<?php

namespace app\home\controller;

use app\common\controller\Frontend;
use think\Db;
use app\common\library\Token;
use app\common\model\Category as CategoryModel;
use fast\Tree;
use think\Session;


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
        $order = [];
        $input['categoryid'] = input('categoryid',14);
        $sort =  input('sort','add_time-desc');
        if($sort){
            $order=implode(" ",explode("-",$sort));
        }

        $product_list =  Db::name('goods')
            ->alias('a')
            ->field('a.*,(select count(*) from fa_goods_comment where product_id=a.product_id) as comment_count,(select count(*) from fa_order where goods_id=a.product_id and pay_status=2) as order_count')
            ->where($where)
            ->order($order)
            ->paginate(10);

        $sort_array = array('order_count'=>'銷量','price'=>'價格','comment_count'=>'評論','add_time'=>'新品');


        $this->view->assign("product_list", $product_list);
        $this->view->assign("getparents",$getParents);
        $this->view->assign("input",$input);
        $this->view->assign("sort",$sort);
        $this->view->assign("sort_array",$sort_array);
        $this->view->assign("title",'产品列表');
        if(input('style') == 'grid'){
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
        $comment_list =  Db::name('goods_comment')
            ->alias('a')->field('a.*,a.user_id as id,c.username,c.level')
            ->join('__USER__ c','a.user_id=c.id','RIGHT')
            ->where('product_id',$goods_id)
            ->order('addtime desc')
            ->paginate(5);

        $tree = Tree::instance();
        $level = array('1'=>'普通会员','2'=>'白金会员','3'=>'金牌会员','4'=>'商业会员');
        $getparents = $tree->getParents($goods['cat_id'],true);
        $img_url=explode(',',$goods['img_url']);
        $this->view->assign("comment_list",$comment_list);
        $this->view->assign("level",$level);
        $this->view->assign("goods",$goods);
        $this->view->assign("goods_list",$goods_list);
        $this->view->assign("getparents",$getparents);
        $this->view->assign("img_url",$img_url);
        $this->view->assign("title",'商品详情');
        return $this->view->fetch();
    }

    public  function  comment(){
        $data =  input();
        $user_id = Session::get('user_id');
        $data['addtime'] = date('Y-m-d H:i:s',time());
        $data['user_id'] = $user_id;
        $data['product_id'] =  $data['goods_id'];

        if(empty($user_id)){
            $this->error('请登录后再操作！');
        }
        if(empty($data['content'])){
            $this->error('请填写您想发表的评论！');
        }
        if(empty($data['score'])){
            $this->error('请选择星级评分！');
        }
        unset($data['goods_id']);
        $res = Db::name('goods_comment')->insert($data);
        if($res){
            $this->success('评论成功',url('user/share_success'));
        }else{
            $this->error('请输入简介！');
        }
    }






}
