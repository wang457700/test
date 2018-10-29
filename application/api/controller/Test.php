<?php

/**
 * Created by PhpStorm.
 * User: YXL
 * Date: 2018/9/4
 * Time: 14:57
 */

namespace app\appapi\controller;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Session;

class Test extends Controller {


    public function index()
    {

        $url='http://103.254.210.215//hksrapisttest/api.asmx/System_Login';
        $post_data=array(
            'sUsername'=>101,
            'sPassword'=>101,
        );
        $cookies = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookies);
        print_r($res);die;
        
    }
    /***
     * 获取商品列表
     */

    public function Product_GetList(){

        $url='http://103.254.210.215//hksrapisttest/api.asmx/System_Login';
        $post_data=array(
            'iC1'=>-1,
            'iC2'=>-1,
            'iC3'=>-1,
            'sOrderBy'=>'BC',
            'bAscend'=>'true',
            'iRecFrom'=>0,
            'iRecTo'=>0

        );
        $cookies = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookies);
        $objectxml = simplexml_load_string($res);
        $xmljson= json_encode($objectxml );//将对象转换个JSON
        $xmlarray=json_decode($xmljson,true);
        print_r($xmlarray);die;
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
     * 不知道是很么鬼
     */
    public function Task_CreateSalesOrder(){


    }

    /***
     *
     */
 public function Product_GetFullStockListByBC(){

     $url='http://103.254.210.215//hksrapisttest/api.asmx/Product_GetFullStockListByBC';
     $post_data=array(
         'sBC'=>''  //填写商品码
     );
     $cookies = dirname(__FILE__).'/cookie.txt';
     $res=$this->curl_post($url,$post_data,$cookies);

     $objectxml = simplexml_load_string($res);
     $xmljson= json_encode($objectxml);//将对象转换个JSON
     $xmlarray=json_decode($xmljson,true);
     $new_arr= implode(" ",$xmlarray);
     $new_array  =explode('|',$new_arr);
     $arr="SN|PNxL|BC|UBC|UNxL|ST|DLU";


     $new_field  =explode('|',$arr);

     $res_r=array();
     foreach ($new_field as $key=>$item){
         foreach ($new_array as $k=>$y){
             $res_r[$new_field[$k]]=$y;
         }
     }


     print_r($res);die;

 }

    /***
     * 添加会员信息
     */
    public function member_user_add(){

        $cookie = dirname(__FILE__).'/cookie.txt';

        $url="http://103.254.210.215/hksrapisttest/api.asmx/Member_AddNew";

        $post_data=array(
            'sID'=>'000111',
            'sCAID'=>'',
            'sSID'=>'',
            'sN1L'=>'李刚',
            'sN2L'=>'dalys',
            'sTL1'=>'',
            'sTL2'=>'',
            'sEM'=>'',
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
            'sID'=>'0001411',

        );
        $cookie = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookie);
        $objectxml = simplexml_load_string($res);
        $xmljson= json_encode($objectxml);//将对象转换个JSON
        $xmlarray=json_decode($xmljson,true);
        $new_arr= implode(" ",$xmlarray);
        $new_array  =explode('|',$new_arr);
        $arr="ID|SID|CAID|N1L|N2L|NN|LN1L|LN2L|SGR|CBAL|HBAL|BPT1|BPT2|BPT3|BPT4|SSE|TL1|TL2|AD1L1|AD1L2|AD1L3|AD2L1|AD2L2|AD2L3|EM|DC|SDB|DJ|CAXP|DL|MC|OC|RC1|RC2|RE1L|RE2L|REFID";


        $new_field  =explode('|',$arr);

        $res_r=array();
        foreach ($new_field as $key=>$item){
            foreach ($new_array as $k=>$y){
                $res_r[$new_field[$k]]=$y;
            }
        }

        print_r($res_r);die;


//                $new_array  =explode('|',$res);
//                $new_res= implode(",", $new_array);
//                $objectxml = simplexml_load_string($new_res);
//                $xmljson= json_encode($objectxml );//将对象转换个JSON
//                $xmlarray=json_decode($xmljson,true);
//                print_r($xmlarray);die;

    }


    /**
     * 获取会员列表
     */
    public function Member_GetList(){
        $url="http://103.254.210.215/hksrapisttest/api.asmx/Member_GetList";

        $post_data=array(
            'sOrderBy'=>'ID',
            'bAscend'=>'true',
            'iRecFrom'=>'0',
            'iRecTo'=>'0'
        );
        $cookie = dirname(__FILE__).'/cookie.txt';
        $res=$this->curl_post($url,$post_data,$cookie);
        $objectxml = simplexml_load_string($res);
        $xmljson= json_encode($objectxml );//将对象转换个JSON
        $xmlarray=json_decode($xmljson,true);
        print_r($xmlarray);die;
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

        //关闭URL请求
            curl_close($curl);
        header('Content-type:text/html;charset=utf8');
            //获得数据并返回
            return $data;
        //print_r($data);
    }


}