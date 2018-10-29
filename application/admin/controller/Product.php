<?php

namespace app\admin\controller;
use fast\Arr;
use think\config\driver\Json;
use think\Db;
use think\validate;
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
        //$categorydata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->categorylist as $k => $v)
        {
            $categorydata[$v['id']] = $v;
        }

        $this->view->assign("flagList", $this->model->getFlagList());
        $this->view->assign("typeList", CategoryModel::getTypeList());
        $this->view->assign("parentList", $categorydata);

    }



    /**
     * 地区管理
     */
    public function region_list()
    {


    }
    public function add(){

        $cat_list = Db::name('category')->where("pid = 14")->select();
        if($this->request->isAjax()){
            $data=input('post.');

            $product_name=Db::name('goods')->where('product_name',$data['product_name'])->find();
            if($product_name){
               // $this->error('该商品名称已经添加');
            }

            $data['cat_id'] = array_filter($data['cat_id']);
            $cat_count = count($data['cat_id']);
            if($cat_count){
                $data['cat_id'] = $data['cat_id'][$cat_count-1];
            }
            if(empty($data['cat_id'])){
                $this->error('请选择分类');
            }

            if(empty($data['cover'])){
                $this->error('至少上传一张封面图！');
            }

            if($data['discount_type'] == 2 ){
                if($data['pricevip'] =='0' || empty($data['pricevip'])){
                    $this->error('特價不能為零！');
                }
            }

            if($data['discount_type'] == 3){
                if($data['discount_price'] =='0' || empty($data['discount_price'])){
                    $this->error('优惠不能為零！');
                }
                if(empty($data['discount_start_time'])){
                    $this->error('优惠开始时间不能為空！');
                }
                if(empty($data['discount_end_time'])){
                    $this->error('优惠结束时间不能為空！');
                }
            }

            $data['add_time']=date('Y-m-d H:i:s',time());
            $res=Db::name('goods')->insertGetId($data);
            if($res){
                $this->success('添加成功！'.$data['pricevip'],url('product/index'));
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

            $data['cat_id'] = array_filter($data['cat_id']);
            $cat_count = count($data['cat_id']);
            if($cat_count){
                $data['cat_id'] = $data['cat_id'][$cat_count-1];
            }
            if(empty($data['cat_id'])){
                $this->error('请选择分类');
            }
            if(empty($data['cover'])){
                $this->error('至少上传一张封面图！');
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
        ->order('product_id desc')
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
            $tree = Tree::instance();

            //数据输出ajax
            $total = 1;
            $where = array();
            $list = [];
            $cid = $this->request->get('type');
            if($cid == 'all' || empty($cid)){
                 foreach ($this->categorylist as $k => $v){
                       if($v['type'] == 'product') {
                               $list[] = $v;
                       }
                }
            }else{
                $tree->init(collection($this->model->order('weigh desc,id desc')->select())->toArray(), 'pid');
                $list = $this->categorylist = $tree->getTreeList($tree->getTreeArray($cid), 'name');
            }
            foreach ($list as $k =>$item){
                $item['name'] = '<i style="color:#bfbfbf">CatID:'.$item['id'].'</i>'.$item['name'];
                $list[$k] = $item;
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

    public function import(){
        $file = $this->request->request('file');
        if (!$file) {
            $this->error(__('Parameter %s can not be empty', 'file'));
        }
        $filePath = ROOT_PATH . DS . 'public' . DS . $file;
        if (!is_file($filePath)) {
            $this->error(__('No results were found'));
        }
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($filePath)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($filePath)) {
                $PHPReader = new \PHPExcel_Reader_CSV();
                if (!$PHPReader->canRead($filePath)) {
                    $this->error(__('Unknown data format'));
                }
            }
        }
        $PHPExcel = $PHPReader->load($filePath); //加载文件
        $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
        $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
        $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
        $maxColumnNumber = \PHPExcel_Cell::columnIndexFromString($allColumn);
        for ($currentRow = 1; $currentRow <= 1; $currentRow++) {
            for ($currentColumn = 0; $currentColumn < $maxColumnNumber; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                $fields[] = $val;
            }
        }

        $insert = [];
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
            $values = [];
            for ($currentColumn = 0; $currentColumn < $maxColumnNumber; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                $values[] = is_null($val) ? '' : $val;
            }
            $row = [];
            $temp = array_combine($fields, $values);
            foreach ($temp as $k => $v) {
                $row[$k] = $v;
            }
            if ($row) {
                $insert[] = $row;
            }

        }
        if (!$insert) {
            $this->error(__('No rows were updated'));
        }


        $add = [];
        foreach ($insert as $k => $vo){
            $goods = Db::name('goods')->where(array('freight_num'=>$vo['ProductCode']))->find();
            $category = Db::name('category')->where(array('id'=>$vo['CatID']))->find();
            if(empty($goods) && !empty($category)){
                $vo['discount_type'] = 1;
                if(!empty($vo['Price2']) && $vo['Price2'] !='0.00'){
                    $vo['discount_type'] = 2;
                }
                $add[] = array(
                    'product_name'=>$vo['LNameL1'],
                    'stock'=>100,
                    'freight_num'=>$vo['ProductCode'],
                    'price'=>$vo['Price1'],
                    'pricevip'=>$vo['Price2'],
                    'cat_id'=>$vo['CatID'],
                    'discount_type'=>$vo['discount_type'],
                    'cover'=>'/uploads/20181023/656b87a1328e1a24efbcca1d4b039d29.jpg',
                    'add_time'=>date('Y-m-d H:i:s',time()),
                    'seo_title'=>$vo['LNameL1'],
                    'is_on_sale'=>$vo['Publish'],
                );
            }else{
                $vo['discount_type'] = 1;
                if(!empty($vo['Price2']) && $vo['Price2'] !='0.00'){
                    $vo['discount_type'] = 2;
                }
                Db::name('goods')->where(array('freight_num'=>$vo['ProductCode']))->update(array(
                    'product_name'=>$vo['LNameL1'],
                    'stock'=>100,
                    'price'=>$vo['Price1'],
                    'pricevip'=>$vo['Price2'],
                    'cat_id'=>$vo['CatID'],
                    'discount_type'=>$vo['discount_type'],
                    'seo_title'=>$vo['LNameL1'],
                    'is_on_sale'=>$vo['Publish'],
                ));
            }
        }

       $info = Db::name('goods')->insertAll($add);
       $this->success('导入成功！','',array('refresh'=>1));
    }

    public function get_category($parent_id){
        $list = Db::name('category')->where("pid",$parent_id)->select();
        return $list;
    }

}
