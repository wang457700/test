/*JQuery 限制文本框只能输入数字*/
$(".NumText").keyup(function(){
    $(this).val($(this).val().replace(/[^0-9]/g,''));
}).bind("paste",function(){  //CTR+V事件处理
    $(this).val($(this).val().replace(/[^0-9]/g,''));
}).css("ime-mode", "disabled"); //CSS设置输入法不可用

/*JQuery 限制文本框只能输入数字和小数点*/
$(".NumDecText").keyup(function(){
    $(this).val($(this).val().replace(/[^0-9.]/g,''));
}).bind("paste",function(){  //CTR+V事件处理
    $(this).val($(this).val().replace(/[^0-9.]/g,''));
}).css("ime-mode", "disabled"); //CSS设置输入法不可用
