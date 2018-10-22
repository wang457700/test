<?php

namespace app\admin\controller\user;
use think\Db;
use app\common\controller\Backend;

/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class User extends Backend
{

    protected $relationSearch = true;

    /**
     * User模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }


            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with('group')
                    ->where($where)
                    ->where('user_type', 2)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with('group')
                    ->where($where)
                    ->where('user_type', 2)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $level = array('1'=>'普通会员','2'=>'白金会员','3'=>'金牌会员','4'=>'商业会员');
            $reception_romotion_email = array('0'=>'否','1'=>'是');
            foreach ($list as $k => $v)
            {
                $third = Db::name('third')->where('user_id',$v['id'])->value('platform'); //获取用户注册来源
                $v['join_source'] = $third?$third:'普通注册';
                $v['level'] = $level[$v['level']];
                $v['reception_romotion_email'] = $reception_romotion_email[$v['reception_romotion_email']];
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), $row['group_id'], ['class' => 'form-control selectpicker']));
        return parent::edit($ids);
    }

    /**
     * 冻结解冻
     */
    public function status($ids = NULL){
        if ($this->request->isPost())
        {
            $is = input('is');
            $res =  Db::name('user')->where('id',$ids)->update(array('status'=>$is));
            if ($res)
            {
                $this->success('成功！');
            }
            $this->error();
        }


    }

      /**
     * 用户详细
     */
    public function detail($ids = NULL)
    {
       $row = Db::name('user')->where(array('id'=>$ids))->find();
       $address_list = Db::name('user_address')->where('user_id',$ids)->select();

       $order_list = Db::name('order')->where('user_id',$ids)->column('order_sn,order_id');
       foreach ($order_list as $k=>$vo){
          $order_list[$k] = Db::name('order')->where(array('order_sn'=>$k))->find();
          $order_list[$k]['address_info'] = Db::name('user_address')->where(array('id'=>$order_list[$k]['address_id']))->find();
          $order_list[$k]['all_goods_num'] = Db::name('order')->where(array('order_sn'=>$k))->sum('goods_num');
          $order_list[$k]['all_money_total'] =  Db::name('order')->where(array('order_sn'=>$k))->sum('money_total');
       }
        $level = array('1'=>'普通会员','2'=>'白金会员','3'=>'金牌会员','4'=>'商业会员');


       $this->view->assign('row',$row);
       $this->view->assign('level',$level);
       $this->view->assign('address_list',$address_list);
       $this->view->assign('order_list',$order_list);
      return $this->view->fetch();
    }

}
