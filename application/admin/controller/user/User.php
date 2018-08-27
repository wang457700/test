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
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with('group')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            foreach ($list as $k => $v)
            {
                $v['join_source'] = '普通注册';
                $v['level'] = '普通';
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
       $this->view->assign('row',$row);
       $this->view->assign('address_list',$address_list);
       $this->view->assign('order_list',$order_list);
      return $this->view->fetch();
    }

}
