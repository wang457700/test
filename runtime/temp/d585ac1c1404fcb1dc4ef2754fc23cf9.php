<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:80:"D:\phpStudy\PHPTutorial\WWW\fastadmin\test\public\template\pc\product\index.html";i:1538991531;s:85:"D:\phpStudy\PHPTutorial\WWW\fastadmin\test\public\template\pc\layout\public_head.html";i:1539253176;s:80:"D:\phpStudy\PHPTutorial\WWW\fastadmin\test\public\template\pc\common\header.html";i:1540196742;s:77:"D:\phpStudy\PHPTutorial\WWW\fastadmin\test\public\template\pc\common\nav.html";i:1540198018;s:80:"D:\phpStudy\PHPTutorial\WWW\fastadmin\test\public\template\pc\common\script.html";i:1540891502;s:80:"D:\phpStudy\PHPTutorial\WWW\fastadmin\test\public\template\pc\common\footer.html";i:1540797910;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="<?php echo $seo_keywords; ?>"/>
    <meta name="description" content="<?php echo $seo_description; ?>"/>
    <link rel="shortcut icon" href="/public/favicon.ico">
    <!-- Bootstrap CSS -->
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link href="/public/template/pc/common/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/template/pc/common/css/style.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/font-awesome/4.6.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="/public/template/pc/common/js/bootstrap.min.js"></script>
    <title><?php echo !empty($seo_title)?$title.' '.$seo_title:$title; ?></title>
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

<div class="container" id="user_tip" style="display: none">
    <div class="search-tip-bow"></div>
    <div class="search-tip font18" style="padding-top: 40px;padding-bottom: 40px;width: 540px;margin-left: -270px;">
        親愛的顧客,感謝加入成為營康薈”會員，I’營康薈"是香港復康會屬下社企支持殘疾人仕及長期病患者重回就業市場，自力更生。
        <button type="submit" class="btn btn-success pull-right bgcolor_gr border_none" style="margin-top: 20px;width: 120px;line-height: 30px;" onclick="$('.search-tip-bow,.search-tip').hide()">确  定</button>
    </div>
</div>

<div class="header">
   <div class="container" style="padding: 5px 0;"> 
        <div class="h_left">
        <?php
            $user = sp_user_info();
            $user_id = Session('user_id');
            $language = Session('language');
        if($user_id != ''): ?>
            <a href="<?php echo url('user/center'); ?>" class="color_gr"><?php echo $user['nickname']; ?></a>
            <a href="<?php echo url('user/logout'); ?>" id="logout" class="js-ajax-operation" onclick="return false;">退出</a>
        <?php else: ?>
             <a href="<?php echo url('user/login'); ?>">登录</a>
        <?php endif; ?>
        </div>
        <div class="h_right">
          <a href="<?php echo url('index/index'); ?>"style="background: url(/public/template/pc/common/img/hd_in_ico.png) no-repeat left;padding-left: 15px;"></a>
           <a id="language_plus" href="javascript:;" data-language="<?php echo (isset($language) && ($language !== '')?$language:'t'); ?>">简体中文</a>
           <a href="<?php echo url('news/index'); ?>">社區服務諮詢</a>
           <a href="<?php echo url('share/index'); ?>">共享平台</a>
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
              <form action="<?php echo url('product/search'); ?>" method="get">
                <input type="search" name="keyword" placeholder="輸入商品關鍵字/商品型號" required="" value="<?php echo (isset($keyword) && ($keyword !== '')?$keyword:''); ?>">
                <a href="<?php echo url('product/search',array('keyword'=>'成人紙尿褲')); ?>" class="color_ro">成人紙尿褲</a>
                <a href="<?php echo url('product/search',array('keyword'=>'電療機')); ?>" class="color_99">電療機</a>
                <a href="<?php echo url('product/search',array('keyword'=>'健康鞋墊')); ?>" class="color_ro">健康鞋墊</a>
                <a href="<?php echo url('product/search',array('keyword'=>'專業運動鞋')); ?>" class="color_99">專業運動鞋</a>
                <button type="submit" class="btn btn-default" aria-label="Left Align">搜索</button></form>
            </div>
            <a class="shopping" href="<?php echo url('cart/shopping_cart'); ?>">我的购物车<span class="color_gr"><?php echo count_cart_num($user_id); ?></span></a>
            <div class="logo-2">
              <img src="/public/template/pc/common/img/logo-2.png">
            </div>  
        </div>
      </div>
    </div>
  </div>
<!--头部 end-->
<?php
      $category = model('category')->where('pid',14)->order('weigh asc')->select();
?>
<div class="nav_menu">
      <div class="nav">
        <div class="list" id="navlist" style="overflow: hidden;height: 35px;">
          <ul id="navfouce">
            <li>
              <a href="<?php echo url('index/index'); ?>">主頁</a></li>
            <?php if(is_array($category) || $category instanceof \think\Collection || $category instanceof \think\Paginator): if( count($category)==0 ) : echo "" ;else: foreach($category as $key=>$vo): ?>
              <li><a href="<?php echo url('product/index',array('categoryid'=>$vo['id'])); ?>" class="true"><?php echo $vo['name']; ?></a></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <li>
              <a href="<?php echo url('share/index'); ?>">共享平台</a></li>
            <li>
              <a href="<?php echo url('news/index'); ?>">最新消息</a></li>
            <li>
              <a href="<?php echo url('index/page',array('id'=>108)); ?>">關於我們</a></li>
            <li>
              <a href="<?php echo url('index/page',array('id'=>110)); ?>" style="background-image:none">會員優惠</a></li>
          </ul>
        </div>
        <div class="box" id="navbox" style="height:0px;opacity:0;overflow:hidden;">
          <div class="" style="display:none;">

          </div>
          <?php if(is_array($category) || $category instanceof \think\Collection || $category instanceof \think\Paginator): if( count($category)==0 ) : echo "" ;else: foreach($category as $key=>$vo): ?>
            <div class="cont" style="display:none;">
              <ul class="sublist clearfix">
                <?php $category_2 = model('category')->where('pid',$vo['id'])->select(); if(is_array($category_2) || $category_2 instanceof \think\Collection || $category_2 instanceof \think\Paginator): if( count($category_2)==0 ) : echo "" ;else: foreach($category_2 as $key=>$vov): ?>
                  <li>
                    <a href="<?php echo url('product/index',array('categoryid'=>$vov['id'])); ?>"><h3 class="mcate-item-hd">
                      <span><?php echo $vov['name']; ?></span></h3></a>
                    <p class="mcate-item-bd">
                      <?php $category_3 = model('category')->where('pid',$vov['id'])->select(); if(is_array($category_3) || $category_3 instanceof \think\Collection || $category_3 instanceof \think\Paginator): if( count($category_3)==0 ) : echo "" ;else: foreach($category_3 as $key=>$vovv): ?>
                        <a href="<?php echo url('product/index',array('categoryid'=>$vovv['id'])); ?>"><?php echo $vovv['name']; ?></a>
                      <?php endforeach; endif; else: echo "" ;endif; ?>
                    </p>
                  </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </ul>
            </div>
          <?php endforeach; endif; else: echo "" ;endif; ?>
          <div class="" style="display:none;">4
          </div>
          <div class="" style="display:none;">5</div>
          <div class="" style="display:none;">6
          </div>
          <div class="" style="display:none;">7
          </div>
        </div>
      </div>
    </div>
	 <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    var urlstr = location.href;
    //alert(urlstr);
    var urlstatus=false;
    $("#navfouce a").each(function () {
        if ((urlstr + '/').indexOf($(this).attr('href')) > -1&&$(this).attr('href')!='') {
            $(this).addClass('cur'); urlstatus = true;
        } else {
            $(this).removeClass('cur');
        }
    });
    if (!urlstatus) {$("#menu a").eq(0).addClass('cur'); }
    $("#navfouce a").each(function(){
        if ($(this)[0].href == String(window.location) && $(this).attr('href')!="") {
            $(this).parents("li").addClass("active");
        }
    });
</script>
<script type="text/javascript">
      (function() {
        var time = null;
        var list = $("#navlist");
        var box = $("#navbox");
        var lista = list.find("a.true");
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
          var index = list.find("a.true").index($(this));
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
  <body style="background: url(/public/template/pc/common/img/index_bg.jpg) repeat-y;">
    <style>
    .pagination{display: none}
    </style>
    <div class="container bgcolor_white">
      <div class="pr_name">
          <?php if(is_array($getparents) || $getparents instanceof \think\Collection || $getparents instanceof \think\Paginator): if( count($getparents)==0 ) : echo "" ;else: foreach($getparents as $key=>$vo): if($vo['id'] != 14): ?>
                 <?php echo $vo['name']; endif; endforeach; endif; else: echo "" ;endif; ?>
      </div>
      <div class="<?php echo $input['style']=='list'?'product':'product2'; ?>">
        <div class="row" style="padding: 20px 0;">
            <form method="get" action="<?php echo url('product/index'); ?>" id="search">
                <?php
                    $last = 'javascript:';
                    $next = 'javascript:';
                    $url =  url('product/index',array('categoryid'=>$input['categoryid'],'sort'=>$sort));
                    if($product_list->currentPage() != 1 ){
                     $last =$url.'?page='.($product_list->currentPage()-1);
                    }
                    if($product_list->currentPage() < $product_list->lastPage()){
                        $next =$url.'?page='.($product_list->currentPage()+1);
                    }
                ?>
                <input type="hidden" name="categoryid" value="<?php echo $input['categoryid']; ?>">
                <input type="hidden" name="sort" value="<?php echo $sort; ?>">
                <input type="hidden" name="search" value="true">
                <div class="product-top-item col-md-6">
                    <?php if(is_array($sort_array) || $sort_array instanceof \think\Collection || $sort_array instanceof \think\Paginator): if( count($sort_array)==0 ) : echo "" ;else: foreach($sort_array as $key=>$vo): ?>
                      <a href="javascript:;"  data-sort="<?php echo $key; ?>-<?php if(strpos($sort,'desc') !== false): ?>asc<?php else: ?>desc<?php endif; ?>" class="<?php if(strpos($sort,$key) !== false): ?>active<?php endif; ?>"><?php echo $vo; ?></a>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="product-top-right col-md-6">
                    <?php if($input['style'] == 'list'): ?>
                     <a href="<?php echo url('product/index',array('style'=>'grid','categoryid'=>$input['categoryid'])); ?>"><i class="fa fa-list" aria-hidden="true"></i></a>
                    <?php else: ?>
                    <a href="<?php echo url('product/index',array('style'=>'list','categoryid'=>$input['categoryid'])); ?>"><i class="fa fa-th-large" aria-hidden="true"></i></a>
                    <?php endif; ?>
                    共 <span class="color_ro"><?php echo $product_list->total(); ?></span> 件商品
                    <span><i class="color_ro" style="font-style: inherit;"><?php echo $product_list->currentPage(); ?></i>/<?php echo $product_list->lastPage(); ?></span>
                    <a href="<?php echo $last; ?>"  id="last"><i class="fa fa-angle-left angle" aria-hidden="true"></i></a>
                    <a href="<?php echo $next; ?>" id="next"><i class="fa fa-angle-right angle disabled" aria-hidden="true" ></i></a>
                </div>
            </form>
            <?php echo $product_list->render(); ?>
        </div>
          <ul class="row">
              <?php if(is_array($product_list) || $product_list instanceof \think\Collection || $product_list instanceof \think\Paginator): if( count($product_list)==0 ) : echo "" ;else: foreach($product_list as $key=>$vo): if($input['style'] == 'grid'): ?>
                  <li class="col-md-6 col-xs-6">
                    <div class="row">
                        <a href="<?php echo url('product/detail',array('id'=>$vo['product_id'])); ?>">
                            <?php if($vo['cover']): ?>
                                <img src="/public//<?php echo $vo['cover']; ?>" class="img_left col-md-4 auto_img" id="img_<?php echo $vo['product_id']; ?>"/>
                            <?php else: ?>
                                <img src="/public//img/default.jpg" class="img_left col-md-4 auto_img">
                            <?php endif; ?>
                        </a>
                      <div class="d1 col-md-8">
                      <a href="<?php echo url('product/detail',array('id'=>$vo['product_id'])); ?>">
                        <p><?php echo $vo['product_name']; ?></p>
                          <?php if($vo['discount_type'] == 1): ?><p class="color_ro">新品上架</p><?php endif; if($vo['discount_type'] == 2): ?><p class="color_ro">特惠全民享</p><?php endif; if($vo['discount_type'] == 3): ?><p class="color_ro">优惠全民享</p><?php endif; ?>
                      </a>
                        <div style="padding-top: 1.5em;">
                            <span style="font-size: 20px;"><i>$</i>
                                  <?php echo product_price($vo['product_id']); ?>
                            </span>
                            <a class="adds" data-productid="<?php echo $vo['product_id']; ?>">+</a>
                        </div>
                      </div>
                    </div>
                  </li>
              <?php endif; if($input['style'] == 'list'): ?>
                  <li class="col-md-3 col-xs-4">
                      <a href="<?php echo url('product/detail',array('id'=>$vo['product_id'])); ?>">
                          <?php if($vo['cover']): ?>
                          <img src="/public//<?php echo $vo['cover']; ?>" id="img_<?php echo $vo['product_id']; ?>" class="img_left2 auto_img"/>
                          <?php else: ?>
                          <img src="/public/template/pc/common/img/default.jpg"  class="img_left2" id="img_<?php echo $vo['product_id']; ?>"/>
                          <?php endif; ?>
                      </a>
                      <div class="d2">
                          <a href="<?php echo url('product/detail',array('id'=>$vo['product_id'])); ?>"/>
                              <p><?php echo $vo['product_name']; ?></p>
                          </a>
                          <?php if($vo['discount_type'] == 1): ?><p class="color_ro">新品上架</p><?php endif; if($vo['discount_type'] == 2): ?><p class="color_ro">特惠全民享</p><?php endif; if($vo['discount_type'] == 3): ?><p class="color_ro">优惠全民享</p><?php endif; ?>
                          <div>
                              <span style="font-size: 20px;"><i>$</i> <?php echo product_price($vo['product_id']); ?> </span>
                              <a class="adds" data-productid="<?php echo $vo['product_id']; ?>" data-flyimg="true">+</a>
                          </div>
                      </div>
                  </li>
              <?php endif; endforeach; endif; else: echo "" ;endif; if(count($product_list) == 0): ?>
              <div style="text-align:  center;padding:  20px;">没有商品信息</div>
              <?php endif; ?>
          </ul>
        </div>
    </div>
    <script src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.min.js"></script>
    <script src="/public/template/pc/common/js/scripts.js"></script>
    <script src='/public//assets/js/message/messageIE.js'></script>
<script src='/public//assets/js/message/message.js'></script>
<script src='/public//assets/js/message/messageIE.js'></script>
<script src="/public/template/pc/common/js/jquery.cookie.min.js"></script>
<script src="/public/assets/js/language_plus.js"></script>
<script>

    /* 第一次加载 */
    $(document).ready(function() {
        auto_img();
    });
    zh_tran('<?php echo (isset($language) && ($language !== '')?$language:"t"); ?>');
    /* 窗口移动 */
    $(window).resize(function (){
        auto_img();
    });
    $("#language_plus").click(function(){
            if($(this).data('language') == 't'){
                zh_tran('s');
                $(this).data('language','s');
                $(this).text('繁体中文');
            }else{
                zh_tran('t');
                $(this).data('language','t');
                $(this).text('简体中文');
            }
            $.ajax({
                type: "get",
                url: "<?php echo url('index/language'); ?>",
                dataType: 'json',
                success: function(data) {

                }
            });
    });


    function auto_img(){
        $('.auto_img').each(function() {
           //
           //  var width = $(this).width();
           //  var height = $(this).height();
           //
           //  //  $(this).hide();
           //
           // // $(this).prepend("<b>Prepended text</b>. ");
           //
           //
           //

            var width = $(this).width();    // 图片实际宽度
            $(this).css('height',parseInt(width));    // 图片实际宽度


        });
 
    }
    /* 加入购物车 */
    $(".adds,#adds").click(function(){
            var product_id = $(this).data('productid');
            var name = $(this).data('name');
            var flyimg = $(this).data('flyimg');
            var img = $('#img_' + product_id);

            $.ajax({
                type: "POST",
                url: "<?php echo url('cart/checkin_cart'); ?>",
                dataType: 'json',
                data:{product_id:product_id},
                success: function(data) {
                    if (data.code) {
                        $.message(data.msg);
                        $('.shopping span').text(Number(data.total));

                        if(flyimg){
                            var flyElm = img.clone().css('opacity','0.7');
                            flyElm.css({
                                'z-index': 9000,
                                'display': 'block',
                                'position': 'absolute',
                                'b': 'absolute',
                                'top': img.offset().top +'px',
                                'left': img.offset().left +'px',
                                'width':img.width() +'px',
                                'height':img.height() +'px'
                            });
                            $('body').append(flyElm);
                            flyElm.animate({
                                top:$('.shopping').offset().top,
                                left:$('.shopping').offset().left,
                                width:0,
                                height:0,
                                opacity:0,
                            },'slow');
                        }
                    }else{
                        $.message({message:data.msg,type:'warning',time:'3000'});
                    }
                }
            });
    });
    function getBasePath(){
        var obj=window.location;
        var contextPath=obj.pathname.split("/")[1];
        var basePath="/"+contextPath;
        return basePath;
    }

    /* 公共get */
    $(".js-ajax-operation").click(function(){
        var url = $(this).attr('href');
        var location = $(this).data('location');
        $.ajax({
            type: "GET",
            url: url,
            dataType:"json",
            success: function(data) {
                if(data.code == 0){
                    $.message({message:data.msg,type:'warning',time:'3000'});
                }else{
                    $.message(data.msg);
                    setTimeout(function () {
                        if(location == false){
                            return false;
                        }
                        if(data.url){
                            window.location = data.url;
                        }else {
                            window.location.reload()
                        }
                    },500);
                }
            }
        });
    });

    /*删除*/
    $(".js-ajax-delete").click(function(){
        var url = $(this).attr('href');
        if(confirm("確定要删除嗎？")){
            $.ajax({
                type: "GET",
                url: url,
                dataType:"json",
                success: function(data) {
                    if(data.code == 0){
                        $.message({message:data.msg,type:'warning',time:'3000'});
                    }else{
                        $.message(data.msg);
                        setTimeout(function () {
                            window.location.reload()
                        },500);
                    }
                }
            });
        }
    });
</script>

    
	<div class="cooperation row">

	  <div class="container">
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/public/template/pc/common/img/footer_tags1.jpg" /></a>            
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/public/template/pc/common/img/footer_tags2.jpg" /></a>
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/public/template/pc/common/img/footer_tags3.jpg" /></a>
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/public/template/pc/common/img/footer_tags4.jpg" /></a>
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/public/template/pc/common/img/footer_tags5.jpg" /></a>
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/public/template/pc/common/img/footer_tags6.jpg" /></a>
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/public/template/pc/common/img/footer_tags7.jpg" /></a>
		<a href="" target="_blank" class="col-md-1 col-xs-1"><img src="/public/template/pc/common/img/footer_tags8.jpg" /></a>
		<a href="" target="_blank" class="col-md-1 col-xs-1"  style="border: 0"><img src="/public/template/pc/common/img/footer_tags9.jpg" /></a>

	  </div>     
	</div>
	<div class="footer"> 
 
       <div class="container" style="padding:2em  0 ">
          <div class="row footer-top"> 
                <div class="col-md-2  col-xs-5">
                  <h4>關於營康薈</h4>
                  <ul class="list-unstyled">
                    <li>「營康薈」是「香港復康會」屬下的社會企業，於2013年9月正式成立，現於油塘大本型商場設立第一家。除了基本的有機食品及健康產品外，我們更有提供不同的獨特食療組合及專業儀器發售，讓大眾有更多選擇。</li> 
                  </ul>
                </div>
                <div class="col-md-2  col-xs-5">
                  <h4>營康薈門市</h4>
                   <a href="<?php echo url('index/page',array('id'=>113)); ?>" target="_blank">
                      <ul class="list-unstyled">
                        <li>油塘大本型商場分店<br><br></li>
                        <li>地址: 九龍油塘高超道38號大本型商場2樓228號舖 (小型零售地帶)<br><br></li>
                        <li>營業時間: 逢星期一至日 早上11時正 至 晚上 9時30分</li>
                      </ul>
                   </a>
                </div>
                <div class="col-md-1  col-xs-5">
                  <h4>職位空缺</h4>
                  <a href="<?php echo url('index/page',array('id'=>109)); ?>" target="_blank">
                      <ul class="list-unstyled">
                        <li>店務助理</li>
                        <li>Shop Assistant <br>(全職/兼職)</li>
                      </ul>
                  </a>
                </div>
                <div class="col-md-2 col-xs-5">
                  <h4>會員優惠</h4>
                    <a href="<?php echo url('index/page',array('id'=>110)); ?>" target="_blank">
                      <ul class="list-unstyled">
                        <li>顧客可親臨本店登記成為會員，費用全免，購物即享優惠！</li>
                        <li>– 獲取購物積分<br>– 其他會員限定獎賞網上會員登記服務即將開放</li>
                      </ul>
                  </a>
                </div>
                <div class="col-md-2 col-xs-5">
                  <h4>聯絡我們</h4>
                  <ul class="list-unstyled">
                    <li><a href="<?php echo url('index/page',array('id'=>108)); ?>" target="_blank"><img src="/public/template/pc/common/img/footer_lx1.jpg" /> 九龍油塘高超道38號大<br>本型商場2樓228號鋪</a></li>
                    <li><a href="<?php echo url('index/page',array('id'=>108)); ?>" target="_blank"><img src="/public/template/pc/common/img/footer_lx2.jpg" /> (852) 2717  2727</a></li>
                    <li><a href="<?php echo url('index/page',array('id'=>108)); ?>" target="_blank"><img src="/public/template/pc/common/img/footer_lx3.jpg" /> (852) 2717  2727</a></li>
                    <li><a href="http://www.livesmart.com.hk" target="_blank" style="font-size:12px;"><img src="/public/template/pc/common/img/footer_lx4.jpg" /> http://www.livesmart.com.hk</a></li>
                    <li><a href="mailto:livesmart_enquiry@wahhong.hk" target="_blank"><img src="/public/template/pc/common/img/footer_lx5.jpg" /> livesmart_enquiry@wa<br>hhong.hk</a></li>
                    <li><a href="http://www.facebook.com/livesmart.hksr" target="_blank"><img src="/public/template/pc/common/img/footer_lx6.jpg" /> http://www.facebook.<br>com/livesmart.hksr</a></li>
                  </ul>
                </div>
                <div class="col-md-3 messagecss  col-xs-5">
                  <h4>商務合作</h4>
                  <ul class="list-unstyled">
                    <li>
                        <form action="<?php echo url('index/message'); ?>" method="post" enctype="multipart/form-data" onsubmit="return messageform()" id="message-form">
                        <div class="form-group">
                          <label>聯絡電話：</label>
                          <input type="text" class="form-control"  placeholder="" name="phone" required oninvalid="setCustomValidity('請輸入聯絡電話！')" oninput="setCustomValidity('')">
                        </div>
                        <div class="form-group">
                          <label>聯絡人：</label>
                          <input type="text" class="form-control"placeholder="" name="name" required oninvalid="setCustomValidity('請輸入聯絡人！')" oninput="setCustomValidity('')">
                        </div>
                        <div class="form-group">
                          <label>諮詢內容：</label>
                          <textarea class="form-control" rows="3" cols="30" placeholder="" name="content" required oninvalid="setCustomValidity('請輸入咨詢內容！')" oninput="setCustomValidity('')"></textarea>
                            <button class="btn btn-default">
                                發  送
                            </button>
                        </div>
                        </form>
                      </li>
                  </ul>
                </div>
          </div> 
        </div>
	</div>
    <!-- BEGIN ProvideSupport.com Graphics Chat Button Code -->

    <div id="ciKiNo" style="z-index:100;position:fixed"></div>
    <div id="scKiNo" style="display:inline;position:  fixed;right: 0;bottom: 0;"></div>
    <div id="sdKiNo" style="display:none"></div>
    <script type="text/javascript">var seKiNo=document.createElement("script");seKiNo.type="text/javascript";var seKiNos=(location.protocol.indexOf("https")==0?"https":"http")+"://image.providesupport.com/js/11ix1prv4xw9a0jlyk9gu7m2o6/safe-standard.js?ps_h=KiNo&ps_t="+new Date().getTime();setTimeout("seKiNo.src=seKiNos;document.getElementById('sdKiNo').appendChild(seKiNo)",1)</script><noscript><div style="display:inline"><a href="http://www.providesupport.com?messenger=11ix1prv4xw9a0jlyk9gu7m2o6">Chat Support</a></div></noscript>

    <!-- END ProvideSupport.com Graphics Chat Button Code -->

    <?php if(empty(sp_ip_ischina())): ?>

    <script src="https://apis.google.com/js/api:client.js"></script>
    <script>
        $('#logout').click(function(){
            // <?php if($user['platform'] == 'google'): ?>
            //     disconnectUser();
            // <?php endif; ?>
            // <?php if($user['platform'] == 'facebook'): ?>
            //     fbLogoutUser();
            // <?php endif; ?>
        });
        // 谷歌登录
        var googleUser = {};
        var startApp = function() {
            gapi.load('auth2', function(){
                // Retrieve the singleton for the GoogleAuth library and set up the client.
                auth2 = gapi.auth2.init({
                    client_id: '974658843640-ban0u40f04qahk5lkl9kl1sj06cfmvi4.apps.googleusercontent.com', //客户端ID
                    cookiepolicy: 'single_host_origin',
                    scope: 'profile' //可以请求除了默认的'profile' and 'email'之外的数据
                });
               // attachSignin(document.getElementById('customBtn'));
            });
        };

        startApp();
        // // 取消与应用关联的代码
        // function disconnectUser(){
        //     var revokeUrl = 'https://accounts.google.com/o/oauth2/revoke?token=' + gapi.auth.getToken().access_token;
        //     $.ajax({
        //         type: 'GET',
        //         url: revokeUrl,
        //         async: false,
        //         contentType: "application/json",
        //         dataType: 'json',
        //         success: function(nullResponse) {
        //             // 成功以后隐藏登录信息
        //             //alert("退出成功！");
        //         },
        //         error: function(e) {
        //             //alert("取消關聯失敗！請到 https://plus.google.com/apps 手动解除！");
        //             //window.open("https://plus.google.com/apps");
        //         }
        //     });
        // }

        // function fbLogoutUser() {
        //     FB.login(function(response){
        //         if (response && response.status === 'connected') {
        //             FB.logout(function(response) {
        //                 document.location.reload();
        //             });
        //         }
        //     });
        // }

        window.fbAsyncInit = function(){
            FB.init({
                appId      : '1026828720830894',
                cookie     : true,
                xfbml      : true,
                version    : 'v3.1'
            });

            FB.AppEvents.logPageView();
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

    </script>
<?php endif; ?>
    <script type="text/javascript">
        $("#search .product-top-item a").click(function(){
            var sort = $(this).data('sort');
            if(sort){
                $('input[name=sort]').val(sort);
                $('#search').submit();
            }
            return false;
        });
    </script>
  </body>
</html>