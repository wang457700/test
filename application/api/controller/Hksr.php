<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Db;
use think\Session;

class Hksr extends Api
{

    protected $noNeedLogin = ['system_login','system_logout','index','Member_GetInfo','Product_GetList','Product_GetFullStockListByBC','Product_GetInfo','Member_GetList','member_user_add','test','Member_ModifyBPT'];

    public function system_login()
    {
        $url='http://103.254.210.215//hksrapisttest/api.asmx/System_Login';
        $post_data=array(
            'sUsername'=>101,
            'sPassword'=>101,
        );
        $cookies = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookies);
        $objectxml = simplexml_load_string($res);
        $cookie=json_decode($objectxml,true);
        return $cookie;
    }


    public function System_Polling()
    {
        $url='http://103.254.210.215//hksrapisttest/api.asmx/System_Polling';
        $post_data=array(
            'sUsername'=>101,
            'sPassword'=>101,
        );
        $cookies = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookies);
        print_r($res);die;
    }


    public function system_logout()
    {
        $url='http://103.254.210.215//hksrapisttest/api.asmx/System_Logout';
        $post_data=array(
            'sUsername'=>101,
            'sPassword'=>100,
        );
        $cookies = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookies);
        print_r($res);die;
    }



    /**
     * 获取当前商店ID
     **/
    public function shop_getid(){
        $post_data=array();
        $url="http://103.254.210.215/hksrapisttest/api.asmx/Shop_GetID";
        $cookie = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookie);
        $objectxml = simplexml_load_string($res);
        $shopid=json_decode($objectxml,true);
        return 1;
    }

    /***
     * 获取商品列表
     */

    public function Product_GetList(){
        $url='http://103.254.210.215/hksrapisttest/api.asmx/Product_GetList';
        $post_data=array(
            'iC1'=>-1,
            'iC1S1'=>-1,
            'iC2'=>-1,
            'iC3'=>-1,
            'sOrderBy'=>'BC',
            'bAscend'=>'true',
            'iRecFrom'=>0,
            'iRecTo'=>20
        );

        $cookies = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookies);
        $objectxml = simplexml_load_string($res);
        $xmljson= json_encode($objectxml );//将对象转换个JSON
        $xmlarray=json_decode($xmljson,true);
        $new_arr= implode(" ",$xmlarray);
        $new_array= json_decode($new_arr,true);
        dump($new_array);

        //print_r($xmlarray);die;
    }


    /***
     * 通过邮箱获取id
     */

    public function Member_GetIDByEMail(){

        $url='http://103.254.210.215//hksrapisttest/api.asmx/Member_GetIDByEmail';
        $post_data=array(
            'sEmail'=>'457700516@qq.com'
        );
        $cookies = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookies);
        print_r($res);die;
    }



    /**
     * 创建訂單
     */
    public function Task_CreateSalesOrder(){

        $url='http://103.254.210.215//hksrapisttest/api.asmx/Task_CreateSalesOrder';
        $Options = array(
            'SN'=>1,  //Shop No. of online shop
            'BD'=>date('Y/m/d'),  //Bill Date
            'SC'=>'0001447',  //user_id
            'GA'=>'0.01',  //Gross Amount
            'DP'=>'',
            'AA'=>'',
            'DDA'=>'',
            'NA'=>'',
            'Delivery'=>array(
                'DVD'=>date('Y/m/d H:i:s'),
                'LOC'=>'-1',
                'AD1'=>'送货地址测试', //Address Line1
                'AD2'=>'送货地址测试',
                'AD3'=>'送货地址测试',
                'TEL1'=>'13192963964', //Telephone1
                'TEL2'=>'',
                'RE'=>'下单测试', //Remark1
                'RE2'=>'',
                'RE3'=>'',
                'RE4'=>'',
                'Item'=>array(
                    array(
                        'BC'=>'3760015411501', //Barcode
                        'UP'=>'0.01', //Unit Price
                        'QU'=>'1', //Quantity
                        'SEP'=>'43.00', //Selling Price
                        'VDA'=>'1', //Selling Price
                        'DP'=>'0', //Item Discount
                        'REM'=>'0',
                    ),
                ),
            ),
            'Payment'=>array(
                'PM'=>'0',//Payment Method Code 付款方法代码
                'PA'=>'200.00',//Payment Amount in Local Currency 本币支付金额
                'RF'=>'',
            ),
            'PointAdj'=>array(
                'BPT'=>'1',
                'ADJ'=>'1',
            ),
        );

        $post_data=array(
            'jSalesOrder'=>json_encode($Options,true),
            'Options'=>json_encode($Options,true)
        );

        print_r($post_data);
        $cookies = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookies);

        dump($res);
        print_r($res);die;
    }


    /***
     *   查询库存商品信息
     *  $sBC = 商品条码
     * return
     * SN：Shop number
     * PNxL：Product name according to login user langID
     * BC：Barcode (unique for product)
     * UBC：Product code (not unique)
     * UNxL：Unit
     * ST：Stock level
     * DLU
    */
 public function Product_GetFullStockListByBC($sBC = ''){

     $shop_getid = Session::get('shop_getid');
     $url='http://103.254.210.215//hksrapisttest/api.asmx/Product_GetFullStockListByBC';
     $post_data=array(
         'sBC'=>$sBC  //填写商品码
     );
     $cookies = dirname(__FILE__).'/cookie.txt';
     $res=$this->curl_post($url,$post_data,$cookies);
     $arr="SN|PNxL|BC|UBC|UNxL|ST|DLU";
     $xmlarray = $this->xml_array_list($res,$arr);
     return $xmlarray[$shop_getid]['ST'];
 }



 public  function  Shop_GetList_J(){

     $url='http://103.254.210.215//hksrapisttest/api.asmx/Shop_GetList_J';
     $post_data=array();
     $cookies = dirname(__FILE__).'/cookie.txt';
     $res=$this->curl_post($url,$post_data,$cookies);

dump($res);

 }
    /***
     *   查询商品信息
     */
    public function Product_GetInfo(){

        $url='http://103.254.210.215//hksrapisttest/api.asmx/Product_GetInfo';
        $post_data=array(
            'sBC'=>'4711931009630'  //填写商品码
        );
        $cookies = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookies);

        $objectxml = simplexml_load_string($res);
        $xmljson= json_encode($objectxml);//将对象转换个JSON
        $xmlarray=json_decode($xmljson,true);
        $new_arr= implode(" ",$xmlarray);
        $new_array  =explode('|',$new_arr);
        $arr="SN|BC|UBC|PN1L|PN2L|SN1L|SN2L|LN1L|LN2L|RE|LOC|C1|C1S1|C2|C3|COLR|SIZ|STS1|STS2|UN|FP|SEP|SEP2|SEP3|SEP4|SEP5|SU|SREF|INSEAM|LP|PD1|PD2|PD3|PP|IO|DM|MDP";
        $new_field  =explode('|',$arr);
        $res_r=array();
        foreach ($new_field as $key=>$item){
            foreach ($new_array as $k=>$y){
                $res_r[$new_field[$k]]=$y;
            }
        }

        dump($res_r);
        print_r($res_r);die;
    }

    /***
     * 添加会员信息
     */
    public function member_user_add($data){
        $cookie = dirname(__FILE__).'/cookie.txt';
        $url="http://103.254.210.215/hksrapisttest/api.asmx/Member_AddNew";
        $post_data=array(
            'sID'=>'',
            'sCAID'=>'',
            'sSID'=>'',
            'sN1L'=>$data['nickname'],
            'sN2L'=>$data['username'],
            'sTL1'=>'',
            'sTL2'=>'',
            'sEM'=>$data['email'],   //邮箱
            'sAD1L1'=>'',
            'sAD1L2'=>'',
            'sAD1L3'=>'',
            'vSDB'=>'1900-01-01',
            'vCAXP'=>'1900-01-01',
            'iSGR'=>-1,
            'sSSE'=>$data['gender'],  //Gender: M/F/NA (‘NA’ for not provided)
            'bEx'=>'true',
            'dCBAL'=>0,
            'dHBAL'=>0,
            'dBPT1'=>0,
            'dBPT2'=>0,
            'dBPT3'=>0,
            'dBPT4'=>0
        );

        $res=$this->curl_post($url,$post_data,$cookie); //成功会返回用户id
        $objectxml = simplexml_load_string($res);
        $xmljson= json_encode($objectxml);//将对象转换个JSON
        $xmlarray=json_decode($xmljson,true);
        return str_replace('"','',$xmlarray[0]);
    }


    /***
     * 修改会员的等级和奖金点
     */
    public function Member_ModifyBPT(){
        $cookie = dirname(__FILE__).'/cookie.txt';
        $url="http://103.254.210.215/hksrapisttest/api.asmx/Member_ModifyBPT";
        $post_data=array(
            'sID'=>'0001420',
            'iSGR'=>'0',
            'dCBAL'=>'0',
            'dHBAL'=>'0',
            'dBPT1'=>'2',
            'dBPT2'=>'0',
            'dBPT3'=>'0',
            'dBPT4'=>'0',
        );
      $res=$this->curl_post($url,$post_data,$cookie); //成功会返回用户id
      print_r($res);
    }


    /***
     * 修改会员信息
     */
    public function Member_Modify(){

        $url="http://103.254.210.215/hksrapisttest/api.asmx/Member_Modify";
        $cookie = dirname(__FILE__).'/cookie.txt';
        $post_data=array(
            'sID'=>'0001411',
            'sCAID'=>'',
            'sSID'=>'',
            'sN1L'=>'李我',
            'sN2L'=>'dalyss',
            'sTL1'=>'',
            'sTL2'=>'',
            'sEM'=>'457700516@qq.com',
            'sAD1L1'=>'',
            'sAD1L2'=>'',
            'sAD1L3'=>'',
            'vSDB'=>'1995-10-13',
            'vCAXP'=>'2050-10-13',
            'iSGR'=>-1,
            'sSSE'=>'M',
            'bEx'=>'true',
            'dCBAL'=>0,
            'dHBAL'=>0,
            'dBPT1'=>0,
            'dBPT2'=>0,
            'dBPT3'=>0,
            'dBPT4'=>0
        );
        $res=$this->curl_post($url,$post_data,$cookie);
        print_r($res);

    }

    /**
     * 获取会员信息
     */
    public function Member_GetInfo(){

        $url="http://103.254.210.215/hksrapisttest/api.asmx/Member_GetInfo";
        $post_data=array(
            'sID'=>'0001447',
        );
        $cookie = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookie);
        $arr="ID|SID|CAID|N1L|N2L|NN|LN1L|LN2L|SGR|CBAL|HBAL|BPT1|BPT2|BPT3|BPT4|SSE|TL1|TL2|AD1L1|AD1L2|AD1L3|AD2L1|AD2L2|AD2L3|EM|DC|SDB|DJ|CAXP|DL|MC|OC|RC1|RC2|RE1L|RE2L|REFID";
        $data = $this->xml_array_find($res,$arr);
        dump($data);
    }

    /**
     * 获取会员列表
     */
    public function Member_GetList(){
        $url="http://103.254.210.215/hksrapisttest/api.asmx/Member_GetList";

        $post_data=array(
            'sOrderBy'=>'SID',
            'bAscend'=>'false',
            'iRecFrom'=>'0',
            'iRecTo'=>'100'
        );
        $cookie = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookie);

        $arr="ID|SID|CAID|NxL|SGR|BPTx|SSE|TL1|TL2|TL2";
       $xmlarray = $this->xml_array_list($res,$arr);

        dump($xmlarray);die;
    }




    /**
     * test
     */
    public function test(){
        $url="http://103.254.210.215/hksrapisttest/api.asmx/Morder_AddItems";


        $cookie = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookie);
        dump($res);exit;
        $arr="SN|PNxL|BC|UBC|UNxL|ST|DLU";
        $xmlarray = $this->xml_array_list($res,$arr);

        dump($xmlarray);die;
    }




//    public static function curl_get($url){
//
//
//        $testurl = $url;
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $testurl);
//
//        //参数为1表示传输数据，为0表示直接输出显示。
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        //参数为0表示不带头文件，为1表示带头文件
//        curl_setopt($ch, CURLOPT_HEADER,0);
//        $output = curl_exec($ch);
//        curl_close($ch);
//        return $output;
//        //print_r($output);
//    }

    /**
     * xml 转换 array
     * $res 返回的数据
     * $arr 接口字段 SN|PNxL|BC|UBC|UNxL|ST|DLU
     **/
    public  function xml_array_list($res,$arr){

        $objectxml = simplexml_load_string($res);
        $xmljson= json_encode($objectxml);//将对象转换个JSON
        $xmlarray=json_decode($xmljson,true);
        $new_arr= implode(" ",$xmlarray);
        if($new_arr == 'null'){
            return null;
        }
        $new_array= json_decode($new_arr,true);
        $new_field  =explode('|',$arr);
        foreach ($new_array as $key =>$v){
            $new_array[$key] = explode('|',$v);
        }
        $res_r=array();
        foreach ($new_array as $k=>$y){
            foreach ($y as $kk=>$v){
                $res_r[$k][$new_field[$kk]] = $v;

            }
        }

        return $res_r;
    }


    /**
     * xml 转换 array
     * $res 返回的数据
     * $arr 接口字段 SN|PNxL|BC|UBC|UNxL|ST|DLU
     **/
    public  function xml_array_find($res,$arr){

        $objectxml = simplexml_load_string($res);
        $xmljson= json_encode($objectxml);//将对象转换个JSON
        $xmlarray=json_decode($xmljson,true);
        $new_arr= implode(" ",$xmlarray);
        if($new_arr == 'null'){
            return null;
        }
        $new_array  =explode('|',$new_arr);
        $new_field  =explode('|',$arr);
        $res_r=array();
        foreach ($new_field as $key=>$item){
            foreach ($new_array as $k=>$y){
                $res_r[$new_field[$k]]=$y;
            }
        }
        return $res_r;
    }

    public  function curl_post($url,$post_data,$cookies){

            $curl = curl_init();
            //$header[] = "Content-type: text/xml";//定义content-type为xml
            $header[]='Content-Type: application/x-www-form-urlencoded';
            //设置提交的url
            curl_setopt($curl, CURLOPT_URL, $url);
            //设置头文件的信息作为数据流输出
            curl_setopt($curl, CURLOPT_HEADER, 0);
            //设置获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

            //设置post方式提交
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies);  //发送
            curl_setopt($curl,CURLOPT_COOKIEJAR, $cookies);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            //设置post数据
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
            //执行命令
            $data = curl_exec($curl);

        //關閉URL请求
            curl_close($curl);
            header('Content-type:text/html;charset=utf8');
            //获得数据并返回
            return $data;
    }


}