<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\user\share\add.html";i:1534840266;s:73:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\layout\public_head.html";i:1534751199;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="/fastadmin/template/pc/common/css/style.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/font-awesome/4.6.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <title><?php echo $title; ?></title>
    <style>
        *{
            padding:0;
            margin:0;
        }
        .left,.right{
            position:absolute;
            top:80px;
        }
        .left{
            left:0;
        }
        .right{
            right:0;
        }
    </style>
    <style type="text/css">
        .pagination>.active>span,.pagination>.active>span:hover{color:#ffa800;background-color: #ffffff;border-color: #ddd;}
        .pagination>li>a{color:#000000;}
      </style>
</head>
  <body style="background: url(/fastadmin/template/pc/common/img/index_bg.jpg) repeat-y;font-size: 14px;">
    <div class="container bgcolor_white">
      <div class="pr_name">發佈共享</div>
      <div class="share_product_add">
            <form action="<?php echo url('user/share_add'); ?>" class="form-horizontal" method="post" enctype="multipart/form-data" onsubmit="return share()" id="share-form"  style="padding: 0 20px;">
              <div class="form-group form-group-e">
                <label for="inputEmail3" class="col-sm-1 col-xs-12 control-label"><span class="color_ro">* </span>&nbsp;商品名稱:</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control input_e" id="title" placeholder="" name="title" required oninvalid="setCustomValidity('请输入商品名稱！')" oninput="setCustomValidity('')">
                </div>
              </div>
              <div class="form-group form-group-e">
                <label for="inputPassword3" class="col-sm-1 col-xs-6 control-label"><span class="color_ro">* </span>分&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;類：</label>
                <div class="col-sm-3">
                  <select class="form-control select_e" name="cid[]">
                    <option value="9">智能恢復產品</option>
                    <option value="9">智能恢復產品</option>
                    <option value="8">智能恢復產品</option>
                    <option value="7">智能恢復產品</option>
                  </select>
                </div>
              </div>
              <div class="form-group form-group-e">
                <label for="inputPassword3" class="col-sm-1 col-xs-6 control-label"><span class="color_ro">* </span>分&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;類&nbsp;2:</label>
                <div class="col-sm-3">
                  <select class="form-control select_e" name="cid[]">
                    <option value="6">智能恢復產品</option>
                    <option value="5">智能恢復產品</option>
                    <option value="4">智能恢復產品</option>
                  </select>

                </div>
              </div>
              <div class="form-group form-group-e">
                <label for="inputPassword3" class="col-sm-1 col-xs-6 control-label"><span class="color_ro">* </span>分&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;類&nbsp;3:</label>
                <div class="col-sm-3">
                  <select class="form-control select_e" name="cid[]">
                    <option value="1">智能恢復產品</option>
                    <option value="2">智能恢復產品</option>
                    <option value="3">智能恢復產品</option>
                  </select>
                </div>
              </div>
              <div class="form-group form-group-e">
                <label 
                for="inputPassword3" class="col-sm-1 col-xs-6 control-label"><span class="color_ro">* </span>圖片上傳:</label>
                <div class="col-sm-6">
                  <link rel="stylesheet" href="/fastadmin/template/pc/common/control/css/zyUpload.css" type="text/css">
                  <script src="https://cdn.bootcss.com/jquery/1.8.2/jquery.min.js"></script>
                  <style type="text/css">
                      .upload_preview{overflow: inherit;border:0;}
                      .upload_main{border: 0;}
                      .upload_drag_area{opacity: 0;}
                      .upload_btn {background: none repeat scroll 0 0 #ffa800;}
                      .add_upload{background: #ececec;display:none}
                      .status_bar{display:none}
                      .add_imgBox{width: 196px !important;height: 38px !important;border: 0;}
                      .add_imgBox:hover{border:0}
                      .upload_box{margin:0}
                      .uploadImg{width:250px !important}
                  </style>
                  <!-- 引用核心层插件 -->
                  <script src="/fastadmin/template/pc/common/js/zyFile.js"></script>
                  <!-- 引用控制层插件 -->
                  <script src="/fastadmin/template/pc/common/control/js/zyUpload.js"></script>
                  <!-- 引用初始化JS -->

                  <script src="/fastadmin/template/pc/common/js/jq22.js"></script>

        				  <div id="upload" class="upload">
        					  <div class="add_upload1">
        						<a style="height:100px;width:120px;" title="点击添加文件" id="rapidAddImg" class="add_imgBox" href="javascript:void(0)">
        							<div class="uploadImg" style="width:105px">
        								<img class="upload_image" src="/fastadmin/template/pc/common/control/images/add_img.png" style="width:expression(this.width > 105 ? 105px : this.width)">
        							</div>
        						</a>
        					  </div>
        				  </div> 
                  
                   <!-- <div id="upload" class="upload"></div> -->
                </div>
              </div>
              <input name="pic" type="hidden" value="" id="pic">
              <div class="form-group form-group-e no_border">
                <label for="inputPassword3" class="col-md-1 control-label"><span class="color_ro">* </span>简&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;介&nbsp;:</label>
                <div class="col-md-8">

                  <link rel="stylesheet" href="/fastadmin/template/pc/common/kindedit/design/css/trumbowyg.css">
                  <script src="/fastadmin/template/pc/common/kindedit/trumbowyg.js"></script>
                  <script src="/fastadmin/template/pc/common/kindedit/plugins/base64/trumbowyg.base64.js"></script>
                  <style type="text/css">.trumbowyg-box, .editor{margin:0;}</style>
                  <div id="odiv" style="display:none;position:absolute;z-index:100;">
                     <img src="/fastadmin/template/pc/common/kindedit/pic/sx.png" title="缩小" border="0" alt="缩小" onclick="sub(-1);"/>
                      <img src="/fastadmin/template/pc/common/kindedit/pic/fd.png" title="放大" border="0" alt="放大" onclick="sub(1)"/>
                      <img src="/fastadmin/template/pc/common/kindedit/pic/cz.png" title="重置" border="0" alt="重置" onclick="sub(0)"/>
                      <img src="/fastadmin/template/pc/common/kindedit/pic/sc.png" title="删除" border="0" alt="删除" onclick="del();odiv.style.display='none';"/>
                  </div>
                  <div  onmousedown="show_element(event)" style="clear:both" id="customized-buttonpane" class="editor">111111</div>
               </div>
              </div>
              <div class="form-group" style="padding-top: 20px;">
                  <div class="col-md-1"></div>
                  <div class="col-md-2" style="">
                    <button type="submit" class="btn btn-success btn-success-edit" style="border-radius: 0;">發佈產品</button>
                  </div>
              </div>

            </form> 
        </div>


    </div>


    <script src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.min.js"></script>
    <script src="/fastadmin/template/pc/common/js/scripts.js"></script>

    <script>
      function share(){
          var title = $('#title');
          var pic = $('#pic').val();
          
        // jquery 表单提交
        $("#share-form").ajaxSubmit(function(data) { 
          console.log(data);
          if(data.code == 0){
            $.message({message:data.msg,type:'error'});
          }else{
              $.message(data.msg);
              setTimeout(function () {
                  window.location = data.url
              },2500);
          }

        }); 
        return false; //必须返回false，否则表单会自己再做一次提交操作，并且页面跳转 
      }
    </script>


  </body>

</html>