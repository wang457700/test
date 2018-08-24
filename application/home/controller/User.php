<?php

namespace app\home\controller;

use app\common\controller\Frontend;
use think\Config;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Hook;
use think\Session;
use think\Validate;
use app\common\library\Email;
use app\home\common\common;

/**
 * 会员中心
 */
class User extends Frontend
{
	protected $noNeedLogin = ['login', 'register', 'third'];

    const APPKEY='4119214287';
    const URL ="www.baidu.com";

     public function _initialize()
    {	
    	parent::_initialize();

    	$user_id = Session::get('user_id');
    	if($user_id){
			$user = Db::name('user')->where(array('id'=>$user_id))->find();
 			$this->assign('user',$user);
 			$this->assign('user_id',$user_id);

    	};
    }


    public function Login(){
    	if ($this->request->isPost()) {
	    	$password = input('post.password');
	    	$email = input('post.email');
	    	$user=Db::name('user')->where(array('email'=>$email))->find();
	    	if(empty($user)){
				$this->error('账号不存在！');
	    	}
	    	if($user['is_eamil_status'] == 0){
	    		$this->error('账号还没有激活，请到电邮激活');
	    	}

	    	if($password !== $user['password']){
	    		$this->error('密码不正确，请重新输入！');
	    	}

	    	Session::set("user_id", $user['id']);
	    	Session::set("user", $user);
	    	$this->success('登录成功！',url('user/center'));
	    	//dump($user);
		}


        $this->assign('title','会员登录');
        return $this->view->fetch();
    }


    public function logout(){
        Session::set("user_id",'');
        $this->success('退出成功！',url('user/login'));
    }

	/*
		注册

 	*/
    public function register(){
      if ($this->request->isPost()) {
         $data= input('post.');
          if(empty($data['username'])){
              $this->error('請填寫用戶名',url('user/register'));
          };
          if($data['password2'] != $data['password']){
              $this->error('二次輸入的密碼不一致',url('user/register'),2);
          };
          unset($data['password2']);
          $res=Db::name('user')->insertGetId($data);
          if($res){
              Session::set('user_id',$res);
              $this->success('注册成功！',url('user/user_email_activation'));
          }else{
              $this->error('注册失败！',url('user/register'));
          }
      }
        $this->assign('title','会员注册');
        return $this->view->fetch();
    }

	/**
     * 邮箱激活
     */
    public function user_email_activation(){
        $user_id= Session::get('user_id');
        $list=Db::name('user')->where(array('id'=>$user_id))->find();

        if(input('email')==1){
            $email = new Email;
            $encodeurl=url('User/is_email',array('token'=>base64_encode($user_id)));
            $message= 'http://'.$_SERVER['SERVER_NAME'].urlencode($encodeurl);

            $url=self::getShort($message);
            $result = $email
                ->to($list['email'])
                ->subject(__("请激活您的邮箱"))
                ->message(''.$url.'')
                ->send();
            if($result){
                $this->success('发送成功！');
            }else{
                $this->error('发送失败！');
            }
        }
       $this->assign('list',$list);
        return $this->view->fetch();
    }

    /***
     * 邮箱激活
     */
    public function is_email(){
    	$user_id=base64_decode(input('token'));
        $res= Db::name('user')->where(array('id'=>$user_id))->value('is_eamil_status');
        if($res){
        	 $this->success('账号已经激活，去登陆！',url('user/login'));
        }
 	 	$res= Db::name('user')->where(array('id'=>$user_id))->find(array('is_eamil_status'=>1));
 		if($res){
          $this->success('激活成功！',url('user/login'));
	  	}else{
          $this->error('激活失败！');
 		}
    }

    /***
     * 用户编辑页面
     * author：MR:daly
     * addting:2018/8/13
     */

    public function user_edit(){

        if ($this->request->isPost()){
            $data = input('post.');

            $user_id = Session::get('user_id');
           $info = Db::name('user')->where(array('id'=>$user_id))->update($data);
            if($info!==false){
                $this->success('修改成功！');
            }else{
                $this->error('修改失败！'.json_encode($data,true));
            }
        }

        $user_id = Session::get('user_id');
        $info = Db::name('user')->where('id', $user_id)->find();
        $this->assign('info', $info);
        $this->assign('title', '修改资料');
        return $this->view->fetch();
    }

    /*
     * @return string
     * 用户会员中心
     */
  

    public function center(){ 
        $this->assign('title','用户中心');
        return $this->view->fetch();
    }



    /**
     * @return string
     * 订单详情
     */
    public function user_order_detail(){ 
        return $this->view->fetch();
    }


/******************************       share_list  产品共享        ************************************/


    public function share_list(){ 
		$user_id = Session::get('user_id');
		$where['user_id'] = $user_id;
		$where['status'] = array('neq',3);
		$share_list= Db::name('user_share')
		->where($where)
		->order('add_date desc')
		->paginate(10);


		$status = array('0'=>'審核中','1'=>'展示中','2'=>'已下架');
		$data = $share_list->all();
		foreach ($data as $k =>  $v)
        {
            $v['status_text'] = $status[$v['status']];
            $v['add_date'] = date('Y-m-d',$v['add_date']);
            $share_list[$k] = $v;
        }
        unset($v);
		$this->assign('share_list',$share_list); 

        $this->assign('title','我的共享');
        return $this->view->fetch('user/share/share_list');
    }

	/**
     * 
     * 发布添加共享产品
     */
    public function share_add(){ 
        $user_id = Session::get('user_id');


		if ($this->request->isPost()) {

			$post = input('post.');
			if(empty($post['customized-buttonpane'])){
				$this->error('请输入简介！');
			}
			$imgstr = $post['pic'];
			$imgdata = substr($imgstr,strpos($imgstr,",") + 1);
			$decodedData = base64_decode($imgdata);
			$pic_url = 'uploads/usershare/img_'.time().'.jpg';
			file_put_contents($pic_url,$decodedData);

			$data['product_content'] =htmlspecialchars_decode($post['customized-buttonpane']);
         	$data['product_name'] = $post['title'];
         	$data['product_category'] = '0-'.implode('-',$post['cid']);
         	$data['product_pic'] = $pic_url;
         	$data['add_date'] = time();
         	$data['user_id'] = $user_id;

			$info= Db::name('user_share')->insert($data);
			if($info!==false){
				$this->success('发布成功',url('user/share_success'));
			}else{
				$this->error('发布失败');
			}
     	
    	}
 
     	$this->assign('title','我的共享');
        return $this->view->fetch('user/share/add');
    }


    /**
     *
     * 编辑共享产品
     */
    public function share_edit(){
        $user_id = Session::get('user_id');

        if ($this->request->isPost()) {

            $post = input('post.');
            if(empty($post['customized-buttonpane'])){
                $this->error('请输入简介！');
            }

            if(!empty($post['pic'])){
                $imgstr = $post['pic'];
                $imgdata = substr($imgstr,strpos($imgstr,",") + 1);
                $decodedData = base64_decode($imgdata);
                $pic_url = 'uploads/usershare/img_'.time().'.jpg';
                file_put_contents($pic_url,$decodedData);
                $data['product_pic'] = $pic_url;
            }

            $data['product_content'] =htmlspecialchars_decode($post['customized-buttonpane']);
            $data['product_name'] = $post['title'];
            $data['product_category'] = '0-'.implode('-',$post['cid']);
            $data['add_date'] = time();
            $data['user_id'] = $user_id;

            $info= Db::name('user_share')->where(array('id'=>$post['id'],'user_id'=>$user_id))->update($data);
            if($info!==false){
                $this->success('编辑成功',url('user/share_list'));
            }else{
                $this->error('编辑失败');
            }
        }

        $id = $this->request->param('id', 0, 'intval');
        $info= Db::name('user_share')->where(array('id'=>$id,'user_id'=>$user_id))->find();
        $this->assign('info',$info);
        $this->assign('title','我的共享');
        return $this->view->fetch('user/share/edit');
    }


    public function share_success(){
 		$id = input('get.id');
 		$info = Db::name('user_share')->where('id',$id)->find();
        $this->assign('title','完成發佈');
        $this->assign('info',$info);
        return $this->view->fetch('user/share/share_success');
    }

    /*
     * 发布共享 - 删除
     */
    public function share_delete(){
    	if ($this->request->isGet()){
	 		$id = $this->request->param('id', 0, 'intval');
	 		$user_id = Session::get('user_id');
	 		$where = array('id'=>$id,'user_id'=>$user_id);
	 		$info = Db::name('user_share')->where($where)->update(array('status' => 3));
 			if($info!==false){
				$this->success('删除成功'.$info.$user_id.$id);
			}else{
				$this->error('删除失败');
			}
    	}else{
			$this->error('非法操作！');
    	}
    }

    /*
     * 发布共享 - 上下架
     */
    public function share_operation(){
    	if ($this->request->isGet()){
	 		$id = $this->request->param('id', 0, 'intval');
	 		$type = $this->request->param('type');
	 		$user_id = Session::get('user_id');
	 		$where = array('id'=>$id,'user_id'=>$user_id);

 			/* type : 2.下架 1.上架*/
	 		if($type == 'lower'){
 				$status = 2;
 				$tips = '下架';
	 		}
	 		if($type == 'on'){
 				$status = 1;
 				$tips = '上架';
	 		}

	 		$info = Db::name('user_share')->where($where)->update(array('status' => $status));
 			if($info!==false){
				$this->success($tips.'成功');
			}else{
				$this->error($tips.'失败');
			}
    	}else{
			$this->error('非法操作！');
    	}
    }



/******************************       address_list 用户地址管理 ************************************/

	public function address_list(){ 
		$user_id = Session::get('user_id'); 
		$where['user_id'] = $user_id; 
		$address_list= Db::name('user_address')
		->where($where)
		->order('createtime desc')
		->paginate(10);


		$default = array('0'=>'','1'=>'默認地址');
		$data = $address_list->all();
		foreach ($data as $k =>  $v)
        {
            $v['default_text'] = $default[$v['default']];
            $v['createtime'] = date('Y-m-d',$v['createtime']);
            $address_list[$k] = $v;
        }
    
        $id = input('id');
        if($id){
        	$row= Db::name('user_address')->where(array('id'=>$id,'user_id'=>$user_id))->find();
        }else{
        	$row = array('address'=>null,'name'=>null,'phone'=>null);
        }
		$this->assign('address_list',$address_list);

		$this->assign('row',$row);
        $this->assign('title','我的地址');
        return $this->view->fetch();
    }


    public function address_add(){
    	if ($this->request->isPost()){ 
    		$user_id = Session::get('user_id');
    		$post = input('post.');
    		$data = $post;
    		$data['default'] = $post['default']?'1':'0';
    		$data['createtime'] = time();
    		$data['user_id'] = $user_id;

    		if($data['default']){
    			Db::name('user_address')->where(array('user_id'=>$user_id))->update(array('default'=>0));
    		}
			$info = Db::name('user_address')->insert($data); 
			if($info!==false){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
    	}
    }
 

    public function address_edit(){
    	if ($this->request->isPost()){
    		$user_id = Session::get('user_id');
    		$data = input('post.');
    		if($data['default'] == 'on'){
				$data['default'] = 1;
    		}
    		if($data['default']){
    			Db::name('user_address')->where(array('user_id'=>$user_id))->update(array('default'=>0));
    		}
			$info = Db::name('user_address')->where(array('id'=>$data['id'] ,'user_id'=>$user_id))->update($data);
			if($info!==false){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
    	}
    }

    public function address_delete(){
    	if ($this->request->isGet()){
	 		$id = $this->request->param('id', 0, 'intval');
	 		if(empty($id)){
	 			$this->error('非法操作！');
	 		}
	 		$user_id = Session::get('user_id');
	 		$where = array('id'=>$id,'user_id'=>$user_id);
	 		$info = Db::name('user_address')->where($where)->delete();
 			if($info){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}
    	}
    }

    /******************************       my_order 我的订单  ************************************/

    public function my_order(){

        $this->assign('title','我的订单');
        return $this->view->fetch();
    }



    //CURL
    private static function CURLQueryString($url){
        //设置附加HTTP头
        $addHead=array("Content-type: application/json");
        //初始化curl
        $curl_obj=curl_init();
        //设置网址
        curl_setopt($curl_obj,CURLOPT_URL,$url);
        //附加Head内容
        curl_setopt($curl_obj,CURLOPT_HTTPHEADER,$addHead);
        //是否输出返回头信息
        curl_setopt($curl_obj,CURLOPT_HEADER,0);
        //将curl_exec的结果返回
        curl_setopt($curl_obj,CURLOPT_RETURNTRANSFER,1);
        //设置超时时间
        curl_setopt($curl_obj,CURLOPT_TIMEOUT,8);
        //执行
        $result=curl_exec($curl_obj);
        //关闭curl回话
        curl_close($curl_obj);
        return $result;
    }
    //处理返回结果
    private static function doWithResult($result,$field){
        $result=json_decode($result,true);
        return isset($result[0][$field])?$result[0][$field]:'';
    }
    //获取短链接
    public static function getShort($url){
        $url='http://api.t.sina.com.cn/short_url/shorten.json?source='.self::APPKEY.'&url_long='.$url;
        $result=self::CURLQueryString($url);
        return self::doWithResult($result,'url_short');
    }
    //获取长链接
    public static function getLong($url){
        $url='http://api.t.sina.com.cn/short_url/expand.json?source='.self::APPKEY.'&url_short='.$url;
        $result=self::CURLQueryString($url);
        return self::doWithResult($result,'url_long');
    }


}
