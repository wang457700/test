<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\user\user_email_activation.html";i:1534741810;}*/ ?>
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
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">

	</style>
    <title>立刻去郵箱啟動帳號</title></head>
	<body style="background: url(/fastadmin/template/pc/common/img/login_bg.jpg) repeat center;background-size:cover !important;">

	 <div class="container">
       <div class="row">
	        <div class="col-md-5 col-xs-11 login" style="">
				<img src="/fastadmin/template/pc/common/img/logo-3.png" class="logo3">
				<div class="form-group" style="border: 0;padding: 60px 0;font-size: 19px;color: #7dbe43;">
					您的電郵：<span class="color_ro"><?php echo $list['email']; ?></span><br>
					<span class="font16">已成功申請帳號！您現在可以直接登入，或者去郵箱啟動郵件。</span>
				</div>
				<div class="form-group" style="border: 0;">
					<a href="<?php echo url('User/user_email_activation',array('email'=>1)); ?>" class="btn btn-success btn-success-edit" style="margin-bottom: 1em"  id="login">立刻去郵箱啟動帳號</a>
				</div>
		</div>
	</div>
	</body>
</html>