<?php

namespace app\admin\controller;
use think\config\driver\Json;
use think\Db;
use app\common\controller\Backend;
use app\common\model\Category as CategoryModel;
use fast\Tree;

/**
 * 共享平台
 * 用户发布的共享列表
 */
class Product extends Backend
{
    protected $model = null;
    protected $categorylist = [];

    public function _initialize()
    {

        parent::_initialize();
        $this->model = model('AdminLog');

        $this->model = model('app\common\model\Category');

        $tree = Tree::instance();
        $tree->init(collection($this->model->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(14), 'name');
        $categorydata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->categorylist as $k => $v)
        {
            $categorydata[$v['id']] = $v;
        }

        $this->view->assign("flagList", $this->model->getFlagList());
        $this->view->assign("typeList", CategoryModel::getTypeList());
        $this->view->assign("parentList", $categorydata);

    }


    public function add(){

        $cat_list = Db::name('category')->where("pid = 14")->select();
        if($this->request->isAjax()){
            $data=input('post.');
            if($data['discount_type'] == 2 && $data['pricevip'] =='0'){
                $this->error('特價不能為零！');
            }

            $product_name=Db::name('goods')->where('product_name',$data['product_name'])->find();
            if($product_name){
                $this->error('该商品名称已经添加');
            }
            if(empty($data['cat_id'])){
                $this->error('请选择分类');
            }
            $data['add_time']=date('Y-m-d H:i:s',time());
            //$img_url=array_filter(input('img_url/a'));
            //$data['img_url']=implode(',',$img_url);
            $res=Db::name('goods')->insertGetId($data);
            if($res){
                $this->success('添加成功！',url('product/index'));
            }else{
                $this->error('添加失败');
            }


        }

        $this->assign('cat_list',$cat_list);
        return $this->view->fetch();
    }


    public function edit($ids = NULL){

        $product_id=input('product_id');
        $product_list=Db::name('goods')->where('product_id',$product_id)->find();

        //$img_url=explode(',',$product_list['img_url']);

        //cai_id 为 三级
        $parent_two_id = Db::name('category')->where('id',$product_list['cat_id'])->value('pid');
        if($parent_two_id){
            $parent_thee_id = Db::name('category')->where("id", $parent_two_id)->value('pid');
        }
        $product_list['cat_id'] = array_values(array_filter(array($parent_thee_id==14?'':$parent_thee_id,$parent_two_id==14?'':$parent_two_id,$product_list['cat_id'])));

        $cat_list = Db::name('category')->where("pid = 14")->select();
        if($this->request->isAjax()){
            $data=input('post.');
            if($data['discount_type'] == 2 && $data['pricevip'] =='0'){
                $this->error('特價不能為零！');
            }

            if(empty($data['cat_id'])){
                $this->error('请选择分类');
            }
            $img_url=array_filter(input('img_url/a'));
            $data['img_url']=implode(',',$img_url);
            $res=Db::name('goods')->where('product_id',$data['product_id'])->update($data);
            if($res){
                $this->success('修改成功！');
            }else{
                $this->error('修改失败');
            }
        }

        $category_list = array(
            $this->get_category($parent_thee_id),
            $this->get_category($parent_two_id),
        );

        $this->assign('product_id',$product_id);

        $this->assign('category_list',$category_list);
        $this->assign('product_list',$product_list);
        $this->assign('cat_list',$cat_list);

        return $this->view->fetch();
    }




    /**
     * 查看
     */
    public function index()
    {
        $tree = Tree::instance();
        $where   = [];
        $request = input('request.');
        $request['is_on_sale'] = input('is_on_sale','all');
        $request['cat_id'] = input('cat_id',14);
        if (!empty($request['cat_id'])) {
            $where['cat_id'] = array('in',$tree->getChildrenIds(input('categoryid',$request['cat_id']),true));
        }
        if ($request['is_on_sale'] !='all') {
            $where['is_on_sale'] = $request['is_on_sale'];
        }
        $keywordComplex = [];
        if (!empty($request['keyword'])) {
            $keyword = $request['keyword'];
            $keywordComplex['product_name|freight_num'] = ['like', "%$keyword%"];
        }
        $list=Db::name('goods')
        ->whereOr($keywordComplex)
        ->where($where)

        ->paginate(10,false,array('query'=>$request));
        $is_on_sale=array(
            '0'=>'下架',
            '1'=>'上架'
        );

        $is_on=array(
            '0'=>'上架',
            '1'=>'下架'
        );

        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('is_on',$is_on);
        $this->assign('page', $page);
        $this->assign('request', $request);
        $this->assign('categorylist', $this->categorylist);
        $this->assign('is_on_sale',$is_on_sale);
        return    $this->view->fetch();
    }

    public function is_on_sale(){
        $product_id = input('product_id');
        $is_on_sale = input('is_on_sale');
        $on_sale="";
        switch ($is_on_sale){
            case 0:
                $on_sale=1;
                break;
             case 1;
             $on_sale=0;
             break;
        }
       $res=Db::name('goods')->where('product_id',$product_id)->update(array('is_on_sale'=>$on_sale));
        if ($res) {
            $this->success();
        } else {
            $this->error();
        }

    }

    public function del($ids =null){
        if ($this->request->isPost()) {
            $product_id = input('product_id');
            $res = Db::name('goods')->where('product_id', $product_id)->delete();
            if ($res) {
                $this->success();
            } else {
                $this->error();
            }
        }
    }
    /**
     * 分类
     */
    public function category()
    {
        if ($this->request->isAjax()) {
            //数据输出ajax
            $total = 1;
            $where = array();
            $cid = $this->request->get('type');
            if($cid == 'all' || empty($cid)){
                 foreach ($this->categorylist as $k => $v){
                       if($v['type'] == 'product') {
                               $list[] = $v;
                       }
                }
            }else{
                $where['pid'] = $cid;
                $list = Db::name('Category')->where($where)->select();
            }

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

        $category_list = Db::name('Category')->where('pid',14)->select();
        $this->view->assign("category_list", $category_list);
        return $this->view->fetch();
    }

    /**
     * 查询三级分类
     */
    public function category_sub()
    {
        if ($this->request->isAjax()) {
            //数据输出ajax
            //
            $total = 1;
            $cid = $this->request->get('cid');

            $list = Db::name('Category')->where('pid',$cid)->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
    }

    /**
     * 修改三级分类
     */
    public function category_sub_edit()
    {
        if ($this->request->isAjax()) {
            //数据输出ajax


            $params = $this->request->post("row/a");
            $pid = $params['pid'];

            if($pid == ''){
                $this->error('请选择分类！');
            }

            if($params['data']){
                foreach ($params['data'] as $k => $v) {
                    $edit = array(
                        'name'=>$v,
                        'nickname'=>$v,
                    );
                    Db::name('Category')->where('id',$k)->update($edit);
                }
            }

            if($params['new']){
                foreach ($params['new'] as $kk => $vv){
                    $add = array(
                        'name'=>$vv,
                        'nickname'=>$vv,
                        'pid'=>$pid,
                        'type'=>'product',
                        'createtime'=>time(),
                        'updatetime'=>time(),
                        'status'=>'normal',
                    );
                    Db::name('Category')->insert($add);
                }
            }
            $this->success('修改成功');

        }
    }
    /**
     * 修改三级分类
     */
    public function category_add()
    {
        if ($this->request->isAjax()) {
            //数据输出ajax
            $params = $this->request->post("row/a");
            $pid = $params['pid'];
            if($pid == 0){
                $pid = 14;
            }

            $add = array(
                'name'=>$params['name'],
                'nickname'=>$params['name'],
                'pid'=>$pid,
                'type'=>'product',
                'createtime'=>time(),
                'updatetime'=>time(),
                'status'=>'normal',
            );
            $info = Db::name('Category')->insert($add);
            if($info!==false){
                $this->success('修改成功！');
            }else{
                $this->error('修改失败！');
            }

        }
        return $this->view->fetch();
    }


    public function get_category($parent_id){
        $list = Db::name('category')->where("pid",$parent_id)->select();
        return $list;
    }

}
