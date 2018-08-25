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

class Payment extends  Frontend
{

    public function go_pay(){




        $this->assign('title','支付订单');
       return $this->fetch();
    }

    public function Payment_done(){


        $this->assign('title','完成支付');
        return $this->fetch();
    }

}