<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:93:"D:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\order\index\index.html";i:1533779318;s:80:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1529292885;s:77:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1529292885;s:79:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1529292885;}*/ ?>
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
        .sm-st {
            background:#fff;
            padding:20px;
            -webkit-border-radius:3px;
            -moz-border-radius:3px;
            border-radius:3px;
            margin-bottom:20px;
            -webkit-box-shadow: 0 1px 0px rgba(0,0,0,0.05);
            box-shadow: 0 1px 0px rgba(0,0,0,0.05);
        }
        .sm-st-icon {
            width:60px;
            height:60px;
            display:inline-block;
            line-height:60px;
            text-align:center;
            font-size:30px;
            -webkit-border-radius:5px;
            -moz-border-radius:5px;
            border-radius:5px;
            float:left;
            margin-right:10px;
            color:#1a8551;
        }
        .sm-st-info {
            font-size:12px;
            padding-top:2px;
        }
        .sm-st-info span {
            display:block;
            font-size:24px;
            color: #1a8551;
            font-weight:600;
        } 
        .sm-st .jine{
            color: #1a8551;
            font-size: 18px;
        }

        .stat-elem { 
            background-color: #fff;
            padding: 18px;
            border-radius: 40px; 
        }

        .stat-info {
            text-align: center;
            background-color:#fff;
            border-radius: 5px;
            margin-top: -5px;
            padding: 8px;
            -webkit-box-shadow: 0 1px 0px rgba(0,0,0,0.05);
            box-shadow: 0 1px 0px rgba(0,0,0,0.05);
            font-style: italic;
        }

        .stat-icon {
            text-align: center;
            margin-bottom: 5px;
        }

        .stats .stat-icon {
            color: #28bb9c;
            display: inline-block;
            font-size: 26px;
            text-align: center;
            vertical-align: middle;
            width: 50px;
            float:left;
        }

        .stat {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            margin-right: 10px; }
        .stat .value {
            font-size: 20px;
            line-height: 24px;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 500; }
        .stat .name {
            overflow: hidden;
            text-overflow: ellipsis; }
        .stat.lg .value {
            font-size: 26px;
            line-height: 28px; }
        .stat.lg .name {
            font-size: 16px; }
        .stat-col .progress {height:2px;}
        .stat-col .progress-bar {line-height:2px;height:2px;}

        .item {
            padding:30px 0;
        }
    </style>
    <div class="row">
        <div class="col-sm-1 col-xs-1"> </div>
        <div class="col-sm-2 col-xs-6">
            <div class="sm-st clearfix">
                <span class="sm-st-icon"><i class="fa fa-shopping-bag"></i></span>
                <div class="sm-st-info">
                    <span>35200</span>
                    訂單數量                            
                </div>
            </div>
        </div>
        <div class="col-sm-2 col-xs-6">
            <div class="sm-st clearfix">
                <span class="sm-st-icon"><i class="fa fa-cube"></i></span>
                <div class="sm-st-info">
                    <span>219390</span>
                    產品出售數量                           
                </div>
            </div>
        </div>
        <div class="col-sm-2 col-xs-6">
            <div class="sm-st clearfix">
                <span class="sm-st-icon"><i class="fa fa-dollar"></i></span>
                <div class="sm-st-info">
                    <span>32143</span>
                    港幣實付總額                           
                </div>
            </div>
        </div>
        <div class="col-sm-2 col-xs-6">
            <div class="sm-st clearfix">
                <span class="sm-st-icon"><i class="fa fa-cny"></i></span>
                <div class="sm-st-info">
                    <span>174800</span>
                    人民幣實付總額   
               </div>
            </div>
        </div>
        <div class="col-sm-3 col-xs-6">
            <div class="sm-st clearfix" style="text-align:center;padding: 10px 20px;line-height: 26px;">
                <div>訂單狀態</div>
                <div class="row" style="color: #999;">
                    <div class="col-sm-4 col-xs-4">
                        <span class="jine">20</span><br>
                        已發貨
                    </div>
                    <div class="col-sm-4 col-xs-4">
                        <span class="jine">5</span><br>
                        未發貨
                    </div>
                    <div class="col-sm-4 col-xs-4">
                        <span class="jine">2</span><br>
                        已取消
                    </div> 
                </div>
            </div>
        </div>
    </div>
<div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>  
    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div id="toolbar" class="toolbar">
                        <?php echo build_toolbar('refresh'); ?>
                        <div class="dropdown btn-group <?php echo $auth->check('user/user/multi')?'':'hide'; ?>">
                           
                        </div>
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover" 
                           data-operate-edit="<?php echo $auth->check('user/user/edit'); ?>" 
                           data-operate-del="<?php echo $auth->check('user/user/del'); ?>" 
                           width="100%">
                    </table>
                </div>
            </div>

        </div>
    </div>
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