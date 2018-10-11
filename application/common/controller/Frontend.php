<?php

namespace app\common\controller;

use app\common\library\Auth;
use think\Config;
use think\Controller;
use think\Db;
use think\Hook;
use think\Session;
use think\Lang;

/**
 * 前台控制器基类
 */
class Frontend extends Controller
{

    /**
     * 布局模板
     * @var string
     */
    protected $layout = '';

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = [];


    protected $noTouristAuthority = [];

    /**
     * 权限Auth
     * @var Auth
     */
    protected $auth = null;

    public function _initialize()
    {
        //移除HTML标签
        $this->request->filter('strip_tags');
        $modulename = $this->request->module();
        $controllername = strtolower($this->request->controller());
        $actionname = strtolower($this->request->action());
        $site = config('site');

        $seo_title = $site['index_seo']['title'];
        $seo_keywords = $site['index_seo']['keywords'];
        $seo_description = $site['index_seo']['description'];

        $array = array('index','product','news');
        foreach ($array as $item){
            if($controllername == $item){
                $seo_title = $site[$item.'_seo']['title'];
                $seo_keywords = $site[$item.'_seo']['keywords'];
                $seo_description = $site[$item.'_seo']['description'];
                //dump($seo_title);
            }
        }

        $this->view->assign('seo_title', $seo_title);
        $this->view->assign('seo_keywords', $seo_keywords);
        $this->view->assign('seo_description', $seo_description);

        // 如果有使用模板布局
        if ($this->layout) {
            $this->view->engine->layout('layout/' . $this->layout);
        }
        $this->auth = Auth::instance();

        // token
        $token = $this->request->server('HTTP_TOKEN', $this->request->request('token', \think\Cookie::get('token')));


        $path = str_replace('.', '/', $controllername) . '/' . $actionname;


        // 设置当前请求的URI
        $this->auth->setRequestUri($path);

        $user = Db::name('user')->where(array('id'=>Session::get('user_id')))->find();

        // 检测是否需要验证登录
        if (!$this->auth->match($this->noNeedLogin)) {
            //初始化
            $this->auth->init($token);
            //检测是否登录
            if (!Session::get('user_id')) {
                $this->error(__('Please login first'), 'user/login');
            }

            // 检测游客权限
            if ($this->auth->match($this->noTouristAuthority) && $user['user_type'] == 3) {
                $this->error(__('游客没有权限访问'), 'user/center');
            }

            //检测账号是否激活
            if($user['is_eamil_status'] == 0){
                $this->error(__('帳號還沒有激活！'), 'user/user_email_activation','',0);
            }



            $user_id = Session::get('user_id');
            //检查第三方是否绑定账号
            $third = Db::name('third')->where(array('user_id'=>$user_id))->find();
            if($third['platform']){

                $uid = Db::name('third')->where('user_id',$user_id)->value('uid');
                if(empty($uid)){
                    //没有，去绑定
                    //$third_user_id 传到 home/user/user_bindsns
                    Session::set("third_user_id", $user_id);
                    $this->redirect(url('home/user/user_bindsns'));
                    exit;
                }

                $u_user = Db::name('user')->where('id',$uid)->find();
                Session::set("user_id",$uid);
                Session::set("user", $u_user);
                Session::set("platform", $third['platform']); //记录登入第三方

                $this->redirect(url('home/user/center'));
            }

            //检测账号是否綁定電子郵箱
            if(empty($user['email'])){
                $this->error(__('帳號還綁定電子郵箱！'), 'user/user_bindsns','',0);
            }






        } else {
            // 如果有传递token才验证是否登录状态
            if ($token){
                $this->auth->init($token);
            }
        }

        $this->view->assign('user', $this->auth->getUser());

        // 语言检测
        $lang = strip_tags($this->request->langset());

        $site = Config::get("site");

        $upload = \app\common\model\Config::upload();

        // 上传信息配置后
        Hook::listen("upload_config_init", $upload);

        // 配置信息
        $config = [
            'site'           => array_intersect_key($site, array_flip(['name', 'cdnurl', 'version', 'timezone', 'languages'])),
            'upload'         => $upload,
            'modulename'     => $modulename,
            'controllername' => $controllername,
            'actionname'     => $actionname,
            'jsname'         => 'frontend/' . str_replace('.', '/', $controllername),
            'moduleurl'      => rtrim(url("/{$modulename}", '', false), '/'),
            'language'       => $lang
        ];
        $config = array_merge($config, Config::get("view_replace_str"));

        Config::set('upload', array_merge(Config::get('upload'), $upload));

        // 配置信息后
        Hook::listen("config_init", $config);
        // 加载当前控制器语言包
        $this->loadlang($controllername);
        $this->assign('site', $site);
        $this->assign('config', $config);
    }

    /**
     * 加载语言文件
     * @param string $name
     */
    protected function loadlang($name)
    {
        Lang::load(APP_PATH . $this->request->module() . '/lang/' . $this->request->langset() . '/' . str_replace('.', '/', $name) . '.php');
    }

    /**
     * 渲染配置信息
     * @param mixed $name 键名或数组
     * @param mixed $value 值
     */
    protected function assignconfig($name, $value = '')
    {
        $this->view->config = array_merge($this->view->config ? $this->view->config : [], is_array($name) ? $name : [$name => $value]);
    }


    protected function is_login(){

        $this->result(1);

    }





}
