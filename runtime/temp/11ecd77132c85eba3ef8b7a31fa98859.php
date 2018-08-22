<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:72:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\user\address_list.html";i:1534844911;s:73:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\layout\public_head.html";i:1534751199;s:68:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\common\header.html";i:1534845130;s:65:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\common\nav.html";i:1534399127;s:71:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\common\user_left.html";i:1534750229;}*/ ?>
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
    <body style="background: url(/fastadmin/template/pc/common/img/index_bg.jpg) repeat-y;"> 
<!--头部 str-->
    <div class="header">
   <div class="container" style="padding: 5px 0;"> 
    <div class="h_left">


      <a class="color_gr" --blue>Andy</a>
      <a>退出</a>


    </div>
    <div class="h_right">
      <a style="background: url(/fastadmin/template/pc/common/img/hd_in_ico.png) no-repeat left;padding-left: 15px;"></a>
       <a href="<?php echo url('news/list'); ?>">社區服務諮詢</a>
       <a href="#">共亯平臺</a>
    </div>
    <div class="clear"></div>

    </div>
    <div style="background: #fff;padding: 20px 0;"> 
      <div class="container p_all_0" > 
	<style>
	
	</style>
        <div class="header-d">
	  
            <a class="phone">訂購熱線：2534-3534  /  51096509</a>
            <div class="header-search">
              <form action="#" method="post">
                <input type="search" name="Search" placeholder="輸入商品關鍵字/商品型號" required="">
                <a class="color_ro">成人紙尿褲</a>
                <a class="color_99">電療機</a>
                <a class="color_ro">健康鞋墊</a>
                <a class="color_99">專業運動鞋</a>
                <button type="submit" class="btn btn-default" aria-label="Left Align">搜索</button></form>
            </div>
            <a class="shopping">我的购物车<span class="color_gr">0</span></a> 
            <div class="logo-2">
              <img src="/fastadmin/template/pc/common/img/logo-2.png">
            </div>  
        </div>
      </div>
    </div>
  </div> 
<!--头部 end-->
<!-- 导航 str-->  
  <div class="nav_menu">
      <div class="nav">
        <div class="list" id="navlist">
          <ul id="navfouce">
            <li class="active">
              <a href="/">主頁</a></li>
            <li>
              <a href="/">康復產品</a></li>
            <li>
              <a href="/">健康及有機產品</a></li>
            <li>
              <a href="/">科技產品</a></li>
            <li>
              <a href="/">節日促銷</a></li>
            <li>
              <a href="/">共享平台</a></li>
            <li>
              <a href="/">最新消息</a></li>
            <li>
              <a href="/">家居家器</a></li>
            <li>
              <a href="/">關於我們</a></li>
            <li>
              <a href="/" style="background:none">會員優惠</a></li>
          </ul>
        </div>
        <div class="box" id="navbox" style="height:0px;opacity:0;overflow:hidden;">
          <div class="cont" style="display:none;">
            <ul class="sublist clearfix">
              <li>
                <h3 class="mcate-item-hd">
                  <span>服饰内衣</span></h3>
                <p class="mcate-item-bd">
                  <a href="">女装</a>
                  <a href="">男装</a>
                  <a href="">内衣</a>
                  <a href="">家居服</a>
                  <a href="">配件</a>
                  <a href="">羽绒</a>
                  <a href="">呢大衣</a>
                  <a href="">毛衣</a></p>
              </li>
              

            </ul>
          </div>
          <div class="cont" style="display:none;">
            <ul class="sublist clearfix">
              <li>
                <h3 class="mcate-item-hd">
                  <span>服饰内衣</span></h3>
                <p class="mcate-item-bd">
                  <a href="">女装</a>
                  <a href="">男装</a>
                  <a href="">内衣</a>
                  <a href="">家居服</a>
                  <a href="">配件</a>
                  <a href="">羽绒</a>
                  <a href="">呢大衣</a>
                  <a href="">毛衣</a></p>
              </li>
              <li>
                <h3 class="mcate-item-hd">
                  <span>鞋 箱包</span></h3>
                <p class="mcate-item-bd">
                  <a href="">女鞋</a>
                  <a href="">男鞋</a>
                  <a href="">箱包</a>
                  <a href="">女包</a>
                  <a href="">男包</a>
                  <a href="">旅行箱</a>
                  <a href="">钱包</a></p>
              </li>
              
            </ul>
          </div>
          <div class="cont" style="display:none;">
            <ul class="sublist clearfix">
              <li>
                <h3 class="mcate-item-hd">
                  <span>服饰内衣</span></h3>
                <p class="mcate-item-bd">
                  <a href="">女装</a>
                  <a href="">男装</a>
                  <a href="">内衣</a>
                  <a href="">家居服</a>
                  <a href="">配件</a>
                  <a href="">羽绒</a>
                  <a href="">呢大衣</a>
                  <a href="">毛衣</a></p>
              </li>
              <li>
                <h3 class="mcate-item-hd">
                  <span>鞋 箱包</span></h3>
                <p class="mcate-item-bd">
                  <a href="">女鞋</a>
                  <a href="">男鞋</a>
                  <a href="">箱包</a>
                  <a href="">女包</a>
                  <a href="">男包</a>
                  <a href="">旅行箱</a>
                  <a href="">钱包</a></p>
              </li>
            </ul>
          </div>
          <div class="cont" style="display:none;">3
            <br />3</div>
          <div class="cont" style="display:none;">4
            <br />3
            <br />4</div>
          <div class="cont" style="display:none;">5</div>
          <div class="cont" style="display:none;">6
            <br />3
            <br />3</div>
          <div class="cont" style="display:none;">7
            <br />3
            <br />3
            <br />3</div></div>
      </div>
    </div>
	 <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script> 
    <script type="text/javascript">
      (function() {

        var time = null;

        var list = $("#navlist");

        var box = $("#navbox");

        var lista = list.find("a");

        for (var i = 0,
        j = lista.length; i < j; i++) {

          if (lista[i].className == "now") {

            var olda = i;

          }

        }

        var box_show = function(hei) {

          box.stop().animate({

            height: hei,

            opacity: 1

          },
          400);

        }

        var box_hide = function() {

          box.stop().animate({

            height: 0,

            opacity: 0

          },
          400);

        }

        lista.hover(function() {

          lista.removeClass("now");

          $(this).addClass("now");

          clearTimeout(time);

          var index = list.find("a").index($(this));

          box.find(".cont").hide().eq(index).show();

          var _height = box.find(".cont").eq(index).height() + 54;

          box_show(_height)

        },
        function() {

          time = setTimeout(function() {

            box.find(".cont").hide();

            box_hide();

          },
          50);

          lista.removeClass("now");

          lista.eq(olda).addClass("now");

        });

        box.find(".cont").hover(function() {

          var _index = box.find(".cont").index($(this));

          lista.removeClass("now");

          lista.eq(_index).addClass("now");

          clearTimeout(time);

          $(this).show();

          var _height = $(this).height() + 54;

          box_show(_height);

        },
        function() {

          time = setTimeout(function() {

            $(this).hide();

            box_hide();

          },
          50);

          lista.removeClass("now");

          lista.eq(olda).addClass("now");

        });

      })(); 

    </script> 
<!--导航 end-->

    <div class="container bgcolor_white">
        <div class="alzx-crumbs alzx-crumbs-f1"> 
          <div class="alzx-con" style="border-bottom: 1px solid #ddd;margin-bottom: 10px;"> 
          <a class="crumbs-01" href="#">主頁</a> 
              <em>&gt;</em> 
              <span class="crumbs-02">用戶中心</span> 
          </div> 
        
        </div>
        <div class="address_list">
              
            <div class="row">
                <div class="col-md-2 left-list"> 
                   
 <div class="user_data">
    <h4><img src="/fastadmin/template/pc/common/img/left_menu_hd.png" style="margin-right: 5px;"/>會員信息</h4>
    <p>
        賬號:  <?php echo $user['username']; ?><br>
        手機:  <?php echo (isset($user['mobile']) && ($user['mobile'] !== '')?$user['mobile']:'未綁定'); ?><br>
        郵箱:<?php echo $user['email']; ?>
    </p>

    <a href="" class="edit">修改信息</a>
</div>
<div class="left_menu">
  <a href="<?php echo url('user/my_order'); ?>"><img src="/fastadmin/template/pc/common/img/left_menu_ico1.png" />我的訂單</a>
  <a href="<?php echo url('user/share_list'); ?>"><img src="/fastadmin/template/pc/common/img/left_menu_ico2.png" />我的共享</a>
  <a href="<?php echo url('user/address_list'); ?>"><img src="/fastadmin/template/pc/common/img/left_menu_ico3.png" />地址管理</a>
</div>
                </div>
                <div class="col-md-10 right-list">
                    <div class="pr_name">收貨地址</div> 
                    <div class="row" style="margin-right: 20px;padding: 0 20px;margin-bottom: 10px;">
                      <h5 class="color_ro bold">新增收貨地址</h5> 
                     
                      <form action="<?php echo !empty($row['id'])?url('user/address_edit'):url('user/address_add'); ?>" class="form-horizontal col-sm-9" method="post" enctype="multipart/form-data" onsubmit="return addressform()" id="address-form">

                        <?php if(isset($row['id'])): ?>
                            <input name="id" type="hidden" value="<?php echo $row['id']; ?>" />
                        <?php endif; ?>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label"><span class="color_ro">＊</span>所在地區</label>
                          <div class="col-sm-3">
                            <select class="form-control select_e" name="country">
                              <option value="香港">香港</option>
                              <option value="香港香港">香港</option>
                              <option value="香港">香港</option>
                            </select>
                          </div>
                          <div class="col-sm-3">
                            <select class="form-control select_e" name="province">
                              <option value="九龍">九龍</option>
                              <option value="九龍九龍">九龍</option>
                              <option value="九龍">九龍</option>
                            </select>
                          </div>
                          <div class="col-sm-3">
                            <select class="form-control select_e" name="city">
                              <option value="九龍">藍田</option>
                              <option value="九龍">藍田</option>
                              <option value="九龍">藍田</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label"><span class="color_ro">*</span>詳細地址</label>
                          <div class="col-sm-9">
                            <textarea class="form-control textarea_e" rows="3" name="address" required oninvalid="setCustomValidity('请输入詳細地址！')" oninput="setCustomValidity('')"><?php echo $row['address']; ?></textarea>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label"><span class="color_ro">*</span>收件人姓名</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control input_e" id="name" placeholder="" name="name" value="<?php echo $row['name']; ?>" required oninvalid="setCustomValidity('请输入收件人姓名！')" oninput="setCustomValidity('')">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label"><span class="color_ro">*</span>手機號碼</label>
                          <div class="col-sm-3">
                            <select class="form-control select_e" name="phone_type">
                              <option value="中國香港+852">中國香港+852</option>
                              <option value="香港">香港</option>
                              <option value="香港">香港</option>
                            </select>
                          </div>
                          <div class="col-sm-6" >
                            <input type="text" class="form-control input_e" id="phone" placeholder="" value="<?php echo $row['phone']; ?>"  name="phone" required oninvalid="setCustomValidity('请输入手機號碼！')" oninput="setCustomValidity('') "onkeyup="value=value.replace(/[^\d]/g,'') " ng-pattern="/[^a-zA-Z]/" maxlength="11">
                          </div>
                          
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label"></label>
                          <div class="col-sm-8">
                            <input type="hidden" style="width: 20%;" name="default" value="0">
                            <label style="width: 40%;"><input type="checkbox" style="width: 20%;" name="default" <?php echo !empty($row['default'])?'checked':''; ?>>設置為默認收貨地址</label>
                          </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2"></div> 
                            <div class="col-md-3" style="">
                              <button type="submit" class="btn btn-success btn-success-edit">保存</button>
                            </div> 
                        </div>
        
                      </form>
                    </div>
                    
                    <div class="col-md-12 right-list">       
            						<div class="color_ro" style="padding: 50px 0 10px 0;">已保存了3條地址，還能新建7條地址</div>
            						<div class="row item-single">
            							<div class="col-md-1" style="">收貨人</div>
            							<div class="col-md-2" style="">所在地區</div>
            							<div class="col-md-4" style="">詳細地址</div>
            							<div class="col-md-2" style="">電話</div>
            							<div class="col-md-1" style="">操作</div>
            						</div>

                      <?php if(is_array($address_list) || $address_list instanceof \think\Collection || $address_list instanceof \think\Paginator): if( count($address_list)==0 ) : echo "" ;else: foreach($address_list as $key=>$vo): ?>
            						<div class="row cart-item-list">
            						  <div class="col-md-1 nowrap">
            							 <?php echo $vo['name']; ?>
            						  </div>
            						  <div class="col-md-2 t2" style="">
            								<?php echo $vo['country']; ?> <?php echo $vo['province']; ?> <?php echo $vo['city']; ?>
            						  </div>
            						  <div class="col-md-4 " style=""><?php echo $vo['address']; ?></div>
            						  <div class="col-md-2" style="">  
            							   <?php echo $vo['phone']; ?>
            						  </div>
            						  <div class="col-md-2" style="width: 11.66666667%;">  
            							   <a href="<?php echo url('user/address_list',array('id'=>$vo['id'])); ?>">修改</a> |
            							   <a href="<?php echo url('user/address_delete',array('id'=>$vo['id'])); ?>" class="js-ajax-delete" onclick="return false;">刪除</a>
            						  </div>
            						  <div class="col-md-1" style="">
                          <?php if($vo['default'] == '1'): ?>
            							   <button type="button" class="btn btn-warning"><?php echo $vo['default_text']; ?></button>
                          <?php endif; ?>
            						  </div>
            						</div>
                      <?php endforeach; endif; else: echo "" ;endif; ?>
            					</div>
 
                </div>
            </div> 

        </div>
    </div> 
	<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
  <script src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.min.js"></script>
  <script src="/fastadmin/template/pc/common/js/scripts.js"></script>
    <script type="text/javascript">
      function addressform(){
        // jquery 表单提交
        $("#address-form").ajaxSubmit(function(data) {
          console.log(data);
          if(data.code == 0){
            $.message({message:data.msg,type:'warning',time:'3000'});
          }else{ 
            $.message(data.msg);
            setTimeout(function () {
                      window.location = data.url
                  },1000);
          }
        });

        return false; //必须返回false，否则表单会自己再做一次提交操作，并且页面跳转
      }
    </script>
     
  </body>

</html>