<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:76:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\user\share\share_list.html";i:1534843864;s:73:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\layout\public_head.html";i:1534751199;s:71:"D:\phpStudy\PHPTutorial\WWW\fastadmin\template\pc\common\user_left.html";i:1534750229;}*/ ?>
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
    <body style="background: url(/fastadmin/template/pc/common/img/index_bg.jpg) repeat-y;background-size: cover !important;">

    <div class="container bgcolor_white">
      <div class="alzx-crumbs alzx-crumbs-f1">
          <div class="alzx-con">
          <a class="crumbs-01" href="#">主頁</a>
              <em>&gt;</em>
              <span class="crumbs-02">用戶中心</span>
          </div>

        </div>
        <div class="user_publish_share">

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


                    <div class="row item-single">
                        <div class="col-md-5" style="">文章標題</div>
                        <div class="col-md-2" style="">發佈日期</div>
                        <div class="col-md-2" style="">文章狀態</div>
                        <div class="col-md-3" style="">操作</div>
                    </div>
                   
                    <?php if(is_array($share_list) || $share_list instanceof \think\Collection || $share_list instanceof \think\Paginator): if( count($share_list)==0 ) : echo "" ;else: foreach($share_list as $key=>$vo): ?>
                      <div class="row cart-item-list">
                          <div class="col-md-5" style="">
                             <?php echo $vo['product_name']; ?>
                          </div>
                          <div class="col-md-2 t2" style="">
                                <?php echo $vo['add_date']; ?>
                          </div>
                          <div class="col-md-2" style=""><?php echo $vo['status_text']; ?></div>
                          <div class="col-md-3" style="">
                               <a href="">編輯</a>  |

                              <?php if($vo['status'] == '1'): ?>
                                  <a href="<?php echo url('user/share_operation',array('id'=>$vo['id'],'type'=>'lower')); ?>" class="js-ajax-operation" onclick="return false;">下架</a>  | 
                              <?php endif; if($vo['status'] == '2'): ?>
                                <a href="<?php echo url('user/share_operation',array('id'=>$vo['id'],'type'=>'on')); ?>" class="js-ajax-operation" onclick="return false;">上架</a>  | 
                              <?php endif; ?>
                               

                               <a href="<?php echo url('user/share_delete',array('id'=>$vo['id'])); ?>" class="js-ajax-delete" onclick="return false;">刪除</a>
                          </div>
                      </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
 <!--                     <div class="row cart-item-list">
                          <div class="col-md-5" style="">
                             兒童健康鞋墊 (中童) 35 - DRIKD3N35
                          </div>
                          <div class="col-md-2 t2" style="">
                                2018-07-12

                          </div>
                          <div class="col-md-2 " style="">展示中</div>
                          <div class="col-md-3" style="">
                               <a href="">編輯</a>  |
                               <a href="">下架</a>  |
                               <a href="">刪除</a>
                          </div>
                      </div>-->
                </div>
            </div>
              <?php echo $share_list->render(); ?>
        </div>

    </div>

  <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
	<script src="/fastadmin/template/pc/common/js/scripts.js"></script>

  </body>

</html>