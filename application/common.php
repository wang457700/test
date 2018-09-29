<?php

use think\Db;
use think\Session;
use fast\Tree;
use think\Request;
use app\common\model\Category as CategoryModel;

// 公共助手函数

if (!function_exists('__')) {

    /**
     * 获取语言变量值
     * @param string $name 语言变量名
     * @param array $vars 动态变量值
     * @param string $lang 语言
     * @return mixed
     */
    function __($name, $vars = [], $lang = '')
    {
        if (is_numeric($name) || !$name)
            return $name;
        if (!is_array($vars)) {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }
        return \think\Lang::get($name, $vars, $lang);
    }

}

if (!function_exists('format_bytes')) {

    /**
     * 将字节转换为可读文本
     * @param int $size 大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++)
            $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

}

if (!function_exists('datetime')) {

    /**
     * 将时间戳转换为日期时间
     * @param int $time 时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function datetime($time, $format = 'Y-m-d H:i:s')
    {
        $time = is_numeric($time) ? $time : strtotime($time);
        return date($format, $time);
    }

}

if (!function_exists('human_date')) {

    /**
     * 获取语义化时间
     * @param int $time 时间
     * @param int $local 本地时间
     * @return string
     */
    function human_date($time, $local = null)
    {
        return \fast\Date::human($time, $local);
    }

}

if (!function_exists('cdnurl')) {

    /**
     * 获取上传资源的CDN的地址
     * @param string $url 资源相对地址
     * @param boolean $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    function cdnurl($url, $domain = false)
    {
        $url = preg_match("/^https?:\/\/(.*)/i", $url) ? $url : \think\Config::get('upload.cdnurl') . $url;
        if ($domain && !preg_match("/^(http:\/\/|https:\/\/)/i", $url)) {
            if (is_bool($domain)) {
                $public = \think\Config::get('view_replace_str.__PUBLIC__');
                $url = rtrim($public, '/') . $url;
                if (!preg_match("/^(http:\/\/|https:\/\/)/i", $url)) {
                    $url = request()->domain() . $url;
                }
            } else {
                $url = $domain . $url;
            }
        }
        return $url;
    }

}


if (!function_exists('is_really_writable')) {

    /**
     * 判断文件或文件夹是否可写
     * @param    string $file 文件或目录
     * @return    bool
     */
    function is_really_writable($file)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === FALSE) {
                return FALSE;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return TRUE;
        } elseif (!is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE) {
            return FALSE;
        }
        fclose($fp);
        return TRUE;
    }

}

if (!function_exists('rmdirs')) {

    /**
     * 删除文件夹
     * @param string $dirname 目录
     * @param bool $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true)
    {
        if (!is_dir($dirname))
            return false;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself) {
            @rmdir($dirname);
        }
        return true;
    }

}

if (!function_exists('copydirs')) {

    /**
     * 复制文件夹
     * @param string $source 源文件夹
     * @param string $dest 目标文件夹
     */
    function copydirs($source, $dest)
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                $sontDir = $dest . DS . $iterator->getSubPathName();
                if (!is_dir($sontDir)) {
                    mkdir($sontDir, 0755, true);
                }
            } else {
                copy($item, $dest . DS . $iterator->getSubPathName());
            }
        }
    }

}

if (!function_exists('mb_ucfirst')) {

    function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
    }

}

if (!function_exists('addtion')) {

    /**
     * 附加关联字段数据
     * @param array $items 数据列表
     * @param mixed $fields 渲染的来源字段
     * @return array
     */
    function addtion($items, $fields)
    {
        if (!$items || !$fields)
            return $items;
        $fieldsArr = [];
        if (!is_array($fields)) {
            $arr = explode(',', $fields);
            foreach ($arr as $k => $v) {
                $fieldsArr[$v] = ['field' => $v];
            }
        } else {
            foreach ($fields as $k => $v) {
                if (is_array($v)) {
                    $v['field'] = isset($v['field']) ? $v['field'] : $k;
                } else {
                    $v = ['field' => $v];
                }
                $fieldsArr[$v['field']] = $v;
            }
        }
        foreach ($fieldsArr as $k => &$v) {
            $v = is_array($v) ? $v : ['field' => $v];
            $v['display'] = isset($v['display']) ? $v['display'] : str_replace(['_ids', '_id'], ['_names', '_name'], $v['field']);
            $v['primary'] = isset($v['primary']) ? $v['primary'] : '';
            $v['column'] = isset($v['column']) ? $v['column'] : 'name';
            $v['model'] = isset($v['model']) ? $v['model'] : '';
            $v['table'] = isset($v['table']) ? $v['table'] : '';
            $v['name'] = isset($v['name']) ? $v['name'] : str_replace(['_ids', '_id'], '', $v['field']);
        }
        unset($v);
        $ids = [];
        $fields = array_keys($fieldsArr);
        foreach ($items as $k => $v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $ids[$n] = array_merge(isset($ids[$n]) && is_array($ids[$n]) ? $ids[$n] : [], explode(',', $v[$n]));
                }
            }
        }
        $result = [];
        foreach ($fieldsArr as $k => $v) {
            if ($v['model']) {
                $model = new $v['model'];
            } else {
                $model = $v['name'] ? \think\Db::name($v['name']) : \think\Db::table($v['table']);
            }
            $primary = $v['primary'] ? $v['primary'] : $model->getPk();
            $result[$v['field']] = $model->where($primary, 'in', $ids[$v['field']])->column("{$primary},{$v['column']}");
        }

        foreach ($items as $k => &$v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $curr = array_flip(explode(',', $v[$n]));

                    $v[$fieldsArr[$n]['display']] = implode(',', array_intersect_key($result[$n], $curr));
                }
            }
        }
        return $items;
    }

}

if (!function_exists('var_export_short')) {

    /**
     * 返回打印数组结构
     * @param string $var 数组
     * @param string $indent 缩进字符
     * @return string
     */
    function var_export_short($var, $indent = "")
    {
        switch (gettype($var)) {
            case "string":
                return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
            case "array":
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value) {
                    $r[] = "$indent    "
                        . ($indexed ? "" : var_export_short($key) . " => ")
                        . var_export_short($value, "$indent    ");
                }
                return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, TRUE);
        }
    }

}
/********************************               home 公共模块新增                    *************************************/

/**
 * 查询系统分类子分类
 * $cid 父分类id
 * */
function sp_getTreeList($cid){
    $tree = Tree::instance();
    $tree->init(collection(model('app\common\model\Category')->order('weigh desc,id desc')->select())->toArray(), 'pid');
    $newscategory = $tree->getTreeList($tree->getTreeArray($cid), 'name');
    return $newscategory;
}

/**
 * 转化数据库保存图片的文件路径，为可以访问的url
 * @param string $file 文件路径，数据存储的文件相对路径 
 */
function fa_get_image_url($file)
{  
    $PHP_SELF=$_SERVER['PHP_SELF'];
    $url=substr($PHP_SELF,0,strrpos($PHP_SELF,'/')+1).$file;
    return $url;
}

/**
* 手机号码 正则表达式格式化 每4位隔空格显示
* 格式后：138 1000 2000
*/
function format_phone($phone)
{
    preg_match('/([\d]{3})([\d]{4})([\d]{4})/', $phone,$match);
    unset($match[0]);
    return implode(' ', $match);
}

/**
 * 查询产品金额并输出
 * $product_id 商品id
*/
function product_price($product_id)
{
    $price = 0;
    $product =  Db::name('goods')->where(array('product_id'=>$product_id))->find();
    if($product['discount_type'] == 1){
        $price = $product['price'];
    }
    if($product['discount_type'] == 2){
        $price = $product['pricevip'];
    }
    if($product['discount_type'] == 3){
        if(strtotime($product['discount_end_time']) > time() && strtotime($product['discount_start_time']) < time()){
            $price = $product['discount_price'];
        }else{
            $price = $product['price'];
        }
    }
    return $price;
}

/**
 * 查询用户购物车数量
 * $user_id 用户id
 */
function count_cart_num($user_id)
{
    $num =  Db::name('cart_order')->where(array('user_id'=>$user_id))->count();
    return $num;
}


/**
* 计算一个订单总金额
* $order_sn 订单号
*/
function sum_order_price($order_sn)
{
    $goods_money_total =  Db::name('order')->where(array('order_sn'=>$order_sn))->sum('money_total');

    //输出 运费金额，积分抵扣金额，运费，服务费
    $order =  Db::name('order')->where(array('order_sn'=>$order_sn))->value('coupon_price,integral_price,freight,service_price');


   dump($order);
}

/**
 * 查询支付方式
 * $payment 支付方式id
*/
function sp_payment($payment)
{
    $payment_text = array('0'=>'未知','1'=>'微信','2'=>'支付宝','3'=>'Mastercard','4'=>'Visa');
    return $payment_text[$payment];
}

/**
 * 统计订单应付金额
 * $order_sn 订单号
*/
function sum_order_payableprice($order_sn)
{
    $order =  Db::name('order')->where('order_sn',$order_sn)->find();
    $goods_money_total =  Db::name('order')->where('order_sn',$order_sn)->sum('money_total');
    /* 应付金额 = 商品总金额 + 运费 + 服务费 + 捐款金额 - 积分抵扣金额 - 优惠金额 */
    $payableprice = $goods_money_total + $order['freight'] + $order['service_price'] + $order['contribution_price'] - $order['integral_price'] - $order['coupon_price'];

    if($payableprice < 0){
        $payableprice = 0;
    }
    return $payableprice;
}

/**  用户是否已登录 */
function is_login()
{
    $user_id = Session('user_id');
    return $user_id;
}

/**  查询用户信息 */
function sp_user_info()
{
    $user_id = Session('user_id');
    $user = Db::name('user')->where('id',$user_id)->find();
    $user['platform'] = Session('platform');
    return $user;
}
/**  创建游客信息  */
function create_tourist()
{
    $tourist_id = getRandomString(3);
    $data = array(
        'username'=>'youke'.$tourist_id,
        'nickname'=>'游客'.$tourist_id,
        'user_type'=>'3',
        'is_eamil_status'=>'1',
        'joinip'=>get_client_ip(0,true),
        'jointime'=>time(),
    );
    $user =  Db::name('user')->insertGetId($data);
    if ($user!==false){
        Session('user_id',$user);
    }
    return $user;
}
/**  查询地区信息
 *  $regionid 地区id
 *  $value  输出的字段
*/

function sp_region_value($regionid,$value = 'name'){
    $region =  Db::name('region')->where('id',$regionid)->value($value);
    return $region;
}

/**  查询用户地址信息
 *  $addressid 地区id
 *  $field  输出的字段
*/

function sp_address_info($userid = '0',$addressid = '0',$field = 'id'){
    $address['province'] = 0;
    $address['city'] = 0;
    $address['district'] = 0;
    $address =  Db::name('user_address')
        ->field('province,city,district,'.$field)
        ->where(array('id'=>$addressid,'user_id'=>$userid))
        ->find();
    $address['province'] = sp_region_value($address['province']);
    $address['city'] = sp_region_value($address['city']);
    $address['district'] = sp_region_value($address['district']);
    return $address;
}

/**
 * 查询用户最近购买记录
 * $userid 用户id
 * $num 数量
*/
function sp_user_buygoods($userid = '0',$num = 5){

    $goods =  Db::name('order')
        ->alias('a')
        ->field('a.*,c.product_name,c.product_name,c.cover')
        ->join('__GOODS__ c','a.goods_id=c.product_id','LEFT')
        ->where(array('user_id'=>$userid))
        ->limit(1,$num)
        ->select();
    return $goods;
}


/**
 * 用户会员升级
 * $userid 用户id
 */
function sp_user_vipupgrade($userid){
    $integral =  Db::name('integral_log')
        ->where(array('user_id'=>$userid,'type'=>'add'))
        ->sum('integral');
    $level = 'level1';
    $levels = array('level0'=>0,'level1'=>1,'level2'=>2,'level3'=>3,'level4'=>4,);
    $user_upgrade = config('site')['user_upgrade'];
    foreach ($user_upgrade as $key =>$item){
        if($integral >= $item){
            $level = $key;
        }
    }
    $res = Db::name('user')
        ->where(array('id'=>$userid))
        ->update(array('level'=>$levels[$level]));
    return true;
}

/**
 * 生成随机数字，
 * $len 位数
 * $chars 自定义字符
 */
function getRandomString($len, $chars=null)
{
    if (is_null($chars)) {
        $chars = "0123456789";
    }
    mt_srand(10000000*(double)microtime());
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {
        $str .= $chars[mt_rand(0, $lc)];
    }
    return $str;
}

/**
 * 生成随机数字，
 * $len 位数
 * $chars 自定义字符
 */
function sp_user_default_address()
{
    $user_id = Session::get('user_id');
    $address= Db::name('user_address')->where(array('user_id'=>$user_id,'default'=>1))->find();
    return $address;
}

/**
 * 是否内地ip
 *
 */
function sp_ip_ischina()
{
    $data['status'] = 0;
    $url = 'http://ip-api.com/json/'.request()->ip();
    $json = request_post($url,$data = 'null');
    $data = json_decode($json,true);
    $echo = false;
    if($data['status'] == 'success' && $data['countryCode'] == 'CN'){
        $echo = true;
    }
    return $echo;
}



/**
 * 模拟post进行url请求
 * @param string $url
 * @param string $param
 */
function request_post($url = '', $param = '') {
    if (empty($url) || empty($param)) {
        return false;
    }
    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    return $data;
}


/* PHP CURL HTTPS POST */
function curl_post_https($url,$data){ // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据，json格式
}
/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
        if (false === $slice) {
            $slice = '';
        }
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    //字数不满不添加...
    $count = mb_strlen($str, 'utf-8');
    if ($count > $length) {
        return $suffix ? $slice . '...' : $slice;
    } else {
        return $slice;
    }
}

/** 获取IP */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
