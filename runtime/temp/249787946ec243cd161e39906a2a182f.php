<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\user\login.html";i:1534752935;s:73:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\layout\public_head.html";i:1534751199;}*/ ?>
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

	 <div class="container">

       <div class="row">
	        <div class="col-md-5 col-xs-11 login" style="">
				<img src="/fastadmin/template/pc/common/img/logo-3.png" class="logo3">
    			<form action="<?php echo url('user/login'); ?>" class="form-horizontal" method="post" enctype="multipart/form-data" onsubmit="return loginform()" id="login-form">
	        		<div class="form-group label1">
                      <label for="exampleInputEmail1"></label>
                      <input type="email" class="form-control" id="email" placeholder="電郵" data-toggle="tooltip" title="請輸入您的電郵" name="email" required>
                    </div>
                    <div class="form-group label2">
                      <label for="exampleInputEmail1"></label>
                       <input type="password" class="form-control" id="password" placeholder="密碼"  data-toggle="tooltip" name="password" title="請輸入您的密碼" required>
                    </div>
				  <div class="form-group" style="border: 0;">
				     <label style="width: 40%;"><input type="checkbox" style="width: 20%;">記住密碼</label>
				     <a href="#" class="color_ro" style="float: right">忘記密碼 找回？</a>
				  </div>
					<div class="form-group" style="border: 0;">

				  		<button type="submit" class="btn btn-success btn-success-edit" style="margin-bottom: 1em"  id="login">登    錄</button>
						<button type="button" class="btn btn-default btn-default-edit" style="margin-bottom: 1em">Facebook 客戶登錄</button>
					 	<button type="button" class="btn btn-default btn-default-edit"><img src="/fastadmin/template/pc/common/img/logo-weixin.png" style="width:  30px;margin-right:  10px;">Wechat 登錄</button>

					</div>
					<div style="text-align: center;">
						<a href="<?php echo url('User/register'); ?>">註冊會員</a>
					</div>
				</form> 
			</div> 
		</div>

		<script src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.min.js"></script>
		<script src="/fastadmin/template/pc/common/js/scripts.js"></script>
		<script type="text/javascript">

			function loginform(){
				
				var email = $('#email');
				var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/; //定义一个正则表达式
				if(!reg.test(email.val())){
					$.message({message:'電郵格式不正确！',type:'warning'});
					email.focus();
					return false;
				} 
			
				// jquery 表单提交
				$("#login-form").ajaxSubmit(function(data) {
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
		</script>
	</body>
</html>