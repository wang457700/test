<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;
use app\common\library\Email;
use app\common\model\Config as ConfigModel;
use think\Exception;
use think\Db;
use think\Session;

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

        $order_sn = '201811211542797686';
        $order_info = Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->find();
        $user= Db::name("user")->where(array('id'=>Session::get('user_id')))->find();
        $order_list = Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->select();
        $address_info = sp_address_info(Session::get('user_id'),$order_info['address_id'],'id,name,phone,phone_type');

        foreach ($order_list as $v){
            save_goods_stock($v['goods_id'],$v['goods_num']);
            $product_name [] = sp_product_info($v['goods_id'])['product_name'] .' '.$v['goods_num'].'×'.$v['price'];
        }

        $row = $this->request->post('row/a');
        \think\Config::set('site', array_merge(\think\Config::get('site'), $row));
        $receiver = $this->request->request("receiver");
        $email = new Email;
        $url = 'https://mail.qq.com';
        $result = $email
            ->to($receiver)
            ->subject(__("This is a test mail"))
            ->message('<div style="width:950px;margin:0 auto;background:#7ac141;border-radius:20px;padding:50px;padding-bottom:1px;text-align:center"><div style="background:#fff;font-size:20px;font-weight:400;padding:30px 90px;border-radius:20px;text-align:left"><img src="http://wsstest.teamotto.me/hksr/public/assets/img/logo400.png" style="width:200px"><br>親愛的'.$user['nickname'].',<br><br>感謝您的購買，您的預訂已確認！<br><br>您的訂單號是:<br>'.$order_info['order_sn'].'<br><br>預訂詳情<br>日期： '.week(date('w',strtotime($order_info['addtime']))).'，'.month(date('m',strtotime($order_info['addtime']))).date('Y',strtotime($order_info['addtime'])).'<br>時間： '.date('H:i',strtotime($order_info['addtime'])).'<br>購買： '.implode('，',$product_name).'<br>已付金額：$ '.sum_order_payableprice($order_info['order_sn']).'<br>您的訂單正在處理中，<br>我們將會把您的貨品派送到：<br><br>'.$address_info['name'].'<br>'.$user['email'].'<br>'.$address_info['phone_type'].' '.$address_info['phone'].' (手機）<br>'.$order_info['address'].'<br>更多關於訂單的詳細資訊和更新情況，請<a href="'.url('user/login','','',true).'">登入</a>您的帳戶查詢。<br><br>謝謝！<br>客戶服務中心<br>營康薈Live Smart<br><br><br>這是一封自動生成的電子郵件，請不要回覆。<br>如果您對您的帳戶有任何疑問，<br>請與我們聯絡dsc@wahhong.hk<br><p style="text-align:center;color:#7ac141;font-size:26px">香港復康會屬下社企"營康薈"支持殘疾人仕及長期病患者投入社會</p></div><p style="font-size:40px;color:#fff;font-weight:bold"><a style="color:#fff;text-decoration:none" href="'.$url.'">'.$url.'</a></p></div>')
            //->message('<div style="min-height:550px; padding: 100px 55px 200px;">' . __('This is a test mail content') . '</div>')
           // ->message('test')
            ->send();
        if ($result) {
            $this->success();
        } else {
            $this->error($email->getError());
        }
    }

}
