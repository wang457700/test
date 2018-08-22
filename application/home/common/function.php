<?php


/**
 *  公共函数
 * Created by PhpStorm.
 * User: linjiahong
 * Date: 2018/8/22 0022
 * Time: 下午 4:47
 */


function test(){
    echo 1;
}


/**
 * 根据幻灯片标识获取所有幻灯片
 * @param string $slide 幻灯片标识
 * @return array;
 */
function sp_getslide(){
    return Db::name('slide')->where('id',1)->find();
}
