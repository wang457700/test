<?php

namespace app\admin\controller\Extend;
use think\Db;
use app\common\controller\Backend;
use think\Config;

/**
 * banner管理
 *
 * @icon fa fa-table
 * @remark 在使用Bootstrap-table中的常用方式,更多使用方式可查看:http://bootstrap-table.wenzhixin.net.cn/zh-cn/
 */
class News extends Backend
{
    protected $model = null;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Article');
    }

    /**
     * 查看
     */
    public function index(){
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(NULL);

            $where = array('post_type'=>2);
            $total = Db::name('Article')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = Db::name('Article')
            ->limit($offset, $limit)
            ->where($where)
            ->select(); 
            

             $status =array('0'=>'隐藏','1'=>'顯示');
            foreach ($list as $k => &$v)
            {
                $v['post_status'] = $status[$v['post_status']];
                $v['post_terms'] = Db::name('category')->where('id',$v['post_term_id'])->value('name');
                $v['post_date'] = date('Y/m/d',strtotime($v['post_date']));
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
            $params['post_type'] = 2;
            $info = Db::name('Article')->where('id',$ids)->update($params);
            if ($info!==false)
            {
               $this->success('修改成功');
            }
        }

        $newscategory = sp_getTreeList(1);
        $row = Db::name('Article')->where('id',$ids)->find();
        $this->view->assign("row", $row);
        $this->view->assign("newscategory", $newscategory);
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $params['post_type'] = 2;
           /* if ($params){
                $params['start_time'] = strtotime($params['start_time']);
                $params['end_time'] = strtotime($params['end_time']);
            }*/
            $params['post_date'] = date('Y-m-d H:i:s', time());
            $info = Db::name('Article')->insert($params);
            if ($info!==false)
            {
               $this->success('添加成功');
            }
        }

        $newscategory = sp_getTreeList(1);
        $this->view->assign("newscategory", $newscategory);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = NULL)
    {
        if ($this->request->isPost())
        {
            $info = Db::name('Article')->where('id',$ids)->delete();
            if ($info!==false)
            {
               $this->success('删除成功');
            }
        }
    }


    /**
     * 隐藏
     */
    public function status_hide($ids = NULL)
    {
        if ($this->request->isPost())
        {
            $info = Db::name('Article')->where('id',$ids)->update('post_status',0);
            if ($info!==false)
            {
               $this->success();
            }
        }
    }


    /**
     * 显示
     */
    public function status_display($ids = NULL)
    {
        if ($this->request->isPost())
        {
           $info = Db::name('Article')->where('id',$ids)->update('post_status',1);
            if ($info!==false)
            {
               $this->success('');
            }
        }
    }


    /**
     * 导入
     */
//    public function import()
//    {
//        Config::set('default_return_type', 'json');
//        $file = $this->request->param('file');
//        dump($file);
//    }

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

        $info = Db::name('Article')->insertAll($insert);

        $this->success('导入成功！');
    }



  

}
