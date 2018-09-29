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

            $category=input('category',1);
            $list = Db::name('coupon')
                ->where('coupon_category',$category)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $total = Db::name('coupon')
                ->where('coupon_category',$category)
                ->count();

            $level = array('0'=>'不限','1'=>'普通會員','2'=>'白金會員','3'=>'金牌會員','4'=>'商業會員');
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
            $result = array("total" => $total, "rows" => $list, "extend" => ['money' => mt_rand(100000,999999), 'price' => 200]);
            return json($result);

        }
        return $this->view->fetch();
    }


    /**
     * 详情
     */
    public function detail()
    {

        if($this->request->isAjax()){

            list($where, $sort, $order, $offset, $limit) = $this->buildparams(NULL);
            $coupon_id=$this->request->param("coupon_id");
            $list = Db::name('order')
                ->alias('a')
                ->field('a.*,e.nickname,(select sum(money_total) from fa_order where order_sn=a.order_sn) as money_total')
                ->join('__USER__ e', 'a.user_id=e.id', 'LEFT')
                ->where('coupon_id',$coupon_id)
                ->group('a.order_sn')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $key=>$item){
                $list[$key]['all_price'] = ($item['money_total'] - $item['coupon_price'] - $item['integral_price']+$item['freight'] + $item['contribution_price']) ;
            }

            $total = Db::name('order')
                ->alias('a')
                ->where('coupon_id',$coupon_id)
                ->group('a.order_sn')
                ->count();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

        $today_str_time = date('Y-m-d').' 00:00:00';
        $today_end_time = date('Y-m-d').' 23:59:59';
        $coupon = Db::name('order')
            ->where('coupon_id',input('ids'))
            ->alias('a')
            ->field('a.*,(select count(*) from fa_order where coupon_id=a.coupon_id) as coupon_count,(select count(*) from fa_order where coupon_id=a.coupon_id and addtime >= "'.$today_str_time.'" and addtime <= "'.$today_end_time.'" ) as today_count')
            ->find();
        $this->view->assign("coupon", $coupon);
        $this->assignconfig('coupon_id', input('ids'));
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
            if($data['row']['coupon_term']){
                if($data['row']['coupon_cash'] == '0'){
                    $this->error('現金券不能為零！');
                }
            }

            if($data['row']['coupon_category'] ==1){
                $info = Db::name('coupon')->where(array('coupon_sn'=>$data['row']['coupon_sn']))->find();
                if($info){
                    $this->error('優惠碼已存在！');
                }
                $data['row']['user_level'] = implode(',',$data['row']['user_level']);
                $data['row']['createtime'] = date('Y-m-d H:i:s',time());
                $res=Db::name('coupon')->insertGetId($data['row']);
            }else{
                $coupon_num = $data['row']['coupon_num'];
                $data['row']['user_level'] = implode(',',$data['row']['user_level']);
                $data['row']['createtime'] = date('Y-m-d H:i:s',time());
                for ($x=1; $x<=$coupon_num; $x++) {
                    $data['row']['coupon_sn'] = $this->getRandomString(8);
                    $data['row']['coupon_num'] = 1;
                    $res=Db::name('coupon')->insertGetId($data['row']);
                }
            }

            if ($res)
            {
                $this->success('添加成功！');
            }
            $this->error();
        }

        $this->view->assign("coupon_category",input('category',1));
        return $this->view->fetch();
    }



    function getRandomString($len, $chars=null)
    {
        if (is_null($chars)) {
            $chars = "0123456789";
        }
        mt_srand(10000000*(double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }


    /**
     * 编辑
     */
    public function status($ids = null)
    {
        if ($this->request->isPost())
        {
            $is = input('is');
            $res =  Db::name('coupon')->where('coupon_id',$ids)->update(array('status'=>$is));
            if ($res)
            {
                $this->success('成功！');
            }
            $this->error();
        }
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
