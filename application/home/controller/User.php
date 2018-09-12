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
	protected $noNeedLogin = ['login', 'register', 'forgetpwd', 'is_forgetpwd_email', 'third'];

	//允许游客访问
	protected $noTouristAuthority = ['share_list','user_edit'];

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

	    	if(md5($password) !== $user['password']){
	    		$this->error('密码不正确，请重新输入！');
	    	}

            if($user['status'] == 'hidden'){
                $this->error('账号已冻结，请联系管理员！');
            }

	    	Session::set("user_id", $user['id']);
	    	Session::set("user", $user);
            $total=Db::name('cart_order')->where('user_id',$user['id'])->count();
            Session::set("total", $total);
	    	$this->success('登录成功！',url('user/center'));
	    	//dump($user);
		}


        $this->assign('title','會員登錄');
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
          $data['password']= md5(input('password'));
          $data['joinip']= $_SERVER['HTTP_X_FORWARDED_FOR'];
          $data['jointime']= time();
          $data['user_type']= 2;

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
     * 重置密码
     */
    function  forgetpwd(){
        if ($this->request->isPost()) {
            $data= input('post.');
            $res=Db::name('user')->where('email',$data['email'])->find();
            if(empty($res['email'])){
                $this->error('郵箱不存在！'.$res['email']);
            };

            $email = new Email;
            $encodeurl=url('User/is_forgetpwd_email',array('token'=>urlencode(base64_encode($res['id']))));
            $message= 'http://'.$_SERVER['SERVER_NAME'].urlencode($encodeurl);
            $url=self::getShort($message);
            $result = $email
                ->to($res['email'])
                ->subject(__("重置您的密码"))
                ->message('<div style="padding:30px"><div><p>您好，<b>'.$res['username'].'</b> ：</p></div><div style="margin:6px 0 60px 0"><p>请点击下面的链接来重置您的密码。</p><p><a href="'.$url.'" rel="noopener" target="_blank">'.$url.'</a></p><p>如果您的邮箱不支持链接点击，请将以上链接地址拷贝到你的浏览器地址栏中。</p></div><div style="color:#999"><p>发件时间：<span style="border-bottom:1px dashed #ccc;position:relative" t="5" times=" 14:30">'.date('Y-m-d H:i:s',time()).'</span></p><p>此邮件为系统自动发出的，请勿直接回复。</p></div></div>')
                ->send();
            if($result){
                $this->success('重置密碼郵件已經發送到<span class="color_ro">'.$res['email'].'，</span>請登入郵箱重置！');
            }else{
                $this->error('发送失败！');
            }
        }
        $this->assign('title','找回密码');
        return $this->view->fetch();
    }

    function  is_forgetpwd_email(){

        if ($this->request->isPost()){
            $user_id=base64_decode(input('token'));
            $user=Db::name('user')->where('id',$user_id)->find();
            $post= input('post.');
            if($post['password2'] != $post['password']){
                $this->error('二次輸入的密碼不一致');
            };

            if(md5($post['password']) == $user['password']){
                $this->error('新密码与旧密码相同！');
            };
            $data['password']=md5($post['password']);
            $res=Db::name('user')->where('id',$user_id)->update($data);
            if($res){
                Session::set('user_id',$res);
                $this->success('修改成功！',url('user/login'));
            }else{
                $this->error('修改失败！');
            }
        }

        $user_id=base64_decode(input('token'));
        $user=Db::name('user')->where('id',$user_id)->find();
        $this->assign('title','填写重置密码');
        $this->assign('user',$user);
        $this->assign('token',input('token'));
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
 	 	$res= Db::name('user')->where(array('id'=>$user_id))->update(array('is_eamil_status'=>1));
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
            $birthday_year= input('birthday_year');
            $birthday_month= input('birthday_month');
            $birthday_day= input('birthday_day');
            $data['birthday']=$birthday_year.'-'.$birthday_month.'-'.$birthday_day;
            $user = Db::name('user')->where('id', $user_id)->find();
            if($data['password']){
                if(md5($data['password']) != $user['password']){
                    $this->error('原密碼错误！');
                }
                if(empty($data['newpassword'])){
                    $this->error('請輸入新密碼！');
                }
                if(empty($data['confirmpassword'])){
                    $this->error('請輸入確認新密碼！');
                }
                if($data['newpassword'] != $data['confirmpassword']){
                    $this->error('二次輸入的密碼不一致！');
                }
                $data['password'] = md5($data['newpassword']);
            }else{
                $data['password'] = $user['password'];
            }
            unset($data['birthday_year']);
            unset($data['birthday_month']);
            unset($data['birthday_day']);
            unset($data['newpassword']);
            unset($data['confirmpassword']);
           $info = Db::name('user')->where(array('id'=>$user_id))->update($data);
            if($info!==false){
              $this->success('保存成功！');
            }else{
              $this->error('保存失败！');
            }
        }

        $user_id = Session::get('user_id');
        $info = Db::name('user')->where('id', $user_id)->find();
        $info['birthday'] = explode('-',$info['birthday']);
        $city = 0;
        $district = 0;
        $province = db('region')->where(array('parent_id'=>0,'level'=>1))->select();
        if($info['province']){
            $city = db('region')->where(array('parent_id'=>$info['province'],'level'=>2))->select();
        }
        if($info['city']){
            $district = db('region')->where(array('parent_id'=>$info['city'],'level'=>3))->select();
        }

        $y = [] ;
        $m = [] ;
        $d = [] ;
        for ($x=1949; $x<=date('Y',time()); $x++){$y[] = $x;}
        for ($x=1; $x<=12; $x++){$m[] = $x;}
        for ($x=1; $x<=31; $x++){$d[] = $x;}
        $this->assign('province',$province);
        $this->assign('city',$city);
        $this->assign('district',$district);
        $this->assign('info', $info);
        $this->assign('y', $y);
        $this->assign('m', $m);
        $this->assign('d', $d);
        $this->assign('title', '修改资料');
        return $this->view->fetch();
    }

    /*
     * @return string
     * 用户会员中心
     */


    public function center(){
        $user_id = Session::get('user_id');
        $style = input('style','center');
        $status = input('status','all');
        $where = [];
        $user = Db::name('user')->where('id', $user_id)->find();

        if($status != 'all'){
            $where['pay_status'] = $status;
        }
        $where['a.user_id'] = $user_id;

        $order_list = Db::name('order')
            ->alias('a')
            ->where($where)
            ->field('a.*,e.name,e.phone')
            ->join('__USER_ADDRESS__ e', 'a.address_id=e.id', 'LEFT')
            ->group('a.order_sn')
            ->order('addtime desc')
            ->paginate(2);

        $result = $order_list->all();
        foreach ($result as $key=>$item){
            $result[$key]['goods_list'] = Db::name('order')
                ->alias('a')
                ->field('a.*,c.product_name,c.freight_num,c.cover')
                ->join('__GOODS__ c','a.goods_id=c.product_id','LEFT')
                ->where('order_sn',$item['order_sn'])
                ->order('addtime desc')->select();
        }

        $level = array('1'=>'普通会员','2'=>'白金会员','3'=>'金牌会员','4'=>'商业会员');
        $level_text  =$level[$user ['level']];
        $sum_contribution_price = array_sum(Db::name('order')->alias('a')
            ->where('user_id',$user_id)
            ->group('a.order_sn')
            ->column('contribution_price'));

        $goods_list= Db::name('order')->alias('a')->join('__GOODS__ c','a.goods_id=c.product_id','LEFT')->where('user_id',Session::get('user_id'))->order('addtime desc')->paginate(4);

        $pay_status = array('0'=>'未支付','2'=>'已支付','6'=>'已取消');
        $payment = array('0'=>'未支付','2'=>'已支付','6'=>'已取消');

        //手机端ajax列表
        if($this->request->isPost()){
            $ajax = [];
            foreach ($result as $k => $item){
                $html = null;
                if($item['pay_status']  != 0){
                    $html = '<a href="'.url('user/order_detail',array('order_sn'=>base64_encode($item['order_sn']))).'"  class="mui-btn mui-btn-warning mui-btn-outlined">查看訂單</a>';
                }
                if($item['pay_status']  == 0){
                    $html = '<a href="'.url('payment/go_pay',array('order_sn'=>base64_encode($item['order_sn']))).'"  class="mui-btn mui-btn-success mui-btn-outlined">去支付</a> <a  href="'.url('user/order_cancel',array('order_sn'=>base64_encode($item['order_sn']))).'" class="mui-btn mui-btn-warning mui-btn-outlined">取消訂單</a>';
                }
                $ajax[$k]['freight'] = $item['freight'];
                $ajax[$k]['price'] = sum_order_payableprice($item['order_sn']);
                $ajax[$k]['statustext']  = $pay_status[$item['pay_status']];
                $ajax[$k]['payment']  = sp_payment($item['payment']);
                $ajax[$k]['button'] = $html;
                foreach ($item['goods_list'] as $kk => $vv){
                    $ajax[$k]['goods_list'][$kk]['product_name'] = $vv['product_name'];
                    $ajax[$k]['goods_list'][$kk]['goods_num'] = $vv['goods_num'];
                    $ajax[$k]['goods_list'][$kk]['money_total'] = $vv['money_total'];
                    $ajax[$k]['goods_list'][$kk]['cover'] = $vv['cover'];
                }
            }
            $this->success('获取成功',url('user/share_success'),$ajax);
        }

        $page = $order_list->render();
        $this->assign('page',$page);
        $this->assign('order_list',$result);
        $this->assign('level_text',$level_text);
        $this->assign('pay_status',$pay_status);
        $this->assign('lastPage',$lastPage=6);
        $this->assign('payment',$payment);
        $this->assign('contribution_price',$sum_contribution_price);
        $this->assign('config_use',config('site')['integral']['use']);
          $this->assign('goods_list',$goods_list);
        $this->assign('style',$style);

        $this->assign('status',$status);//手机筛选状态
        if($style == 'order'){
            $this->assign('title','我的訂單');
        }else{
            $this->assign('title','用戶中心');
        }
        return $this->view->fetch();
    }



/******************************       user_contribution_list  用户捐款记录   ************************************/

    public function user_contribution_list(){
        $user_id = Session::get('user_id');
        $where['user_id'] = $user_id;
        $where['contribution_price'] = array('neq',0);
        $where['pay_status'] = array('eq',2);
        $contribution_list= Db::name('order')
            ->alias('a')
            ->where($where)
            ->order('pay_time desc')
            ->group('a.order_sn')
            // ->select();
           ->paginate(10);

        $data = $contribution_list->all();
        foreach ($data as $k =>  $v)
        {
           // $v['pay_time'] = date('Y-m-d',$v['pay_time']);
            $contribution_list[$k] = $v;
        }
        unset($v);
        $this->assign('contribution_list',$contribution_list);

        $this->assign('title','我的共享');
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
         	$data['product_category'] = '0-'.implode('-',array_filter($post['cid']));
         	$data['product_pic'] = $pic_url;
         	$data['add_date'] = time();
         	$data['user_id'] = $user_id;

			$info= Db::name('user_share')->insertGetId($data);
			if($info!==false){
				$this->success('发布成功',url('user/share_success',array('id'=>$info)));
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
        $info['product_category'] = explode('-',$info['product_category']);
        $this->assign('info',$info);
        $this->assign('title','我的共享');
        return $this->view->fetch('user/share/edit');
    }


    public function share_success(){
 		$id = input('id');
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
		$where['status'] = 1;
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
            $v['province'] = Db::name('region')->where(array('id'=>$v['province']))->value('name');
            $v['city'] =Db::name('region')->where(array('id'=>$v['city']))->value('name');
            $v['district'] =Db::name('region')->where(array('id'=>$v['district']))->value('name');
            $address_list[$k] = $v;
        }

        $city = 0;
        $district = 0;
        $province = db('region')->where(array('parent_id'=>0,'level'=>1))->select();

        $id = input('id');
        if($id){
        	$row= Db::name('user_address')->where(array('id'=>$id,'user_id'=>$user_id))->find();
            if($row['province']){
                $city = db('region')->where(array('parent_id'=>$row['province'],'level'=>2))->select();
            }
            if($row['city']){
                $district = db('region')->where(array('parent_id'=>$row['city'],'level'=>3))->select();
            }
        }else{
        	$row = array('province'=>null,'city'=>null,'district'=>null,'address'=>null,'name'=>null,'phone'=>null);
        }

		$this->assign('address_list',$address_list);
		$this->assign('row',$row);
        $this->assign('province',$province);
        $this->assign('city',$city);
        $this->assign('district',$district);
        $this->assign('title','我的地址');
        return $this->view->fetch();
    }


    public function address_add(){
    	if ($this->request->isPost()){
    		$user_id = Session::get('user_id');
            $data['default'] = 0;
            $data = input('post.');
            $info = Db::name('user_address')->where(array('user_id'=>$user_id,'default'=>1))->find();
            if(empty($info)){
                $data['default'] = 1;
            }
    		$data['createtime'] = time();
    		$data['user_id'] = $user_id;
    		if($data['default']){
    			Db::name('user_address')->where(array('user_id'=>$user_id))->update(array('default'=>0));
    		}

			$res = Db::name('user_address')->insertGetId($data);
			if($res!==false){
				$this->success('保存成功','',array('address_info'=>sp_address_info($user_id,$res,'name,address,phone')));
			}else{
				$this->error('保存失败');
			}
    	}
    }


    public function address_edit(){

        $user_id = Session::get('user_id');
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
	 		$info = Db::name('user_address')->where($where)->update(array('status'=>0));
 			if($info){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}
    	}
    }

    /******************************       my_order 我的订单  ************************************/

    public function order_detail(){
        $user_id = Session::get('user_id');
        $order_sn=base64_decode(input('order_sn'));
        if(empty($order_sn)){
            $this->error('非法操作！');
        }
        $order_info= Db::name('order')
            ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))
            ->find();
        $order_info['address_info']= Db::name('user_address')
            ->field('name,phone')
            ->where(array('user_id'=>Session::get('user_id'),'id'=>$order_info['address_id']))
            ->find();
        $order_info['integral'] =  Db::name('integral_log')->where(array('order_sn'=>$order_sn,'type'=>'reduce'))->value('integral');


        $goods_list= Db::name('order')
            ->alias('a')
            ->field('a.*,c.cover,product_name,freight_num')
            ->join('__GOODS__ c','a.goods_id=c.product_id','LEFT')
            ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))
            ->select();
        $all_goods_num =0;
        $all_money_total =0;
        foreach ($goods_list as $k => $v){
                $all_money_total += $v['money_total'];
                $all_goods_num += $v['goods_num'];
        }

        $this->assign('order_info',$order_info);
        $this->assign('all_money_total',$all_money_total);
        $this->assign('all_goods_num',$all_goods_num);
        $this->assign('goods_list',$goods_list);
        $this->assign('title','訂單詳情');
        return $this->view->fetch();
    }

    //取消订单
    public  function order_cancel(){
            $order_sn=base64_decode(input('order_sn'));
            $user_id = Session::get('user_id');
            $where = array('order_sn'=>$order_sn,'user_id'=>$user_id);
            $res = Db::name('order')->where($where)->update(array('pay_status'=>6));
            if($res){
                $this->success('取消成功');
            }else{
                $this->error('取消失败');
            }
    }




    /******************************       其他函数  ************************************/
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
