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
            $data['cat_id']=17;
            $product_name=Db::name('goods')->where('product_name',$data['product_name'])->find();
            if($product_name){
                $this->error('该商品名称已经添加');
            }
            if(empty($data['cat_id'])){
                $this->error('请选择分类');
            }
            $data['add_time']=date('Y-m-d H:i:s',time());
            $img_url=input('img_url/a');
            $data['img_url']=implode(',',$img_url);
            $res=Db::name('goods')->insertGetId($data);
            if($res){
                $this->success('添加成功！');
            }else{
                $this->error('添加失败');
            }


        }

        $this->assign('cat_list',$cat_list);

        return $this->view->fetch();
    }

    /**
     * 查看
     */
    public function index()
    {
        $list=Db::name('goods')->paginate(3);
        $is_on_sale=array(
            '0'=>'下架',
            '1'=>'上架'
        );
        $page = $list->render();
        $this->assign('list',$list);
        $this->assign('page', $page);
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
            //
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
    public function edit($ids = NULL){

          $product_id=input('product_id');
        $product_list=Db::name('goods')->where('product_id',$product_id)->find();
         $img_url=explode(',',$product_list['img_url']);

        $cat_list = Db::name('category')->where("pid = 14")->select();
        if($this->request->isAjax()){
            $data=input('post.');
            $data['cat_id']=17;

            if(empty($data['cat_id'])){
                $this->error('请选择分类');
            }
            $img_url=input('img_url/a');
            $data['img_url']=implode(',',$img_url);
            $res=Db::name('goods')->where($data);
            if($res){
                $this->success('添加成功！');
            }else{
                $this->error('添加失败');
            }
        }

        $this->assign('img_url',$img_url);
        $this->assign('product_list',$product_list);
        $this->assign('product_list',$product_list);
        $this->assign('cat_list',$cat_list);

        return $this->view->fetch();


    }

}
