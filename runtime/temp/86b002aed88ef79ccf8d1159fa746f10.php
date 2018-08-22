<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:88:"D:\phpStudy\PHPTutorial\WWW\fastadmin\public/../application/admin\view\product\edit.html";i:1534150206;s:80:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\layout\default.html";i:1529292885;s:77:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\meta.html";i:1529292885;s:79:"D:\phpStudy\PHPTutorial\WWW\fastadmin\application\admin\view\common\script.html";i:1529292885;}*/ ?>
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
.plupload {
    background: url(/fastadmin/public/assets/img/upload_img.png) no-repeat;
    background-size: 100%;
    width: 60px;
    height: 60px;
}
.plupload:active,.plupload:hover{
    background: url(/fastadmin/public/assets/img/upload_img.png) no-repeat;
    background-size: 100%;
    width: 60px;
    height: 60px;
    border:0;
}
.danxuan .fa-circle{color: #eee;}
.danxuan.on .fa-circle{display: inline-block;color: #18bc9b;}

</style>
<form id="edit-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="" style="min-height: 450px;">
    
    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2">產品名稱</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="slide_name" name="row[data1]" value="<?php echo $row['data1']; ?>" data-rule="required;data1" />
        </div>
    </div>

    <div class="form-group">
        <label for="c-type" class="control-label col-xs-12 col-sm-2">类型:</label>
        <div class="col-xs-12 col-sm-3">

            <select id="c-type" data-rule="required" class="form-control selectpicker" name="row[slide_type]">
                <option value="index_banner" >健康有机产品</option>
                <option value="float_left_banner" >健康有机产品1</option>
                <option value="center_lefe_banner" >健康有机产品2</option>
                <option value="center_right_banner" >健康有机产品3</option>
            </select>

        </div>
         <div class="col-xs-12 col-sm-3">

            <select id="c-type" data-rule="required" class="form-control selectpicker" name="row[slide_type]">
                <option value="index_banner" >健康食品及零食</option>
                <option value="float_left_banner" >健康食品及零食1 </option>
                <option value="center_lefe_banner" >健康食品及零食2 </option>
                <option value="center_right_banner" >健康食品及零食3</option>
            </select>

        </div>
         <div class="col-xs-12 col-sm-3">

            <select id="c-type" data-rule="required" class="form-control selectpicker" name="row[slide_type]">
                <option value="index_banner" >健康零食</option>
                <option value="float_left_banner" >健康零食1</option>
                <option value="center_lefe_banner" >健康零食2</option>
                <option value="center_right_banner" >健康零食3</option>
            </select>

        </div>
    </div>

    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2">品牌</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="slide_url" name="row[data2]" value="<?php echo $row['data2']; ?>" data-rule="required;data2" />
        </div>
    </div>
    
    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2">貨品ID</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="slide_url" name="row[data3]" value="<?php echo $row['data3']; ?>" data-rule="required;data3" />
        </div>
    </div>
    
    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2">規格</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="slide_url" name="row[data4]" value="<?php echo $row['data4']; ?>" data-rule="required;data4" />
        </div>
    </div> 

    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2">產地</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="slide_url" name="row[data4]" value="<?php echo $row['slide_url']; ?>" data-rule="required;data4" />
        </div>
    </div>


    <div class="form-group on danxuan">
        <div class="col-sm-12">
            <label for="username" class="control-label col-xs-12 col-sm-3"><i class="fa fa-circle"></i> 價格</label>
            <div class="col-xs-12 col-sm-5">
                <input type="text" class="form-control" id="data5" name="row[data5]" value="<?php echo $row['data5']; ?>" data-rule=";data5" />
            </div>
        </div>
    </div> 
    <div class="form-group danxuan"> 
        <div class="col-sm-12">
            <label for="username" class="control-label col-xs-12 col-sm-3"><i class="fa fa-circle"></i> 特價</label>
            <div class="col-xs-12 col-sm-5">
                <input type="text" class="form-control" id="data6" name="row[data6]" value="<?php echo $row['data6']; ?>" data-rule=";data6" />
            </div>
        </div>
    </div> 
    <div class="form-group danxuan" > 
        <div class="col-sm-12">
            <label for="username" class="control-label col-xs-12 col-sm-3"><i class="fa fa-circle"></i> 優惠</label>
            <div class="col-xs-12 col-sm-4">
                <input type="text" class="form-control" id="data7" name="row[data7]" value="<?php echo $row['data7']; ?>" data-rule=";data7" />
            </div>
            <div class="col-xs-12 col-sm-1">%</div>
        </div>
    </div>

    <div class="form-group has-success">
        <label for="c-logintime" class="control-label col-xs-12 col-sm-3">开始时间:</label>
        <div class="col-xs-12 col-sm-8" style="position: relative;">
            <input id="c-logintime" class="form-control datetimepicker " data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[start_time]" type="text" value="2018-08-08 16:43:43" style="width: 40%">
            结束时间：
            <input id="c-logintime" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[end_time]" type="text" value="2018-07-31 16:43:44" style="width: 40%">
        </div>
    </div>
    <div class="form-group">
        <label for="c-image" class="control-label col-xs-12 col-sm-2">封面</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-image" class="form-control" size="50" name="row[data8]" type="hidden" value="<?php echo $row['data8']; ?>">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-image" class="btn btn-danger plupload" data-input-id="c-image" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-image"></button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image"></ul>
        </div>
    </div>
    <div class="form-group">
        <label for="c-image1" class="control-label col-xs-12 col-sm-2">图片</label>
        <div class="col-xs-12 col-sm-1">
            <div class="input-group">
                <input id="c-image1" class="form-control" size="50" name="row[data9]" type="hidden" value="<?php echo $row['data9']; ?>">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-image1" class="btn btn-danger plupload" data-input-id="c-image1" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-image1"></button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image1"  data-template="uploadtpl"></ul>
        </div>
        <div class="col-xs-12 col-sm-1">
            <div class="input-group">
                <input id="c-image2" class="form-control" size="50" name="row[data9]" type="hidden" value="<?php echo $row['data9']; ?>">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-image2" class="btn btn-danger plupload" data-input-id="c-image2" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-image2"></button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image2"  data-template="uploadtpl"></ul>
        </div>
        <div class="col-xs-12 col-sm-1">
            <div class="input-group">
                <input id="c-image3" class="form-control" size="50" name="row[data9]" type="hidden" value="<?php echo $row['data9']; ?>">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-image3" class="btn btn-danger plupload" data-input-id="c-image3" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-image3"></button></span>
                </div>
                <span class="msg-box n-right"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-image3"  data-template="uploadtpl"></ul>
        </div>



    </div>

    <div class="form-group"> 
        <div class="col-sm-12">
            <label for="username" class="control-label col-xs-12 col-sm-2">SKU</label>
            <div class="col-xs-12 col-sm-8">
                <input type="text" class="form-control" id="data10" name="row[data10]" value="<?php echo $row['data10']; ?>" data-rule="required;" />
            </div>
        </div>
    </div>
    <div class="form-group"> 
        <div class="col-sm-12">
            <label for="username" class="control-label col-xs-12 col-sm-2">庫存</label>
            <div class="col-xs-12 col-sm-2">
                <input type="text" class="form-control" id="data11" name="row[data11]" value="<?php echo $row['data11']; ?>" data-rule="required;data11" />
            </div>
            <div class="col-xs-12 col-sm-4">
                    <a type="text" class="btn btn-success">獲取庫存</a>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <?php echo build_radios('row[slide_status]', ['0'=>'TOP 10', '1'=>'熱銷', '2'=>'精選'], $row['slide_status']); ?>
        </div>
    </div>

    <div class="form-group hidden layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
    <div class="form-group tf tf-channel tf tf-list">
        <label for="c-description" class="control-label col-xs-12 col-sm-2">簡介</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-description" data-rule="" class="form-control" name="row[description]"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2">詳情</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-content" data-rule="" class="form-control editor" rows="15" name="row[content]" cols="50"></textarea>
        </div>
    </div>
    <div class="form-group"> 
        <div class="col-sm-12">
            <label for="username" class="control-label col-xs-12 col-sm-2">标题</label>
            <div class="col-xs-12 col-sm-8">
                <input type="text" class="form-control" id="data1" name="row[data12]" value="<?php echo $row['data12']; ?>" data-rule="required;data12" />
            </div>
        </div>
    </div>

    <div class="form-group"> 
        <div class="col-sm-12">
            <label for="username" class="control-label col-xs-12 col-sm-2">關鍵字</label>
            <div class="col-xs-12 col-sm-8">
                <input type="text" class="form-control" id="data2" name="row[data13]" value="<?php echo $row['data13']; ?>" data-rule="required;data13" />
            </div>
        </div>
    </div>

    <div class="form-group"> 
        <div class="col-sm-12">
            <label for="username" class="control-label col-xs-12 col-sm-2">描述</label>
            <div class="col-xs-12 col-sm-8">
                <input type="text" class="form-control" id="slide_url" name="row[data14]" value="<?php echo $row['data14']; ?>" data-rule="required;data14" />
            </div>
        </div>
    </div>


</form>
<script type="text/html" id="uploadtpl">
    <li class="col-xs-12">
         <a href="<%=fullurl%>" data-url="<%=url%>" target="_blank" class="thumbnail">
            <img src="<%=fullurl%>" class="img-responsive">
        </a>
        <a href="javascript:;" class="btn btn-danger btn-xs btn-trash"><i class="fa fa-trash"></i></a>
    </li>
</script> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/fastadmin/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/fastadmin/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>