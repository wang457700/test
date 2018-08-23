<?php

namespace app\admin\controller;
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

        $cat_list = Db::name('category')->where("pid = 0")->select();

        $this->assign('cat_list',$cat_list);

        return $this->view->fetch();
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            //数据输出ajax




        }
        return $this->view->fetch();
    }

    /**
     * 详情
     */
    public function detail($ids)
    {

        $row = array(
            'order_id'=>201807001,
            'order_time'=>'2018/06/30',
            'user_id'=>'152',
            'user_name'=>'Alan Fox',
            'service_charge'=>'$10.00',
            'total_amount'=>'$1,210.40',
            'amount_payable'=>'$1,210.40',
            'take_name'=>'Alan Fox',
            'take_phone'=>'13111111111',
            'take_address'=>'廣東省深圳市龍翔大道志聯佳大厦',
            'freight'=>'$45.00',
            'donated_amount'=>'$180.00',
            'payment_type'=>'信用卡',
            'goods_count'=>'2',
            'coupon'=>'XXXXX XXXX',
            'coupon_amount'=>'$100.00',
            'status'=>'未發貨',
        );

        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isAjax()) {
            $this->success("Ajax请求成功", null, ['id' => $ids]);
        }

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
     /**
     * 编辑
     */
    public function edit($ids = NULL)
    {

        if ($this->request->isPost())
        {
            $params = 1;
            if ($params)
            {
               $this->success();
            }
            $this->error();
        }
        $this->view->assign("row", 1);
        return $this->view->fetch();
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

}
