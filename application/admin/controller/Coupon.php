<?php

namespace app\admin\controller;
use think\Db;
use app\common\controller\Backend;

/**
 * 优惠券管理
 * 添加|编辑|删除
 */
class Coupon extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AdminLog');
    }

    /**
     * 通用优惠券
     */
    public function index()
    {
        if ($this->request->isAjax()){
            //数据输出ajax
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(NULL);

            $list = Db::name('coupon')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $level = array('0'=>'不限','1'=>'普通会员','2'=>'白金会员','3'=>'金牌会员','4'=>'商业会员');
            foreach ($list as $key=>$item){
                $list[$key]['coupon_time_text'] = ''.date('Y/m/d',strtotime($item['coupon_end_time']));
                if($item['user_level']){
                    $user_level = explode(',',$item['user_level']);
                    foreach ($user_level as $v){
                        $user_level_text[$key][] = $level[$v];
                    }
                    $list[$key]['user_level_text']=  implode(',',$user_level_text[$key]);
                }else{
                    $list[$key]['user_level_text']=  $level[$item['user_level']];
                }
            }
            $result = array("total" => 1, "rows" => $list, "extend" => ['money' => mt_rand(100000,999999), 'price' => 200]);
            return json($result);

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
        if ($this->request->isAjax()){
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
            $data=input('post.');
            if($data['row']['coupon_term'] == 0){
                if($data['row']['coupon_cash'] == '0'){
                    $this->error('現金券不能為零！');
                }
            }

            $data['row']['user_level'] = implode(',',$data['row']['user_level']);
            $data['row']['createtime'] = date('Y-m-d H:i:s',time());
            $res=Db::name('coupon')->where('coupon_id',$data['coupon_id'])->update($data['row']);

            if ($res)
            {
               $this->success();
            }
            $this->error();
        }

        $row =Db::name('coupon')
            ->where('coupon_id',$ids)
            ->find();
        $row['user_level'] = explode(',',$row['user_level']);
        $level = array('0'=>'不限','1'=>'普通会员','2'=>'白金会员','3'=>'金牌会员','4'=>'商业会员');
        $this->view->assign("row", $row);
        $this->view->assign("level", $level);
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            $data=input('post.');
            $info = Db::name('coupon')->where(array('coupon_sn'=>$data['coupon_sn']))->find();
            if($info){
                $this->error();
            }
            if($data['coupon_term']){
                if($data['coupon_cash'] == '0'){
                    $this->error('現金券不能為零！');
                }
            }
            $data['row']['user_level'] = implode(',',$data['row']['user_level']);
            $data['row']['createtime'] = date('Y-m-d H:i:s',time());
            $res=Db::name('coupon')->insertGetId($data['row']);
            if ($res)
            {
                $this->success('添加成功！');
            }
            $this->error();
        }
        return $this->view->fetch();
    }
    



    /**
     * 一次性优惠券
     */
    public function disposable_index()
    {
        if ($this->request->isAjax()) {
            //数据输出ajax

           

        }
        return $this->view->fetch();
    }

    /**
     * 一次性优惠码添加
     */
    public function disposable_add($ids = NULL)
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
     * 一次性优惠码编辑
     */
    public function disposable_edit($ids = NULL)
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

        }
        return $this->view->fetch();
    }
 
}
