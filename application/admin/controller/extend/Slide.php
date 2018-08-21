<?php

namespace app\admin\controller\Extend;
use think\Db;
use app\common\controller\Backend;

/**
 * banner管理
 *
 * @icon fa fa-table
 * @remark 在使用Bootstrap-table中的常用方式,更多使用方式可查看:http://bootstrap-table.wenzhixin.net.cn/zh-cn/
 */
class Slide extends Backend
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();

    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {

            
           $list = Db::name('Slide')->select();

            $total = 1;
           /* $list =array(
                array('id'=>1,'name'=>'banner1','url'=>'http://baidu.com','finite_period'=>'2018/01/01-2018/07/30','img'=>'/uploads/20180807/1f80094b66870d833a6073d2fbee9116.jpg','status'=>'顯示'),
                array('id'=>2,'name'=>'banner2','url'=>'http://baidu.com','finite_period'=>'2018/01/01-2018/07/30','img'=>'/uploads/20180807/1f80094b66870d833a6073d2fbee9116.jpg','status'=>'顯示'),
            );*/

            $status =array('0'=>'隐藏','1'=>'顯示');
            foreach ($list as $k => &$v)
            {
                $v['slide_status'] = $status[$v['slide_status']];
                $v['finite_period'] = date('Y/m/d',$v['start_time']).'-'.date('Y/m/d',$v['end_time']);


                //dump();
            }
            unset($v);
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
         
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params){
                $params['start_time'] = strtotime($params['start_time']);
                $params['end_time'] = strtotime($params['end_time']);
            }


            $info = Db::name('Slide')->where('id',$ids)->update($params);
            if ($info!==false)
            {
               $this->success();
            }
        }

        $row = Db::name('Slide')->where('id',$ids)->find();
        $row['start_time'] = date('Y-m-d H:i:s', $row['start_time']);
        $row['end_time'] = date('Y-m-d H:i:s', $row['end_time']);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }


    /**
     *添加
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            $data = $this->request->post("row/a");
            if ($data){
                $data['start_time'] = strtotime($data['start_time']);
                $data['end_time'] = strtotime($data['end_time']);
            }
           
           
            $info = Db::name('Slide')->insert($data);
            if ($info!==false)
            {
               $this->success();
            }
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function del($ids = NULL)
    {
         
        if ($this->request->isPost())
        {
            $info = Db::name('Slide')->where('id',$ids)->delete();
            if ($info!==false)
            {
               $this->success();
            }
        }
    }

  

}
