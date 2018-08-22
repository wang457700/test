<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:67:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\news\article.html";i:1534845699;s:73:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\layout\public_head.html";i:1534845439;s:68:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\common\header.html";i:1534845383;s:65:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\common\nav.html";i:1534399127;s:68:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\common\footer.html";i:1534845661;}*/ ?>
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
<!--头部 str-->
    <div class="header">
   <div class="container" style="padding: 5px 0;"> 
    <div class="h_left">


      <a class="color_gr" --blue>Andy</a>
      <a>退出</a>


    </div>
    <div class="h_right">
      <a style="background: url(/fastadmin/template/pc/common/img/hd_in_ico.png) no-repeat left;padding-left: 15px;"></a>
       <a href="<?php echo url('news/index'); ?>">社區服務諮詢</a>
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
  <body style="background: url(/fastadmin/template/pc/common/img/index_bg.jpg) repeat-y;">
 
 
 
   <div class="row top_banner"><img src="/fastadmin/template/pc/common/img/info_top_banner.jpg" class="col-md-12" /></div>
    <div class="container bgcolor_white">
        <ol class="breadcrumb" style="padding:  20px;background-color: #fff;border-bottom: 1px solid #eee;">
            <li><a href="#">主頁</a></li>
            <li><a href="<?php echo url('home/news/index'); ?>">社區服務資訊</a></li>
            <li class="active"><?php echo $data['post_title']; ?></li>
        </ol>
        <div class="info_detail"> 
              <div>
                 <h3><?php echo $data['post_title']; ?></h3>
                 <p>发布时间：<?php echo $data['post_date']; ?></p>
                  <div class="content">
                    <?php echo $data['post_content']; ?>
                  </div>

                  <div class="nextfa"> 
                  
                    <if condition="!empty($prev)">
                        <a href="<?php echo url('home/news/article',array('id'=>$prev['id'])); ?>" class="">上一篇：<?php echo $prev['post_title']; ?></a>
                    </if> 
                    <if condition="!empty($next)"> 
                        <a href="<?php echo url('home/news/article',array('id'=>$next['id'])); ?>" class="">下一篇：<?php echo $next['post_title']; ?></a>
                    </if> 


                    <div class="clearfix"></div> 
                  </div>
              </div>
        </div>
        
    </div> 
 

  
	<div class="cooperation row">

	  <div class="container">
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/fastadmin/template/pc/common/img/footer_tags1.jpg" /></a>            
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/fastadmin/template/pc/common/img/footer_tags1.jpg" /></a>            
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/fastadmin/template/pc/common/img/footer_tags1.jpg" /></a>            
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/fastadmin/template/pc/common/img/footer_tags1.jpg" /></a>            
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/fastadmin/template/pc/common/img/footer_tags1.jpg" /></a>            
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/fastadmin/template/pc/common/img/footer_tags1.jpg" /></a>   
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/fastadmin/template/pc/common/img/footer_tags1.jpg" /></a>   
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/fastadmin/template/pc/common/img/footer_tags1.jpg" /></a>   
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/fastadmin/template/pc/common/img/footer_tags1.jpg" /></a>   
		<a href="" target="_blank" class="col-md-1 col-xs-1"  style="border: 0" ><img src="/fastadmin/template/pc/common/img/footer_tags1.jpg" /></a>

	  </div>     
	</div>
	<div class="footer"> 
 
       <div class="container" style="padding:2em  0 ">
          <div class="row footer-top"> 
                <div class="col-sm-2">
                  <h4>關於營康薈</h4>
                  <ul class="list-unstyled">
                    <li>「營康薈」是「香港復康會」屬下的社會企業，於2013年9月正式成立，現於油塘大本型商場設立第一家。除了基本的有機食品及健康產品外，我們更有提供不同的獨特食療組合及專業儀器發售，讓大眾有更多選擇。</li> 
                  </ul>
                </div>
                <div class="col-sm-2">
                  <h4>營康薈門市</h4>
                  <ul class="list-unstyled">
                    <li><a href="#" title="油塘大本型商場分店" target="_blank">油塘大本型商場分店</a><br><br></li>
                    <li><a href="#">地址: 九龍油塘高超道38號大本型商場2樓228號舖 (小型零售地帶)</a><br><br></li>
                    <li><a href="#">營業時間: 逢星期一至日 早上11時正 至 晚上 9時30分</a></li>
                  </ul>
                </div>
                <div class="col-sm-1">
                  <h4>職位空缺</h4>
                  <ul class="list-unstyled">
                    <li><a href="#" target="_blank">店務助理</a></li>
                    <li><a href="#" target="_blank">Shop Assistant <br>(全職/兼職)</a></li>
                  </ul>
                </div>
                <div class="col-sm-2">
                  <h4>會員優惠</h4>
                  <ul class="list-unstyled">
                    <li><a href="#" target="_blank">顧客可親臨本店登記成為會員，費用全免，購物即享優惠！</a></li>
                    <li><a href="#" target="_blank">– 獲取購物積分<br>– 其他會員限定獎賞網上會員登記服務即將開放</a></li>
                  </ul>
                </div>
                <div class="col-sm-2 col-sm-2">
                  <h4>聯絡我們</h4>
                  <ul class="list-unstyled">
                    <li><a href="#" target="_blank"><img src="/fastadmin/template/pc/common/img/footer_lx1.jpg" /> 九龍油塘高超道38號大<br>本型商場2樓228號鋪</a></li>
                    <li><a href="#" target="_blank"><img src="/fastadmin/template/pc/common/img/footer_lx2.jpg" /> (852) 2717  2727</a></li>
                    <li><a href="#" target="_blank"><img src="/fastadmin/template/pc/common/img/footer_lx3.jpg" /> (852) 2717  2727</a></li>
                    <li><a href="#" target="_blank" style="font-size:12px;"><img src="/fastadmin/template/pc/common/img/footer_lx4.jpg" /> http://www.livesmart.com.hk</a></li>
                    <li><a href="#" target="_blank"><img src="/fastadmin/template/pc/common/img/footer_lx5.jpg" /> livesmart_enquiry@wa<br>hhong.hk</a></li>
                    <li><a href="#" target="_blank"><img src="/fastadmin/template/pc/common/img/footer_lx6.jpg" /> http://www.facebook.<br>com/livesmart.hksr</a></li>
                  </ul>
                </div>
                <div class="col-sm-3">
                  <h4>商務合作</h4>
                  <ul class="list-unstyled">
                    <li>
                        <div class="form-group">
                          <label for="exampleInputEmail1">聯絡電話：</label>
                          <input type="text" class="form-control" id="exampleInputEmail1" placeholder="">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">聯絡人：</label>
                          <input type="text" class="form-control" id="exampleInputPassword1" placeholder="">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">諮詢內容：</label>
                          <textarea class="form-control" rows="3"></textarea>
                        </div>
                      </li>
                    
                  </ul>
                </div>
          </div> 
        </div>
 
	</div>
	
    
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    

  </body>

</html>