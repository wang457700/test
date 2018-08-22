<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:101:"D:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\general\config\index_meta.html";i:1534141100;s:80:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1529292885;s:77:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1529292885;s:79:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1529292885;}*/ ?>
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
    @media (max-width: 375px) {
        .edit-form tr td input{width:100%;}
        .edit-form tr th:first-child,.edit-form tr td:first-child{
            width:20%;
        }
        .edit-form tr th:last-child,.edit-form tr td:last-child{
            display: none;
        } 
    }
    .title{padding: 20px;font-weight: bold;clear: both}
    .form-group2 .col-sm-12{margin-top: 10px;}
    .meta_title{line-height: 143px;text-align:  center;border: 1px solid #eee;border-top: 0;border-left: 0;}
</style>

<div class="panel panel-default panel-intro col-sm-6" > 

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane active">
                <form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="<?php echo url('general.config/add'); ?>">
                   
                    <div class="form-group">
                        <label for="group" class="control-label col-xs-12 col-sm-2"><?php echo __('Group'); ?>:</label>
                        <div class="col-xs-12 col-sm-4">
                            <select name="row[group]" class="form-control">
                                <option value="0" selected>0</option>
                                <option value="1" >1</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rule" class="control-label col-xs-12 col-sm-2">运费：</label>
                        <div class="col-xs-12 col-sm-4">
                            <input type="text" class="form-control" id="rule" name="row[rule]" value="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="extend" class="control-label col-xs-12 col-sm-2">免服务费区域</label>
                        <div class="col-xs-12 col-sm-4">
                            <textarea name="row[extend]" id="extend" cols="30" rows="5" class="form-control" data-rule=""></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2"></label>
                        <div class="col-xs-12 col-sm-4">
                            <button type="submit" class="btn btn-success btn-embossed"><?php echo __('OK'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default panel-intro col-sm-6" >
    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane active">
                <form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="<?php echo url('general.config/add111'); ?>">
                   
                    <div  class="form-group">
                        <div class="col-sm-6">
                            <label for="rule" class="control-label col-xs-12 col-sm-4">积分获取：</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[rule]" value="" />
                            </div>
                            <div class="col-xs-12 col-sm-2">=1分</div>
                        </div>
                        <div class="col-sm-6">
                            <label for="rule" class="control-label col-xs-12 col-sm-4">积分使用：</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[rule]" value="" />
                            </div>
                            <div class="col-xs-12 col-sm-2">=$1</div>
                        </div>
                    </div>
                <div class="title" style="padding-top: 0;">会员升级</div>
                    <div  class="form-group">
                        <div class="col-sm-6">
                            <label for="rule" class="control-label col-xs-12 col-sm-4">普通会员：</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[rule]" value="" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="rule" class="control-label col-xs-12 col-sm-4">白金会员：</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[rule]" value="" />
                            </div>
                        </div>
                    </div>
                    <div  class="form-group">
                        <div class="col-sm-6">
                            <label for="rule" class="control-label col-xs-12 col-sm-4">金牌会员：</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[rule]" value="" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="rule" class="control-label col-xs-12 col-sm-4">商业会员：</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[rule]" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2"></label>
                        <div class="col-xs-12 col-sm-4">
                            <button type="submit" class="btn btn-success btn-embossed"><?php echo __('OK'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="title">会员升级</div>
<div class="panel panel-default panel-intro col-sm-12" > 

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane active">
                <form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="<?php echo url('general.config/add'); ?>">
                   
                    <div class="col-sm-1 meta_title">
                          主页
                    </div>
                    <div  class="form-group form-group2 col-sm-11" style="border-bottom:  1px solid #eee;padding-bottom:  20px;margin: 0;">
                        <div class="col-sm-12">
                            <label for="rule" class="control-label col-xs-12 col-sm-1">标题</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[index_seo_title]" value="" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="rule" class="control-label col-xs-12 col-sm-1">关键字</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[index_seo_keywords]" value="" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="rule" class="control-label col-xs-12 col-sm-1">描述</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[index_seo_description]" value="" />
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-sm-1 meta_title">
                          产品列表
                    </div>
                    <div  class="form-group form-group2 col-sm-11"  style="border-bottom:  1px solid #eee;padding-bottom:  20px;margin: 0;">
                        <div class="col-sm-12">
                            <label for="rule" class="control-label col-xs-12 col-sm-1">标题</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[product_list_seo_title]" value="" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="rule" class="control-label col-xs-12 col-sm-1">关键字</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[product_list_seo_keywords]" value="" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="rule" class="control-label col-xs-12 col-sm-1">描述</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[product_list_seo_description]" value="" />
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-sm-1 meta_title" style="line-height: 130px;text-align:  center;">
                          社区服务资讯
                    </div>
                    <div  class="form-group form-group2 col-sm-11"  style="border-bottom:  1px solid #eee;padding-bottom:8px;margin: 0;">
                        <div class="col-sm-12">
                            <label for="rule" class="control-label col-xs-12 col-sm-1">标题</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[info_seo_title]" value="" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="rule" class="control-label col-xs-12 col-sm-1">关键字</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[info_seo_keywords]" value="" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="rule" class="control-label col-xs-12 col-sm-1">描述</label>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control" id="rule" name="row[info_seo_description]" value="" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2"></label>
                        <div class="col-xs-12 col-sm-4">
                            <button type="submit" class="btn btn-success btn-embossed"><?php echo __('OK'); ?></button>
                        </div>
                    </div>
                </form>
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