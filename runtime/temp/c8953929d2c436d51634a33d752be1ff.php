<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\user\share\share_success.html";i:1534840261;}*/ ?>
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
    <link rel="stylesheet" type="text/css" href="/fastadmin/template/pc/common/css/build.css">
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style type="text/css">

  </style>
  </head>
  <title>4.3完成發佈</title></head>
	<body style="background: url(/fastadmin/template/pc/common/img/index_bg.jpg) repeat-y;">

		<div class="container shopping_cart" >
			<div class="pr_name">完成發佈</div>
			<div class="order_ok">
			  <div class="row">
				<div class="col-md-4 col-sm-2 col-xs-2" style=""></div>
				<div class="col-md-6 col-sm-8 col-xs-8" style="">
				  <div class="row">
					<div class="col-md-2 col-sm-2 col-xs-2">
					  <img src="/fastadmin/template/pc/common/img/order_ok.jpg" />
					</div>
					<div class="col-md-8 col-sm-8" style="">
					  <h4>已發佈，等待審核~</h4>
					  <p>您發佈的的共享內容“Bremed Tens電療機 2 Channels”已提交後台審核,審核成功后將在共享平台對所有用戶展示</p>
					  <p>審核時間大於24小時，期間不可以修改內容，但可以刪除</p>
					  <p>
						  <a type="button" class="btn btn-success" href="<?php echo url('user/share_list',array('id'=>$info['id'])); ?>">查看文章</a>
						  <a type="button" class="btn btn-warning" href="<?php echo url('user/share_add'); ?>">&nbsp;&nbsp;返回&nbsp;&nbsp;</a>
					  </p>
					</div>
				  </div>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-2" style=""></div>
			  </div>
			</div>
		</div>

		<script type="text/javascript">

		</script>

	</body>
</html>