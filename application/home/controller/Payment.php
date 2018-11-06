<?php
/**
 * Created by PhpStorm.
 * User: YXL
 * Date: 2018/8/25
 * Time: 19:17
 */

namespace app\home\controller;
use app\common\controller\Frontend;
use think\Db;
use app\common\library\Token;
use think\Session;
use think\Request;
use app\common\library\Email;
use app\api\controller\Eftpayment;

class Payment extends  Frontend
{

    public function go_pay(){

        if ($this->request->isPost()){
            $order_sn=base64_decode(input('order_sn'));
            $post= input('post.');

            if(empty($post['payment'])){
                $this->error('請選擇支付方式！');
            }

            $up = Db::name('order')->where(array('order_sn'=>$order_sn))->update(array('contribution_price'=>$post['contribution_price'],'payment'=>$post['payment']));
            //支付宝 || 万事达信用卡
            if($post['payment'] == 2 || $post['payment'] == 3 || $post['payment'] == 4){
                $this->success('跳转支付中...',url('payment/order_payment',array('order_sn'=>base64_encode($order_sn))));
                exit;
            }
//            //万事达信用卡
//            if($post['payment'] == 3 || $post['payment'] == 4){
//
//                $this->success('跳转支付中...',url('payment/order_payment',array('order_sn'=>base64_encode($order_sn),'pt'=>'2')));
//
//            }


            $res = $this->order_payment_success($order_sn,$post['payment'],$post['contribution_price']);
            if($res){
                $this->success('订单已成功支付！',url('payment/payment_done',array('order_sn'=>base64_encode($order_sn))));
            }else{
                $this->error('支付失败！');
            }
        }



        $order_sn=base64_decode(input('order_sn'));
        $order_info= Db::name('order')
        ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))
        ->find();

        if($order_info['pay_status']!=0 && $order_info['pay_status']!=1){
          $this->success('订单已成功支付！',url('user/center'));
        }
        $address_info= Db::name('user_address')
        ->where(array('user_id'=>Session::get('user_id'),'id'=>$order_info['address_id']))
        ->find();

        $all_total= Db::name('order')
            ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))
            ->sum('money_total');

        $this->assign('address_info',$address_info);
        $this->assign('all_total',$all_total);
        $this->assign('order_info',$order_info);
        $this->assign('order_sn',base64_encode($order_sn));
        $this->assign('title','支付订单');
       return $this->fetch();
    }


    /**
     * 支付接口中转站
     * 支付宝|mastercard|visa
     **/
    public function order_payment(){
        $order_sn= base64_decode(input('order_sn'));
        $order_info= Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->find();
        $this->assign('data',$data = '');
        $this->assign('order_info',$order_info);
        $this->assign('title','支付订单');

        // 支付宝
        if($order_info['payment'] == 2){
            $res = Db::name('payment')->where(array('order_sn'=>$order_sn,'payment_type'=>2))->find();
            if($res){
                $data = htmlspecialchars_decode($res['code']);
            }else{
                $trans_amount = '0.01';
                $EFTPayment = new \app\api\controller\Eftpayment;
                $data = $EFTPayment->transaction($order_sn,$trans_amount,'无','无');
                $signature = $EFTPayment->signature($order_sn,'ALIPAY',$trans_amount);
                if(empty($res)){
                    //保存第一次返回的html代码
                    Db::name('payment')->insert(array('order_sn'=>$order_sn,'payment_type'=>$order_info['payment'],'signature'=>$signature,'time'=>date('Y-m-d H:i:s'),'code'=>htmlspecialchars($data)));
                }
            }
            return $data;
        }

        // mastercard|visa
        if($order_info['payment'] == 3 || $order_info['payment'] == 4){
            $session = input('session');
            $mastercard_session = Session::get('mastercard_session');
            if(empty($mastercard_session)){
                $mastercard_session = $this->mastercard_session();
            }

            //回调查询订单是否支付
            if($session){
                $sessionVersion = input('sessionVersion');
                Db::name('payment')->insert(array('order_sn'=>$order_sn,'payment_type'=>$order_info['payment'],'time'=>date('Y-m-d H:i:s'),'sessionVersion'=>$sessionVersion,'session'=>$session));
                $res = $this->pageState($session);
                if($res){
                    if($res['order_sn'] == $order_sn && $res['amount'] == sum_order_payableprice($res['order_sn'])){
                        Session::set('mastercard_session',null);
                        $this->order_payment_success($order_sn,$order_info['payment']);
                        $this->redirect(url('payment/payment_done',array('order_sn'=>base64_encode($order_sn))));
                    }
                }
            }

            $return_url = url('payment/order_payment',array('order_sn'=>base64_encode($order_info['order_sn']),'session'=>$mastercard_session,'payment'=>$order_info['payment']),false);
            $this->assign('mastercard_session',$mastercard_session);
            $this->assign('order_info',$order_info);
            $this->assign('return_url',$return_url);
            $this->assign('session',input('session'));
            return $this->fetch();
        }


    }

    //ALIPAY支付宝接收通知
    public function alipay_return_url(){

        $data = input();
        $order_info= Db::name('order')
            ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$data['merch_ref_no']))
            ->find();
        if($order_info['pay_status'] != 0){
            $this->success('订单已成功支付！',url('user/center'));
        }

        $payment =  Db::name('payment')->where(array('order_sn'=>$data['merch_ref_no']))->find();
        $EFTPayment = new \app\api\controller\Eftpayment;

        $verify_sign = $EFTPayment->signature($data['merch_ref_no'],'ALIPAY',$data['trans_amount']);
        if($verify_sign == $payment['signature']){
            $verify_result = "Verify_SUCCESS";
            $info = Db::name('order')->where(array('order_sn'=>$data['merch_ref_no']))->update(
                array(
                    'trade_no'=>$data['trade_no'],
                )
            );
            $this->order_payment_success($data['merch_ref_no'],'2','0.00');
            $this->redirect(url('payment/payment_done',array('order_sn'=>base64_encode($data['merch_ref_no']))));
        }else{
            $this->error('支付出错了！');
        }
//        if($data['trans_status'] == 'TRADE_FINISHED' && !empty($data['merch_ref_no']) && !empty($data['trade_no'])){
//
//        }else{
//
//        }
    }


    //支付成功处理
    public function order_payment_success($order_sn,$payment,$contribution_price = '0.00'){
        $order_info = Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->find();

        if($order_info['pay_status']){
            $this->success('订单已经支付了！',url('payment/payment_done',array('order_sn'=>base64_encode($order_sn))));
        }
        if(empty($payment)){
            $this->error('請選擇支付方式！');
        }

        $data = array(
            'payment'=>$payment,
            'pay_time'=>date('Y-m-d H:i:s',time()),
            'pay_status'=>2,
        );

        if($payment == 5){
            $data['pay_status'] = 7;
        }
        $res = Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->update($data);
        if($res){
            $all_price = Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->sum('money_total');
            $all_price = $all_price-$order_info['integral_price']-$order_info['coupon_price'];
            $cofing_integral = config('site')['integral'];

            //支付成功处理
            /* 选中使用积分 */
            if(!empty($order_info['integral_price']) && $order_info['integral_price']!= '0.00'){
                $user_score= Db::name("user")->where(array('id'=>Session::get('user_id')))->value('score');
                $setdec_score= $cofing_integral['use']*$order_info['integral_price'];
                if($setdec_score > $user_score){
                    $setdec_score = $user_score;
                }

                $int = array(
                    'order_sn'=>$order_sn,
                    'user_id'=>Session::get('user_id'),
                    'integral'=>$setdec_score,
                    'type'=>'reduce',
                    'createtime'=>date('Y-m-d H:i:s',time()),
                );
                $res= Db::name("integral_log")->insertGetId($int);
                if($res!==false){
                    $res= Db::name("user")->where(array('id'=>Session::get('user_id')))->setDec('score',$setdec_score);
                }
            }

            /* 获得积分 */
            $int = array(
                'order_sn'=>$order_sn,
                'user_id'=>Session::get('user_id'),
                'integral'=>$all_price/$cofing_integral['obtain'],
                'type'=>'add',
                'createtime'=>date('Y-m-d H:i:s',time()),
            );
            if($int['integral'] <= 0){
                $int['integral'] = 0;
            }

            $res= Db::name("integral_log")->insertGetId($int);
            if($res!==false){
                $res= Db::name("user")->where(array('id'=>Session::get('user_id')))->setInc('score',$int['integral']);
            }
            /*会员升级*/
            sp_user_vipupgrade(Session::get('user_id'));

            $user= Db::name("user")->where(array('id'=>Session::get('user_id')))->find();
            $order_list = Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->select();
            $address_info = sp_address_info(Session::get('user_id'),$order_info['address_id'],'id,name,phone,phone_type');

            foreach ($order_list as $v){
                save_goods_stock($v['goods_id'],$v['goods_num']);
                $product_name [] = sp_product_info($v['goods_id'])['product_name'] .' '.$v['goods_num'].'×'.$v['price'];
            }


            /* 发送邮件 */
            $url = Request::instance()->domain().Request::instance()->root();
            $email = new Email;
            if($user['email']){
                $result = $email
                    ->to($user['email'])
                    ->subject(__("Order payment successfully"))
                    ->message('<div style="width:950px;margin:0 auto;background:#7ac141;border-radius:20px;padding:50px;padding-bottom:1px;text-align:center"><div style="background:#fff;font-size:20px;font-weight:400;padding:30px 90px;border-radius:20px;text-align:left"><img src="http://wsstest.teamotto.me/hksr/public/assets/img/logo400.png" style="width:200px"><br>親愛的'.$user['nickname'].',<br><br>感謝您的購買，您的預訂已確認！<br><br>您的訂單號是:<br>'.$order_info['order_sn'].'<br><br>預訂詳情<br>日期： '.week(date('w',strtotime($order_info['addtime']))).'，'.month(date('m',strtotime($order_info['addtime']))).date('Y',strtotime($order_info['addtime'])).'<br>時間： '.date('H:i',strtotime($order_info['addtime'])).'<br>購買： '.implode('，',$product_name).'<br>已付金額：$ '.sum_order_payableprice($order_info['order_sn']).'<br>您的訂單正在處理中，<br>我們將會把您的貨品派送到：<br><br>'.$address_info['name'].'<br>'.$user['email'].'<br>'.$address_info['phone_type'].' '.$address_info['phone'].' (手機）<br>'.$order_info['address'].'<br>更多關於訂單的詳細資訊和更新情況，請<a href="'.url('user/login','','',true).'">登入</a>您的帳戶查詢。<br><br>謝謝！<br>客戶服務中心<br>營康薈Live Smart<br><br><br>這是一封自動生成的電子郵件，請不要回覆。<br>如果您對您的帳戶有任何疑問，<br>請與我們聯絡dsc@wahhong.hk<br><p style="text-align:center;color:#7ac141;font-size:26px">香港復康會屬下社企"營康薈"支持殘疾人仕及長期病患者投入社會</p></div><p style="font-size:40px;color:#fff;font-weight:bold"><a style="color:#fff;text-decoration:none" href="'.$url.'">'.$url.'</a></p></div>')
                    ->send();
            }
                $return = array('code'=>1);
        }else{
            $return = array('code'=>0);

        }

        return $return;
    }

    public function payment_done(){
        $order_sn=base64_decode(input('order_sn'));
        $order_info = Db::name('order')->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn))->find();
        if(empty($order_info)){
            $this->error('出错了！',url('index/index'));
        }
        $integral= Db::name('integral_log')
            ->where(array('user_id'=>Session::get('user_id'),'order_sn'=>$order_sn,'type'=>'add'))
            ->value('integral');
        $this->assign('integral',$integral);
        $this->assign('order_sn',$order_sn);
        $this->assign('title','完成支付');
        return $this->fetch();
    }

    //获取mastercard session
    public function mastercard_session(){
        $url = 'https://ap-gateway.mastercard.com/form/version/48/merchant/TEST936666007/session';
        $data = array();
        $post = curl_post_https($url,$data);
        $data = json_decode($post,true);
        $session = $data['session']['id'];
        if($session){
            Session::set('mastercard_session',$session);
        }
        return $session;
    }


    //查看mastercard订单信息
    public function pageState($session){
        $url = 'https://ap-gateway.mastercard.com/checkout/api/pageState/'.$session;
        $data = array();
        $post = curl_post_https($url,$data);
        $data = json_decode($post,true);
        $order = [];
        if( $data['success'] == 'true'){
            $order['order_sn'] =$data['receiptNumber'];
            $order['amount'] = $data['displayData']['order']['amount'];
        }
        return $order;
    }


    public function resultIndicator(){
        $url = 'https://test-gateway.mastercard.com/api/rest/version/49';
        $data = array(
            'apiOperation'=>'CREATE_CHECKOUT_SESSION',
            'apiPassword'=>'Hkbea0919',
            'interaction.returnUrl'=>'http://hksr.com/public/api/payment/notifications',
            'apiUsername'=>'merchant.TEST936666007',
            'merchant'=>'TEST936666007',
            'order.currency'=>'HKD',
            'order.amount'=>'100.00',
            'order.id'=>'201810191539945822'
        );
        // $data = '';
        $post = curl_post_https($url,$data);
        dump($post);
    }







}