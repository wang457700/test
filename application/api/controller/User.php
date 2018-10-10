<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Ems;
use app\common\library\Sms;
use fast\Random;
use think\Validate;
use think\Session;
use think\Image;
use think\Db;
/**
 * 会员接口
 */
class User extends Api
{

    protected $noNeedLogin = ['login', 'mobilelogin', 'register', 'resetpwd', 'changeemail', 'changemobile', 'third'];
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 会员中心
     */
    public function index()
    {
        $this->success('', ['welcome' => $this->auth->nickname]);
    }

    /**
     * 会员登录
     * 
     * @param string $account 账号
     * @param string $password 密码
     */
    public function login()
    {
        $account = $this->request->request('account');
        $password = $this->request->request('password');
        if (!$account || !$password)
        {
            $this->error(__('Invalid parameters'));
        }
        $ret = $this->auth->login($account, $password);
        if ($ret)
        {
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success(__('Logged in successful'), $data);
        }
        else
        {
            $this->error($this->auth->getError());
        }
    }

    /**
     * 手机验证码登录
     * 
     * @param string $mobile 手机号
     * @param string $captcha 验证码
     */
    public function mobilelogin()
    {
        $mobile = $this->request->request('mobile');
        $captcha = $this->request->request('captcha');
        if (!$mobile || !$captcha)
        {
            $this->error(__('Invalid parameters'));
        }
        if (!Validate::regex($mobile, "^1\d{10}$"))
        {
            $this->error(__('Mobile is incorrect'));
        }
        if (!Sms::check($mobile, $captcha, 'mobilelogin'))
        {
            $this->error(__('Captcha is incorrect'));
        }
        $user = \app\common\model\User::getByMobile($mobile);
        if ($user)
        {
            //如果已经有账号则直接登录
            $ret = $this->auth->direct($user->id);
        }
        else
        {
            $ret = $this->auth->register($mobile, Random::alnum(), '', $mobile, []);
        }
        if ($ret)
        {
            Sms::flush($mobile, 'mobilelogin');
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success(__('Logged in successful'), $data);
        }
        else
        {
            $this->error($this->auth->getError());
        }
    }

    /**
     * 注册会员
     * 
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $email 邮箱
     * @param string $mobile 手机号
     */
    public function register()
    {
        $username = $this->request->request('username');
        $password = $this->request->request('password');
        $email = $this->request->request('email');
        $mobile = $this->request->request('mobile');
        if (!$username || !$password)
        {
            $this->error(__('Invalid parameters'));
        }
        if ($email && !Validate::is($email, "email"))
        {
            $this->error(__('Email is incorrect'));
        }
        if ($mobile && !Validate::regex($mobile, "^1\d{10}$"))
        {
            $this->error(__('Mobile is incorrect'));
        }
        $ret = $this->auth->register($username, $password, $email, $mobile, []);
        if ($ret)
        {
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success(__('Sign up successful'), $data);
        }
        else
        {
            $this->error($this->auth->getError());
        }
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        $this->auth->logout();
        $this->success(__('Logout successful'));
    }

    /**
     * 修改会员个人信息
     * 
     * @param string $avatar 头像地址
     * @param string $username 用户名
     * @param string $nickname 昵称
     * @param string $bio 个人简介
     */
    public function profile()
    {
        $user = $this->auth->getUser();
        $username = $this->request->request('username');
        $nickname = $this->request->request('nickname');
        $bio = $this->request->request('bio');
        $avatar = $this->request->request('avatar');
        $exists = \app\common\model\User::where('username', $username)->where('id', '<>', $this->auth->id)->find();
        if ($exists)
        {
            $this->error(__('Username already exists'));
        }
        $user->username = $username;
        $user->nickname = $nickname;
        $user->bio = $bio;
        $user->avatar = $avatar;
        $user->save();
        $this->success();
    }

    /**
     * 修改邮箱
     * 
     * @param string $email 邮箱
     * @param string $captcha 验证码
     */
    public function changeemail()
    {
        $user = $this->auth->getUser();
        $email = $this->request->post('email');
        $captcha = $this->request->request('captcha');
        if (!$email || !$captcha)
        {
            $this->error(__('Invalid parameters'));
        }
        if (!Validate::is($email, "email"))
        {
            $this->error(__('Email is incorrect'));
        }
        if (\app\common\model\User::where('email', $email)->where('id', '<>', $user->id)->find())
        {
            $this->error(__('Email already exists'));
        }
        $result = Ems::check($email, $captcha, 'changeemail');
        if (!$result)
        {
            $this->error(__('Captcha is incorrect'));
        }
        $verification = $user->verification;
        $verification->email = 1;
        $user->verification = $verification;
        $user->email = $email;
        $user->save();

        Ems::flush($email, 'changeemail');
        $this->success();
    }

    /**
     * 修改手机号
     * 
     * @param string $email 手机号
     * @param string $captcha 验证码
     */
    public function changemobile()
    {
        $user = $this->auth->getUser();
        $mobile = $this->request->request('mobile');
        $captcha = $this->request->request('captcha');
        if (!$mobile || !$captcha)
        {
            $this->error(__('Invalid parameters'));
        }
        if (!Validate::regex($mobile, "^1\d{10}$"))
        {
            $this->error(__('Mobile is incorrect'));
        }
        if (\app\common\model\User::where('mobile', $mobile)->where('id', '<>', $user->id)->find())
        {
            $this->error(__('Mobile already exists'));
        }
        $result = Sms::check($mobile, $captcha, 'changemobile');
        if (!$result)
        {
            $this->error(__('Captcha is incorrect'));
        }
        $verification = $user->verification;
        $verification->mobile = 1;
        $user->verification = $verification;
        $user->mobile = $mobile;
        $user->save();

        Sms::flush($mobile, 'changemobile');
        $this->success();
    }

    /**
     * 第三方登录
     * 
     * @param string $platform 平台名称
     * @param string $code Code码
     */
    public function third()
    {
        $url = url('user/index');
        $platform = $this->request->request("platform");

        $code = $this->request->request("code");

        $config = get_addon_config('third');
        if (!$config || !isset($config[$platform]))
        {
            $this->error(__('Invalid parameters'));
        }
        $app = new \addons\third\library\Application($config);
        //通过code换access_token和绑定会员
        $result = $app->{$platform}->getUserInfo(['code' => $code]);
        if ($result)
        {
            $loginret = \addons\third\library\Service::connect($platform, $result);
            if ($loginret)
            {
                $data = [
                    'userinfo'  => $this->auth->getUserinfo(),
                    'thirdinfo' => $result
                ];

                //检查第三方是否绑定账号
                $uid = Db::name('third')->where('user_id',$data['userinfo']['id'])->value('uid');
                if(empty($uid)){
                    //没有，去绑定
                    //$third_user_id 传到 home/user/user_bindsns
                    Session::set("third_user_id", $data['userinfo']['id']);
                    $this->success(__('Logged in successful'),url('home/user/user_bindsns'));
                }

                $u_user = Db::name('user')->where('id',$uid)->find();
                Session::set("user_id",$uid);
                Session::set("user", $u_user);
                Session::set("platform", $platform); //记录登入第三方
                $this->success(__('Logged in successful'), url('home/user/center'));
            }
        }
        $this->error(__('Operation failed'), $url);
    }

    /**
     * 重置密码
     * 
     * @param string $mobile 手机号
     * @param string $newpassword 新密码
     * @param string $captcha 验证码
     */
    public function resetpwd()
    {
        $type = $this->request->request("type");
        $mobile = $this->request->request("mobile");
        $email = $this->request->request("email");
        $newpassword = $this->request->request("newpassword");
        $captcha = $this->request->request("captcha");
        if (!$newpassword || !$captcha)
        {
            $this->error(__('Invalid parameters'));
        }
        if ($type == 'mobile')
        {
            if (!Validate::regex($mobile, "^1\d{10}$"))
            {
                $this->error(__('Mobile is incorrect'));
            }
            $user = \app\common\model\User::getByMobile($mobile);
            if (!$user)
            {
                $this->error(__('User not found'));
            }
            $ret = Sms::check($mobile, $captcha, 'resetpwd');
            if (!$ret)
            {
                $this->error(__('Captcha is incorrect'));
            }
            Sms::flush($mobile, 'resetpwd');
        }
        else
        {
            if (!Validate::is($email, "email"))
            {
                $this->error(__('Email is incorrect'));
            }
            $user = \app\common\model\User::getByEmail($email);
            if (!$user)
            {
                $this->error(__('User not found'));
            }
            $ret = Ems::check($email, $captcha, 'resetpwd');
            if (!$ret)
            {
                $this->error(__('Captcha is incorrect'));
            }
            Ems::flush($email, 'resetpwd');
        }
        //模拟一次登录
        $this->auth->direct($user->id);
        $ret = $this->auth->changepwd($newpassword, '', true);
        if ($ret)
        {
            $this->success(__('Reset password successful'));
        }
        else
        {
            $this->error($this->auth->getError());
        }
    }

    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/uploads/ 目录下
        $path = 'uploads/cards';
        $info = $file->validate(['size'=>1000000,'ext'=>'jpg,png,gif'])->move($path);
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg

            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            $this->success('上傳成功',$path.'/'.$info->getSaveName());
        }else{
            // 上传失败获取错误信息
            $this->error('上傳失敗');
        }
    }

    public function cx_coupon(){
        $data['coupon_price'] = [];
        $goods_json = json_decode(base64_decode(input('goods_json')),true); //商品ids
        $coupon_sn = input('coupon');
        $data = $this->api_cx_coupon($goods_json,$coupon_sn);
        if($data['coupon_price']){
            $this->success('ok',array('coupon_price'=>$data['coupon_price'],'coupon_name'=>$data['coupon_name']));
        }
    }


    /**
     * @param $goods_list
     * @param $coupon_sn
     * @return array
     */
    //查询优惠券
    public function api_cx_coupon($goods_list,$coupon_sn){
        if(!is_array($goods_list)){
            $this->error('goods_list is not array！');
        }
        if(empty($coupon_sn)){
            $this->error('coupon_sn is empty！');
        }

        $coupon['no_product_categoryids'] = [];
        $no_product_categoryids = [];
        $coupon_goods = [];
        $all_total = 0;
        $user_id = Session::get('user_id');

        foreach($goods_list as $k =>$v){
            $goods_cat_ids[] = $v['cat_id'];
        }
        $user = Db::name('user')->where('id',Session::get('user_id'))->find();

        /*优惠券*/
        $coupon  = Db::name('coupon')->where(array('coupon_sn'=>$coupon_sn))->find();
        $coupon_log = Db::name('order')->where(array('coupon_id'=>$coupon['coupon_id'],'user_id'=>Session::get('user_id')))->find();
        if($coupon_log){
            $this->error('你已使用該優惠碼，請勿重複使用！！');
        }
        //过滤不可用分类
        if($coupon['no_product_categoryids']){
            foreach(explode(',',$coupon['no_product_categoryids']) as $item){
                if(in_array($item,$goods_cat_ids)){
                    $no_product_categoryids[] = $item;
                }
            }
        }

        foreach($goods_list as $k =>$v){
            if(!in_array($v['cat_id'],$no_product_categoryids)){
                if($coupon['no_specials']){
                    if($v['discount_type'] !=2){
                        $coupon_goods[] = $v;
                    }
                }else{
                    $coupon_goods[] = $v;
                }
            }
        }

        foreach($coupon_goods as $k =>$v){
            $allprice = $v['price']*$v['goods_num'];
            $all_total +=$allprice;
        }

        $coupon_price =  0;
        $coupon_id =  0;
        if($coupon){

            if($coupon['coupon_num'] <= 0){
                $this->error('優惠券被抢光了！');
            }

            if($coupon['coupon_term']){ //1有限期
                if(strtotime(date('Y-m-d H:i:s',strtotime('+1 day',strtotime($coupon['coupon_end_time'])))) < time()){
                    $this->error('優惠碼已失效！');
                }
                if(strtotime($coupon['coupon_start_time']) > time()){
                    $this->error('優惠時間還沒有開始！');
                }
            }
            if($coupon['coupon_num'] <= 0){
                $this->error('優惠券被抢光了！');
            }

            $level = array('1'=>'普通會員','2'=>'白金會員','3'=>'金牌會員','4'=>'商业會員');
            if(strstr($coupon['user_level'],strval($user['level'])) == false && $coupon['user_level']!=0){
                $this->error('優惠碼不適合'.$level[$user['level']].'使用！');
            }

            if($coupon['min_money'] > $all_total){
                $this->error('最低消費'.$coupon['min_money'].'，才能使用優惠碼！');
            }

            if($coupon['coupon_type'] == 1){ //1现金券 2折扣
                $coupon_price = $coupon['coupon_cash'];
            }else{
                $coupon_price = ($coupon['coupon_discount']/100)*($all_total-$score_price);
            }

            if(strval($coupon_price) >= strval($all_total)){
                $coupon_price = ($all_total);
            }

            $coupon_id = $coupon['coupon_id'];
            return array('coupon_price'=>$coupon_price,'coupon_name'=>$coupon['coupon_name']);
        }else{
            $this->error('優惠碼有誤！');
        }
    }

}
