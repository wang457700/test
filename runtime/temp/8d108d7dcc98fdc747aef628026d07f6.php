<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:92:"D:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\extend\slide\add.html";i:1534477226;s:80:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1529292885;s:77:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1529292885;s:79:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1529292885;}*/ ?>
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
                                <form id="edit-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="" style="min-height: 450px;">
    <div class="form-group">
        <label for="c-type" class="control-label col-xs-12 col-sm-2">类型:</label>
        <div class="col-xs-12 col-sm-8">

            <select id="c-type" data-rule="required" class="form-control selectpicker" name="row[slide_type]">
                <option value="index_banner" >首页轮播_banner 1920px*420px</option>
                <option value="float_left_banner" >左边浮动_banner 255px*255px</option>
                <option value="center_lefe_banner" >首页固定_左边_banner 390px*360px</option>
                <option value="center_right_banner" >首页固定_右边(最多两个)_banner 790px*170px</option>
            </select>

        </div>
    </div>
    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2">banner名稱</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="slide_name" name="row[slide_name]" value="" data-rule="required;slide_name" />
        </div>
    </div>

    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2">链接</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="url" name="row[slide_url]" value="" data-rule="slide_url" />
        </div>
    </div>
   
    <div class="form-group">
        <label for="c-logintime" class="control-label col-xs-12 col-sm-2">有效期:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-logintime" data-rule="required" class="form-control datetimepicker " data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[start_time]" type="text" value="" style="width: 40%">
            -
            <input id="c-logintime" data-rule="required" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[end_time]" type="text" value="" style="width: 40%">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">狀態</label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[slide_status]', ['1'=>'顯示', '0'=>'隐藏'], 1); ?>
        </div>
    </div>
    <div class="form-group hidden layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2">排序</label>
        <div class="col-xs-12 col-sm-8">
            <input type="number" class="form-control" id="listorder" name="row[listorder]" value="0" data-rule="required;listorder" />
        </div>
    </div>
    <div class="form-group">
        <label for="c-image" class="control-label col-xs-12 col-sm-2">图片:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-image" class="form-control" size="50" name="row[slide_pic]" type="text" value="">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-image" class="btn btn-danger plupload" data-input-id="c-image" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-image"><i class="fa fa-upload"></i> 上传</button></span>
                    <span><button type="button" id="fachoose-image" class="btn btn-primary fachoose" data-input-id="c-image" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image"></ul>
        </div>
    </div> 
</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/fastadmin/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/fastadmin/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>