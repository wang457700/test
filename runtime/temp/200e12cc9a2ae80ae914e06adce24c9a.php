<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:92:"D:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\user\user\detail.html";i:1534217095;s:80:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1529292885;s:77:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1529292885;s:79:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1529292885;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/fastadmin/public/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/fastadmin/public/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/fastadmin/public/assets/js/html5shiv.js"></script>
  <script src="/fastadmin/public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !$config['fastadmin']['multiplenav']): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <style type="text/css">
   
    .bg_fff{background:#fff;padding: 20px;padding-top: 5px;}
    .notable tr{background-color:#fff !important;}
    .notable td{border-top:0 !important;}
    .notable th{border-top:0 !important;}
    h4{font-weight: bold;}

</style>
<div class="bg_fff">
    <h4>用戶信息</h4>
    <table class="table table-striped col-sm-4 notable">
        <thead>
            
        </thead>
        <tbody class="col-xs-12 col-sm-12">
            <tr class="col-xs-4 col-sm-4">
                <th>用戶名：</th>
                <td>maychan</td>
            </tr>
            <tr class="col-xs-4 col-sm-4">
                <th>用戶ID：</th>
                <td>100086</td>
            </tr>
            <tr class="col-xs-4 col-sm-4">
                <th>出生日期：</th>
                <td>1992/10/01</td>
            </tr>
            <tr class="col-xs-4 col-sm-4">
                <th>注册類型：</th>
                <td>微信</td>
            </tr> 
            <tr class="col-xs-4 col-sm-4">
                <th>電郵：</th>
                <td>may.chan@teamotto.net</td>
            </tr> 
            <tr class="col-xs-4 col-sm-4">
                <th>手機號碼：</th>
                <td>131929639666</td>
            </tr> 
            <tr class="col-xs-4 col-sm-4">
                <th>用戶類型：</th>
                <td>金牌</td>
            </tr> 
            <tr class="col-xs-4 col-sm-4">
                <th>積分：</th>
                <td>10000</td>
            </tr> 
            <tr class="col-xs-4 col-sm-4">
                <th>本月失效積分：</th>
                <td>666</td>
            </tr> 
        </tbody>
    </table>
    <div style="clear:both"></div>
    <h4 >收货地址</h4>
    <table class="table table-striped notable">
        <thead>
           
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>收貨人姓名：陳大大</td>
                <td>聯繫電話：131929639666</td>
                <td>收貨地址：九龍油塘高超到38號大本型商場2樓</td>
            </tr>
           <tr>
                <td>2</td>
                <td>收貨人姓名：陳大大</td>
                <td>聯繫電話：131929639666</td>
                <td>收貨地址：九龍油塘高超到38號大本型商場2樓</td>
            </tr>
            
        </tbody>
    </table>
</div>

<div class="bg_fff" style="margin-top: 20px;">
    <h4>訂單信息</h4>
    <table class="table table-striped">
        <thead>
            <tr>
              <th>訂單ID</th>
              <th>日期</th>
              <th>收货地址</th>
              <th>訂單金額</th>
              <th>訂單狀態</th>
              <th>實付金額</th>
              <th>支付狀態</th>
              <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2018070102</td>
                <td>2018/07/01 09:33:69</td>
                <td>收貨人姓名：陳大大</td>
                <td>聯繫電話：131929639666</td>
                <td>收貨地址：九龍油塘高超到38號大本型商場2樓</td>
                 <td>$1,174.32</td>
                 <td>已付款</td>
                 <td><a>查看详情</a></td>
            </tr>
            <tr>
                <td>2018070102</td>
                <td>2018/07/01 09:33:69</td>
                <td>收貨人姓名：陳大大</td>
                <td>聯繫電話：131929639666</td>
                <td>收貨地址：九龍油塘高超到38號大本型商場2樓</td>
                 <td>$1,174.32</td>
                 <td>已付款</td>
                 <td><a>查看详情</a></td>
            </tr>
            
        </tbody>
    </table>
</div>




                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/fastadmin/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/fastadmin/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>