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
        $input['style'] = input('style','list');
        $input['categoryid'] = input('categoryid',14);
        $sort =  input('sort','add_time-desc');
        if($sort){
            $order=implode(" ",explode("-",$sort));
        }
        $product_list =  Db::name('goods')
            ->alias('a')
            ->field('a.product_id,a.product_name,a.cover,a.discount_type,a.price,(select count(*) from fa_goods_comment where product_id=a.product_id) as comment_count,(select count(*) from fa_order where goods_id=a.product_id and pay_status=2) as order_count')
            ->where($where)
            ->order($order)
            ->paginate(10);

        $sort_array = array('order_count'=>'銷量','price'=>'價格','comment_count'=>'評論','add_time'=>'新品');

        //手机端ajax数据
        if ($this->request->isPost() && input('search',false) ==false) {
            $this->success('发布成功',url('user/share_success'),$product_list);
        }

        $this->view->assign("product_list", $product_list);
        $this->view->assign("getparents",$getParents);
        $this->view->assign("getchild",$tree->getChild(14));//mobile
        $this->view->assign("input",$input);
        $this->view->assign("sort",$sort);
        $this->view->assign("sort_array",$sort_array);
        $this->view->assign("title",$getParents[count($getParents)-1]['name']);
        return $this->view->fetch();
        if(input('style') == 'grid'){

        }else{

        }
    }

    public function search()
    {
        $keyword = input('keyword');
        $tree = Tree::instance();
        $new_list =  Db::name('article')->where(array('post_type'=>2,'post_title'=>array('like',"%$keyword%")))->select();
        $goods_list =  Db::name('goods')->where(array('is_on_sale'=>1,'product_name'=>array('like',"%$keyword%")))->select();

        //记录历史纪录
        $this->keyword($keyword);
        $keyword_list = Session::get('keyword');

        $this->view->assign("new_list",$new_list);
        $this->view->assign("product_list",$goods_list);
        $this->view->assign("input",input('categoryid',14));
        $this->view->assign("getchild",$tree->getChild(14));//mobile
        $this->view->assign("title",$keyword.' 搜索結果');
        $this->view->assign("keyword",$keyword);
        $this->view->assign("keyword_list",$keyword_list);
        return $this->view->fetch();
    }

    public function delkeyword(){
        Session::set('keyword','');
        $this->success('清除歷史成功',url('product/search'));
    }
    public function keyword($keyword){
//判断session里有没有history
        $history=Session::get('keyword');//此处用了tp框架
        if(empty($history)){
            $history=array();
        }
//取出重复浏览的商品只保留最新的
        if( isset($history[$keyword])){
            unset($history[$keyword]);
        }
        $row=array();
        $row['keyword']=$keyword;
        $history[$keyword]=$row;
//保证只有6条历史记录
        if(count($history)>6){
            $key=key($history);
            unset($history[$key]);
        }
//储存在session里
        Session::set('keyword',$history);
    }

    public function detail()
    {
        $tree = Tree::instance();
        $china_categoryids = $tree->getChildrenIds(input('categoryid',72),true);
        $goods_id = input('id');
        $goods =  Db::name('goods')->where('product_id',$goods_id)->find();
        if(empty($goods)){
            $this->error('错误！');
        }

        //香港用户
        if(!sp_ip_ischina() && in_array($goods['cat_id'],$china_categoryids) && $goods['is_inland']){
             $this->error('內地專區只供內地用戶購買！',url('index/index'));
        }

        $this->product_history($goods_id);

        //Session::set('product_history','');
        $goods_list =  Db::name('goods')->where('is_on_sale',1)->limit(6)->order('add_time desc')->select();
        $comment_list =  Db::name('goods_comment')
            ->alias('a')->field('a.*,a.user_id as id,c.username,c.level')
            ->join('__USER__ c','a.user_id=c.id','RIGHT')
            ->where('product_id',$goods_id)
            ->order('addtime desc')
            ->paginate(5);


        //判断ip
        //香港
        $from_bow = 0;
        $tip = 0;
        if($goods['stock'] <= 0){
             $tip = '<sapn style="color: #ffa800;">库存不足</sapn>';
        }
        if($goods['pre_order'] == 1){
            $from_bow = true;
            $tip = '';
        }

        $ischina = sp_ip_ischina();
        if ($ischina && ($goods['is_inland'] == 1 || in_array($goods['cat_id'],$china_categoryids))){
            $buy = true;
        }elseif(!$ischina){
            $buy = true;
        }else{
            $buy = false;
            $tip = '内地用户可以到内地专区购买  <a href="'.url('product/index',array('categoryid'=>72)).'" style="color: #ffa800;">立即跳转</a>';
        }


        $tree = Tree::instance();
        $level = array('1'=>'普通会员','2'=>'白金会员','3'=>'金牌会员','4'=>'商业会员');
        $getparents = $tree->getParents($goods['cat_id'],true);
        $img_url=explode(',',$goods['img_url']);
        $this->view->assign("comment_list",$comment_list);
        $this->view->assign("level",$level);
        $this->view->assign("goods",$goods);
        $this->view->assign("goods_list",$goods_list);
        $this->view->assign("getparents",$getparents);
        $this->view->assign("china_categoryids",$china_categoryids);
        $this->view->assign("img_url",$img_url);

        $this->view->assign("from_bow",$from_bow);
        $this->view->assign("tip",$tip);
        $this->view->assign("buy",$buy);

        $this->view->assign("title",$goods['seo_title']);
        $this->view->assign("seo_keywords",$goods['seo_keywords']);
        $this->view->assign("seo_description",$goods['seo_desc']);
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

    public function product_history($product_array){
//判断session里有没有history
        $history=Session::get('product_history');//此处用了tp框架
        if(empty($history)){
            $history=array();
        }
//取出重复浏览的商品只保留最新的
        if( isset($history[$product_array])){
            unset($history[$product_array]);
        }
        $row=array();
        $row['product_history']=$product_array;
        $row['time']=time();
        $history[$product_array]=$row;
//保证只有10条历史记录
        if(count($history)>10){
            $key=key($history);
            unset($history[$key]);
        }
//储存在session里
        Session::set('product_history',$history);
    }




}
