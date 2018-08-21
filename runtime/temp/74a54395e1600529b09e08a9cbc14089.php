<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:48:"D:\WWW\yinkanghui\template\pc\user\register.html";i:1534498191;s:53:"D:\WWW\yinkanghui\template\pc\layout\public_head.html";i:1534496269;}*/ ?>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
<link href="https://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<link href="/yinkanghui/template/pc/common/css/style.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/font-awesome/4.6.0/css/font-awesome.min.css" rel="stylesheet">
	<body style="background: url(img/login_bg.jpg) repeat center;background-size: cover;">

	 <div class="container">

       <div class="row">
	        <div class="col-md-5 col-xs-11 login" style="">
				<img src="/yinkanghui/template/pc/common/img/logo-3.png" class="logo3">
	        	<form class="form-horizontal" action="" method="post">

	        		<div class="form-group label1">

                      <input type="text" class="form-control" name="username" value="" id="exampleInputEmail1" placeholder="姓名*">
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

                      <input type="text" class="form-control" name="email" value="" id="exampleInputEmail1" placeholder="電郵*">
                    </div>

                    <div class="form-group label2">

                       <input type="password" class="form-control" name="password" value="" id="inputPassword3" placeholder="密碼*">
                    </div>
                    <div class="form-group label2">

                       <input type="password" class="form-control" name="password2" value="" id="inputPassword3" placeholder="確認密碼*">
                    </div>

					<div class="form-group" style="border: 0;">
						<button type="submit" class="btn btn-success btn-success-edit" style="margin: 1em 0">註    冊</button>
					</div>
					<div class="form-group" style="border: 0;text-align: center;">
						 已是會員<a href="#" class="color_ro" style="margin: 20px;">登陸</a>
					</div>
				</form>



		</div>


	</div>


	 <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
	<script>
		;(function($){$.extend({inputStyle:function(){function check(el,cl){$(el).each(function(){$(this).parent('i').removeClass(cl);var checked=$(this).prop('checked');if(checked){$(this).parent('i').addClass(cl);}})}
		$('input[type="radio"]').on('click',function(){check('input[type="radio"]','radio_bg_check');})
		$('input[type="checkbox"]').on('click',function(){check('input[type="checkbox"]','checkbox_bg_check');})}})})(jQuery)
		$(function(){$.inputStyle();})
	</script>


	</body>
</html>