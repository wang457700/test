<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:92:"D:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\product\category.html";i:1534824302;s:80:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1529292885;s:77:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1529292885;s:79:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1529292885;}*/ ?>
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
#table tr:hover{}
.on{background: #18bc9b !important;color:#fff;}

</style>
<div class="panel panel-default panel-intro row">
    <div class="panel-heading">
        <?php echo build_heading(null,FALSE); ?>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#all" data-toggle="tab"><?php echo __('All'); ?></a></li>
               
                 <?php if(is_array($category_list) || $category_list instanceof \think\Collection || $category_list instanceof \think\Paginator): if( count($category_list)==0 ) : echo "" ;else: foreach($category_list as $key=>$vo): ?>
                    <li><a href="#<?php echo $vo['id']; ?>" data-toggle="tab"><?php echo $vo['name']; ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            
        </ul>
    </div>
    <div class="panel-body col-sm-6">
        <div>二级分类</div>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div id="toolbar" class="toolbar">
                        <?php echo build_toolbar('refresh'); ?>
                        <a href="javascript:;" class="btn btn-success btn-add" title="添加"><i class="fa fa-plus"></i> 新增分类</a>
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover" 
                           data-operate-edit="<?php echo $auth->check('category/edit'); ?>" 
                           data-operate-del="<?php echo $auth->check('category/del'); ?>" 
                           width="100%">
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="panel-body col-sm-4">
         <div  style="padding: 10px  5px;">三级分类</div>
        <form id="edit-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="product/category_sub_edit" style="min-height: 450px;">
            <input type="hidden" name="row[pid]" value="" id="sub_pid">
            <input type="hidden" name="row[data]" value="" id="sub_pid">
            <input type="hidden" name="row[new]" value="" id="sub_pid">
            <div id="form-group">
                
            </div>
            <div class="form-group">
                <div class="col-xs-12 col-sm-4">
                    <button type="text" class="btn btn-success">保存</button>
                </div> 
                <a href="javascript:;" class=" btn-add pull-right" title="添加" id="add_sub"><i class="fa fa-plus"></i> 新增分类</a>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/fastadmin/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/fastadmin/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>