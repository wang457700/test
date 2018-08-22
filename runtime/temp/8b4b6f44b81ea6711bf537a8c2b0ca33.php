<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:68:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\user\register.html";i:1534753415;s:73:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\layout\public_head.html";i:1534751199;}*/ ?>
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
	<body style="background: url(/fastadmin/template/pc/common/img/login_bg.jpg) repeat center;background-size: cover !important;">
	<style type="text/css">
			input[type=radio],input[type=checkbox] {
				width:20px;
				height:20px;
				vertical-align:middle;
				opacity:0
			}
			.input_style {
				background:url(/fastadmin/template/pc/common/img/green.png) no-repeat;
				width:20px;
				height:20px;
				display:inline-block;
				float: left;
				margin-top: 10px;
				margin-right: 10px;
			}
			.radio_bg {
				background-position:-118px 0
			}
			.checkbox_bg {
				background-position:0 0
			}
			.radio_bg_check {
				background-position:-166px 0
			}
			.checkbox_bg_check {
				background-position:-48px 0
			}

	</style>


	 <div class="container">

       <div class="row">
	        <div class="col-md-5 col-xs-11 login" style="">
				<img src="/fastadmin/template/pc/common/img/logo-3.png" class="logo3">

				<form action="<?php echo url('user/register'); ?>" class="form-horizontal" method="post" enctype="multipart/form-data" onsubmit="return register()" id="register-form">

	        		<div class="form-group label1 username">
                      <input type="text" class="form-control" name="username" value="" id="username" placeholder="姓名*" required title="请输入姓名！">
					</div>
					<div style="line-height: 40px;padding: 10px 0;">
						<div style="float: left;color:#999;margin-top: 5px;">性别*</div>

						<label class="radio-inline">
						  <i class="input_style radio_bg radio_bg_check"><input type="radio"  checked="checked"  name="six" value="0"></i>男
						</label>
						<label class="radio-inline">
						  <i class="input_style radio_bg"><input type="radio" name="six" value="1"></i>女
						</label>
                    </div>

	        		<div class="form-group label1">

                      <input type="text" class="form-control email" name="email" value="" id="email" placeholder="電郵*"  title="请输入電郵！" required>
                    </div>

                    <div class="form-group label2">
                       <input type="password" class="form-control password" name="password" value="" id="password" placeholder="密碼*" title="请输入密碼！" required>
                    </div>
                    <div class="form-group label2">

                       <input type="password" class="form-control password" name="password2" value="" id="password2" placeholder="確認密碼*" title="请输入確認密碼！" required>
                    </div>

					<div class="form-group" style="border: 0;">
						<button type="submit" class="btn btn-success btn-success-edit" style="margin: 1em 0" id="button">註    冊</button>
					</div>
					<div class="form-group" style="border: 0;text-align: center;">
						 已是會員<a href="#" class="color_ro" style="margin: 20px;">登陸</a>
					</div>
				</form>
		</div>
	</div>
 	<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
 	<script src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.min.js"></script>
	<script src="/fastadmin/template/pc/common/js/scripts.js"></script>
 <script>
		
		function register(){

			var email = $('#email');
			var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/; //定义一个正则表达式
			if(!reg.test(email.val())){
				$.message({message:'電郵格式不正确！',type:'warning',time:'3000'});
				email.focus();
				return false;
			}

			var password = $('#password');
			var password2 = $('#password2');
			if(password.val() !== password2.val()){
				
				$.message({message:'二次輸入的密碼不一致',type:'warning',time:'3000'});
				password.focus();
				return false;
			}

			// jquery 表单提交
			$("#register-form").ajaxSubmit(function(data) { 
				console.log(data);
				if(data.code == 0){
					$.message({message:data.msg,type:'warning',time:'3000'});
				}else{ 
					$.message(data.msg);
					setTimeout(function () {
	                  window.location = data.url
	              },2500);
				}

			}); 
			return false; //必须返回false，否则表单会自己再做一次提交操作，并且页面跳转 
		}

		;(function($){$.extend({inputStyle:function(){function check(el,cl){$(el).each(function(){$(this).parent('i').removeClass(cl);var checked=$(this).prop('checked');if(checked){$(this).parent('i').addClass(cl);}})}
		$('input[type="radio"]').on('click',function(){check('input[type="radio"]','radio_bg_check');})
		$('input[type="checkbox"]').on('click',function(){check('input[type="checkbox"]','checkbox_bg_check');})}})})(jQuery)
		$(function(){$.inputStyle();})
	</script>


	</body>
</html>