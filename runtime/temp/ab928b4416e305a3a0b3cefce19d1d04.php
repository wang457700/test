<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:66:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\user\center.html";i:1534831981;s:73:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\layout\public_head.html";i:1534751199;s:71:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\common\user_left.html";i:1534750229;}*/ ?>
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

    <div class="container bgcolor_white">
      <div class="alzx-crumbs alzx-crumbs-f1">
          <div class="alzx-con">
          <a class="crumbs-01" href="#">主頁</a>
              <em>&gt;</em>
              <span class="crumbs-02">用戶中心</span>
          </div>

        </div>
        <div class="user_center">

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
                    <div class="top_connt">
                       <p class="title"><?php echo $user['username']; ?> ,<span class="color_777777">歡迎回來!</span></p>

                        <div class="row">
                            <div class="col-md-4" style="">
                              <h5>我的積分</h5>
                              <span class="color_ro font24"><?php echo $user['score']; ?></span><br>
                              <span class="font12">(100積分=1元)</span>
                            </div>
                            <div class="col-md-4" style="">
                              <h5>我的捐款</h5>
                              <span class="color_ro font24">260.00</span><br>
							  <span class="font12">(查看消費記錄) </span>
                            </div>
                            <div class="col-md-4" style="border: 0">
                              <h5>會員區別</h5>
                              <span class="color_ro font24"><?php echo $user['level']; ?></span><br>
                             <span class="font12">(升级成高級會員)</span>
                            </div>
                        </div>

                    </div>

                    <div class="pr_name">近期訂單</div>


				<!--订单列表-->
                    <div class="row item-single">
                        <div class="col-md-3" style="">商品信息</div>
                        <div class="col-md-3" style="">收貨地址</div>
                        <div class="col-md-2" style="">價格</div>
                        <div class="col-md-1" style="">數量</div>
                        <div class="col-md-1" style="padding: 0;">物流狀態</div>
                        <div class="col-md-2" style="">實付款</div>
                    </div>

                    <div class="cart-item-list">
                        <div class="row row1">
                              <div class="col-md-10 color_75" style="">
                                 2018-06-28  訂單編號: <span class="color_ro bold">1608766834</span>
                              </div>
                              <div class="col-md-2 t2" style="">
                                    訂單狀態: <span class="color_ro">待付款</span>
                              </div>
                        </div>
                        <table class="table table-bordered row2" style="width: 98%;margin: 0;">
                          <tbody>
                            <tr>
                              <td class="col-md-3 "scope="row">
                                  <img src="/fastadmin/template/pc/common/img/f2_1.jpg" class="left_img"/>
                                  <p>
                								  	<h4 class="bold">高效運動鞋墊</h4>
                									  <span class="color_66">返還積分：</span>
                									  <span class="color_ro bold">688</span>
                								  </p>
                              </td>
                              <td class="col-md-3 ">九龍油塘高超道38號大本型商場2樓228號鋪</td>
                              <td class="col-md-2 color_ro"><span class="bold font16">688.00</span> (HKD)</td>
                              <td class="col-md-1 bold font16">2</td>
                              <td class="col-md-1 ">運輸中</td>
                              <td class="col-md-2 "><span class="bold font16 color_ro">628.00</span> <span class="color_75">(HKD)</span><br>(含運費:<span class="color_ro">15.00</span>HKD)</td>
                            </tr>
                          </tbody>
                        </table>
                          <div class="row row3">
                              <div class="col-md-6 color_75" style="text-align:left">
                                 下單時間: 2018-06-28 10:02:20 支付方式：信用卡
                              </div>
                              <div class="col-md-3 t2 color_75" style="">
                                    訂單總價: <span class="bold font16 color_ro">628.00</span> (HKD)
                              </div>
                              <div class="col-md-3 t2" style="">

                                  <button type="button" class="btn btn-warning">查看訂單</button>
                                  <button type="button" class="btn btn-success">取消訂單</button>
                              </div>
                          </div>
                    </div>

				<!--订单列表-->
                    <div class="row item-single">
                        <div class="col-md-3" style="">商品信息</div>
                        <div class="col-md-3" style="">收貨地址</div>
                        <div class="col-md-2" style="">價格</div>
                        <div class="col-md-1" style="">數量</div>
                        <div class="col-md-1" style="padding: 0;">物流狀態</div>
                        <div class="col-md-2" style="">實付款</div>
                    </div>

                    <div class="cart-item-list">
                        <div class="row row1">
                              <div class="col-md-10 color_75" style="">
                                 2018-06-28  訂單編號: <span class="color_ro bold">1608766834</span>
                              </div>
                              <div class="col-md-2 t2" style="">
                                    訂單狀態: <span class="color_ro">待付款</span>
                              </div>
                        </div>
                        <table class="table table-bordered row2" style="width: 98%;margin: 0;">
                          <tbody>
                            <tr>
                              <td class="col-md-3 "scope="row">
                                  <img src="/fastadmin/template/pc/common/img/f2_1.jpg" class="left_img"/>
                                  <p>
                								  	<h4 class="bold">高效運動鞋墊</h4>
                								  	<span class="color_66">返還積分：</span>
                								  	<span class="color_ro bold">688</span>
                								  </p>
                              </td>
                              <td class="col-md-3 ">九龍油塘高超道38號大本型商場2樓228號鋪</td>
                              <td class="col-md-2 color_ro"><span class="bold font16">688.00</span> (HKD)</td>
                              <td class="col-md-1 bold font16">2</td>
                              <td class="col-md-1 ">運輸中</td>
                              <td class="col-md-2 "><span class="bold font16 color_ro">628.00</span> <span class="color_75">(HKD)</span><br>(含運費:<span class="color_ro">15.00</span>HKD)</td>
                            </tr>
                          </tbody>
                        </table>
                          <div class="row row3">
                              <div class="col-md-6 color_75" style="text-align:left">
                                 下單時間: 2018-06-28 10:02:20 支付方式：信用卡
                              </div>
                              <div class="col-md-3 t2 color_75" style="">
                                    訂單總價: <span class="bold font16 color_ro">628.00</span> (HKD)
                              </div>
                              <div class="col-md-3 t2" style="">

                                  <button type="button" class="btn btn-warning">查看訂單</button>
                                  <button type="button" class="btn btn-success">取消訂單</button>
                              </div>
                          </div>
                    </div>

				<!--订单列表-->





                </div>

            </div>

            <div class="pagination">

                <ul>
                    <li><a href="#">上一頁</a> </li>
                    <li class="active current"><span class="current">1</span> </li>
                    <li><a href="#"> 2</a> </li>
                    <li><a href="#"> 3</a> </li>
                    <li><a href="#"> 4</a> </li>
                    <li><a href="#"> 5</a> </li>
                    <li><a href="#"> 6</a> </li>
                    <li><a href="#"> ...</a> </li>
                    <li><a href="#"> 234</a> </li>
                    <li><a href="#">下一頁</a> </li>
                    <li><a href="#">尾页</a> </li>
                </ul>

            </div>




        </div>

    </div>



	<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">(function() {

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

      })();</script>

  </body>

</html>