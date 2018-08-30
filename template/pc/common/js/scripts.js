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

$("#add_image_input").click(function(){

    var id= Math.floor(Math.random()*10000);
    var li = '<div class="col-xs-12 col-sm-1"><div class="input-group"><input id="c-image'+id+'" class="form-control" size="50" name="img_url[]" type="hidden" value=""><div class="input-group-addon no-border no-padding"><span style="position:relative"><button type="button" id="plupload-image'+id+'" class="btn btn-danger plupload" data-input-id="c-image'+id+'" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-image'+id+'" initialized="true" style="position:relative;z-index:1"></button><div id="html5_'+id+'_container" class="moxie-shim moxie-shim-html5" style="position:absolute;top:-22px;left:0;width:60px;height:60px;overflow:hidden;z-index:0"><input id="html5_'+id+'" type="file" style="font-size:999px;opacity:0;position:absolute;top:0;left:0;width:100%;height:100%" accept="image/gif,.gif,image/jpeg,.jpg,.jpeg,.jpe,image/png,.png,image/bmp,.bmp"></div></span></div><span class="msg-box n-right"></span></div><ul class="row list-inline plupload-preview" id="p-image'+id+'" data-template="uploadtpl"></ul></div>';
    $("#images").append(li);
});