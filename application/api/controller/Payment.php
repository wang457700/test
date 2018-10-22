<?php
/**
 * Created by PhpStorm.
 * User: YXL
 * Date: 2018/8/25
 * Time: 19:17
 */

namespace app\api\controller;
use app\common\controller\Frontend;
use think\Db;
use app\common\library\Token;
use think\Session;
use app\common\library\Email;

class Payment extends  Frontend
{

    public function notifications(){

        $email = new Email;
        $result = $email
            ->to('572580083@qq.com')
            ->subject(__("Order payment successfully"))
            ->message(''.json_encode(input('get.'),true).'')
            ->send();
    }

}