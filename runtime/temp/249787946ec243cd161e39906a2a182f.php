<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\user\login.html";i:1534494240;s:73:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\layout\public_head.html";i:1534494164;}*/ ?>
<!doctype html>
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
</head>
	<body style="background: url(/fastadmin/template/pc/common/img/login_bg.jpg) repeat center;background-size: cover;">

	 <div class="container">

       <div class="row">
	        <div class="col-md-5 col-xs-11 login" style="">
				<img src="/fastadmin/template/pc/common/img/logo-3.png" class="logo3">
	        	<form class="form-horizontal">

	        		<div class="form-group label1">
                      <label for="exampleInputEmail1"></label>
                      <input type="email" class="form-control" id="email" placeholder="電郵" data-toggle="tooltip" title="請輸入您的電郵">
                    </div>
                    <div class="form-group label2">
                      <label for="exampleInputEmail1"></label>
                       <input type="password" class="form-control" id="password" placeholder="密碼"  data-toggle="tooltip" title="請輸入您的密碼">
                    </div>
				  <div class="form-group" style="border: 0;">
				     <label style="width: 40%;"><input type="checkbox" style="width: 20%;">記住密碼</label>
				     <a href="#" class="color_ro" style="float: right">忘記密碼 找回？</a>
				  </div>
					<div class="form-group" style="border: 0;">

				  		<button type="button" class="btn btn-success btn-success-edit" style="margin-bottom: 1em"  id="login">登    錄</button>

						<button type="button" class="btn btn-default btn-default-edit" style="margin-bottom: 1em">Facebook 客戶登錄</button>
					 	<button type="button" class="btn btn-default btn-default-edit"><img src="/fastadmin/template/pc/common/img/logo-weixin.png" style="width:  30px;margin-right:  10px;">Wechat 登錄</button>

					</div>
					<div style="text-align: center;">
						<a href="<?php echo url('User/register'); ?>">註冊會員</a>
					</div>

				</form>

		</div>


	</div>

	<script type="text/javascript">

		$("#login").click(function(){
		  	var email = $('#email').val();
		  	var password = $('#password').val();
		  	if(email == ''){
		  		$('#email').tooltip('show').focus();
		  		return false
		  	}
		  	if(password == ''){
		  		$('#password').tooltip('show').focus();
		  		return false
		  	}






		});
	</script>




	</body>
</html>