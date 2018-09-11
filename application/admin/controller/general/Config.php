<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;
use app\common\library\Email;
use app\common\model\Config as ConfigModel;
use think\Exception;
use think\Db;

/**
 * 系统配置
 *
 * @icon fa fa-cogs
 * @remark 可以在此增改系统的变量和分组,也可以自定义分组和变量,如果需要删除请从数据库中删除
 */
class Config extends Backend
{

    protected $model = null;
    protected $noNeedRight = ['check'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Config');
    }

    /**
     * 查看
     */
    public function index()
    {
        $siteList = [];
        $groupList = ConfigModel::getGroupList();
        foreach ($groupList as $k => $v) {
            $siteList[$k]['name'] = $k;
            $siteList[$k]['title'] = $v;
            $siteList[$k]['list'] = [];
        }

        foreach ($this->model->all() as $k => $v) {
            if (!isset($siteList[$v['group']])) {
                continue;
            }
            $value = $v->toArray();
            $value['title'] = __($value['title']);
            if (in_array($value['type'], ['select', 'selects', 'checkbox', 'radio'])) {
                $value['value'] = explode(',', $value['value']);
            }
            $value['content'] = json_decode($value['content'], TRUE);
            $siteList[$v['group']]['list'][] = $value;
        }
        $index = 0;
        foreach ($siteList as $k => &$v) {
            $v['active'] = !$index ? true : false;
            $index++;
        }
        $this->view->assign('siteList', $siteList);
        $this->view->assign('typeList', ConfigModel::getTypeList());
        $this->view->assign('groupList', ConfigModel::getGroupList());
        return $this->view->fetch();
    }


    /**
     * 查看
     */
    public function index_meta()
    {
        $siteList = [];
        $groupList = ConfigModel::getGroupList();
        foreach ($groupList as $k => $v) {
            $siteList[$k]['list'] = [];
        }
        foreach ($this->model->all() as $k => $v) {
            if (!isset($siteList[$v['group']])) {
                continue;
            }
            $value = $v->toArray();
            if($value['type'] == 'array') {
                $data [] = $value;
            }
        }
        foreach ($data as $index=>$item){
            $item['value'] = json_decode($item['value'],true);
            $config_data[$item['name']] =  $item['value'];
        }
        $region= Db::name('region')->where(array('level'=>2,'is_remote_area'=>0))->column('name');
        $this->view->assign('config_data', $config_data);
        $this->view->assign('region', $region); //显示免服务费地区 - 市
        return $this->view->fetch();
    }

    /**
     * 修改其他设置
     */
    public function meta_edit()
    {
        if ($this->request->isPost()) {
            $row = $this->request->post("row/a");
            foreach ($row as $name=>$item){
                $item = json_encode($item,true);
                $info= Db::name('config')->where('name',$name)->update(array('value'=>$item));
            }
            if($info!==false){
                $this->refreshFile();
                $this->success('保存成功');
            }
        }
    }

    /**
     * 设置偏远地区
     */
    public function remotearea()
    {
        if ($this->request->isAjax()) {
            //数据输出ajax
            $total = 1;
            $where = array();
            $pid = $this->request->get('type',47494);

            $where['parent_id'] = $pid;
            $list = Db::name('region')->where($where)->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

        $region_list = Db::name('region')
            ->alias('a')
            ->field('a.*,(select count(id) from fa_region where parent_id=a.id and is_remote_area=0 and level=2) as count')
            ->where('level',1)
            ->select();

        $this->view->assign("region_list", $region_list);
        return $this->view->fetch();
    }

    public function isremotearea($ids = '')
    {
        $is = input('is');
        $res = Db::name('region')->where('id',$ids)->update(array('is_remote_area'=>$is));
        $region = Db::name('region')->where('parent_id',$ids)->find();
        if ($region){
           Db::name('region')->where('parent_id',$ids)->update(array('is_remote_area'=>$is));
        }
        if($res){
            $this->success("成功");
        }else{
            $this->error('失败');
        }
    }


    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                foreach ($params as $k => &$v) {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                try {
                    if (in_array($params['type'], ['select', 'selects', 'checkbox', 'radio', 'array'])) {
                        $params['content'] = json_encode(ConfigModel::decode($params['content']), JSON_UNESCAPED_UNICODE);
                    } else {
                        $params['content'] = '';
                    }
                    $result = $this->model->create($params);
                    if ($result !== false) {
                        try {
                            $this->refreshFile();
                            $this->success();
                        } catch (Exception $e) {
                            $this->error($e->getMessage());
                        }
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     * @param null $ids
     */
    public function edit($ids = NULL)
    {
        if ($this->request->isPost()) {
            $row = $this->request->post("row/a");
            if ($row) {
                $configList = [];
                foreach ($this->model->all() as $v) {
                    if (isset($row[$v['name']])) {
                        $value = $row[$v['name']];
                        if (is_array($value) && isset($value['field'])) {
                            $value = json_encode(ConfigModel::getArrayData($value), JSON_UNESCAPED_UNICODE);
                        } else {
                            $value = is_array($value) ? implode(',', $value) : $value;
                        }
                        $v['value'] = $value;
                        $configList[] = $v->toArray();
                    }
                }
                $this->model->allowField(true)->saveAll($configList);
                try {
                    $this->refreshFile();
                    $this->success();
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
    }

    /**
     * 刷新配置文件
     */
    protected function refreshFile()
    {
        $config = [];
        foreach ($this->model->all() as $k => $v) {

            $value = $v->toArray();
            if (in_array($value['type'], ['selects', 'checkbox', 'images', 'files'])) {
                $value['value'] = explode(',', $value['value']);
            }
            if ($value['type'] == 'array') {
                $value['value'] = (array)json_decode($value['value'], TRUE);
            }
            $config[$value['name']] = $value['value'];
        }
        file_put_contents(APP_PATH . 'extra' . DS . 'site.php', '<?php' . "\n\nreturn " . var_export($config, true) . ";");
    }

    /**
     * 检测配置项是否存在
     * @internal
     */
    public function check()
    {
        $params = $this->request->post("row/a");
        if ($params) {

            $config = $this->model->get($params);
            if (!$config) {
                return $this->success();
            } else {
                return $this->error(__('Name already exist'));
            }
        } else {
            return $this->error(__('Invalid parameters'));
        }
    }

    /**
     * 发送测试邮件
     * @internal
     */
    public function emailtest()
    {
        $row = $this->request->post('row/a');
        \think\Config::set('site', array_merge(\think\Config::get('site'), $row));
        $receiver = $this->request->request("receiver");
        $email = new Email;

        dump($email);
        $result = $email
            ->to($receiver)
            ->subject(__("This is a test mail"))
            ->message('<div style="min-height:550px; padding: 100px 55px 200px;">' . __('This is a test mail content') . '</div>')
            ->send();
        if ($result) {
            $this->success();
        } else {
            $this->error($email->getError());
        }
    }

}
