define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: '',
                    add_url: 'product/add',
                    edit_url: '',
                    del_url: '',
                    multi_url: 'order/index/multi',
                    import_url: 'product/import',
                    table: 'user',
                }
            });

            var table = $("#table");
            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                var form = $("form", table.$commonsearch);
                $("input[name='title']", form).addClass("selectpage").data("source", "auth/adminlog/selectpage").data("primaryKey", "title").data("field", "title").data("orderBy", "id desc");
                $("input[name='username']", form).addClass("selectpage").data("source", "auth/admin/index").data("primaryKey", "username").data("field", "username").data("orderBy", "id desc");
                Form.events.cxselect(form);
                Form.events.selectpage(form);
            });
            var submitForm = function (ids, layero) {
                var options = table.bootstrapTable('getOptions');
                var columns = [];
                $.each(options.columns[0], function (i, j) {
                    if (j.field && !j.checkbox && j.visible && j.field != 'operate') {
                        columns.push(j.field);
                    }
                });
                var keyword = $('#keyword').val();
                var payment = $('#payment').val();
                var pay_status = $('#pay_status').val();
                $("input[name=keyword]", layero).val(keyword);
                $("input[name=payment]", layero).val(payment);
                $("input[name=pay_status]", layero).val(pay_status);
                $("input[name=ids]", layero).val(ids);
                $("input[name=columns]", layero).val(columns.join(','));
                $("form", layero).submit();
            };

            $(document).on("click", ".btn-export", function () {
                var ids = [];
                $("input[name='ids[]']").each(function(){
                    ids += $(this).val() + ',';
                });
                var page = $('#page').val();
                var all = table.bootstrapTable('getOptions').totalRows;
                Layer.confirm("请选择导出的选项<form action='" + Fast.api.fixurl("order/export") + "' method='post' target='_blank'>" +
                    "<input type='hidden' name='keyword' value='' /><input type='hidden' name='payment' value='' /><input type='hidden' name='pay_status' value='' /><input type='hidden' name='ids' value='' /><input type='hidden' name='filter' ><input type='hidden' name='op'><input type='hidden' name='search'><input type='hidden' name='columns'></form>", {
                    title: '导出数据',
                    btn: ["本页(" + page + "条)", "全部"],
                    success: function (layero, index) {
                        $(".layui-layer-btn a", layero).addClass("layui-layer-btn0");
                    }
                    ,yes: function (index, layero) {
                        submitForm(ids, layero);
                        return false;
                    }
                    ,btn2: function (index, layero) {
                        submitForm("all", layero);
                        return false;
                    }
                })
            });


            $(document).on("click", ".btn-delone", function (e) {

                var url = $(this).data('url');
                e.stopPropagation();
                e.preventDefault();
                var that = this;

                var top = $(that).offset().top - $(window).scrollTop();
                var left = $(that).offset().left - $(window).scrollLeft() - 260;
                if (top + 154 > $(window).height()) {
                    top = top - 154;
                }
                if ($(window).width() < 480) {
                    top = left = undefined;
                }
                Layer.confirm(
                    '你确定删除订单吗?',
                    {icon: 3, title: '温馨提示', offset: [top, left], shadeClose: true},
                    function (index) {
                        var table = $(that).closest('table');
                        var options = table.bootstrapTable('getOptions');

                        var options = {url: url};
                        Fast.api.ajax(options, function (data, ret) {
                           // Fast.events.onAjaxSuccess(ret.msg);
                           window.location.reload();
                        }, function (data, ret) {
                            var error = $(element).data("error") || $.noop;
                            if (typeof error === 'function') {
                                if (false === error.call(element, data, ret)) {
                                    return false;
                                }
                            }
                        });
                        Layer.close(index);
                    }
                );
            });


            // 初始化表格
            table.bootstrapTable({
                search:false,
                showExport:false,
                showToggle:false,
                visible:false,
                showColumns:false,
                commonSearch:false,
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [   {checkbox: true},
                        {field: 'order_id', title:'', operate: false},
                        {field: 'order_sn', title:'', operate: false},
                        {field: 'addtime', title:'', operate: false},
                        {field: 'address', title:'', operate: false},
                        {field: 'payment', title:'', operate: false},
                        {field: 'pay_time', title:'', operate: false},
                        {field: 'pay_status', title:'', operate: false},
                    ]
                ]
            });

            // ,'.btn-delone': function (e, value, row, index) {
            //     e.stopPropagation();
            //     e.preventDefault();
            //     var that = this;
            //     var top = $(that).offset().top - $(window).scrollTop();
            //     var left = $(that).offset().left - $(window).scrollLeft() - 260;
            //     if (top + 154 > $(window).height()) {
            //         top = top - 154;
            //     }
            //     if ($(window).width() < 480) {
            //         top = left = undefined;
            //     }
            //     Layer.confirm(
            //         __('Are you sure you want to delete this item?'),
            //         {icon: 3, title: __('Warning'), offset: [top, left], shadeClose: true},
            //         function (index) {
            //             var table = $(that).closest('table');
            //             var options = table.bootstrapTable('getOptions');
            //             Table.api.multi("del", row[options.pk], table, that);
            //             Layer.close(index);
            //         }
            //     );
            // }
            // 为表格绑定事件
            Table.api.bindevent(table);

        },
        category: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'product/category',
                    sub_url: 'product/category_sub',
                    add_url: 'product/category_add',
                    edit_url: '',
                    del_url: 'category/del',
                    multi_url: 'category/multi',
                    dragsort_url: '',
                    table: 'category',
                }
            });


            var table = $("#table");
            var tableOptions = {
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'weigh',
                pagination: false,
                commonSearch: false,
                columns: [
                    [
                        {field: 'name', title:'',addclass:'aaaaaa',data:'1',align: 'left',formatter: function (value, row, index){
                                return '<span data-id="' + row.id + '">' + value + '</span>';
                            }
                         },
                         {
                            field: 'operate',
                            width: "120px",
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,formatter: function (value, row, index) {
                                 console.log(row.status);
                                 var that = $.extend({}, this);
                                 var table = $(that.table).clone(true);
                                 if (row.id == 15 || row.id == 16|| row.id == 17|| row.id == 63){
                                     $(table).data("operate-del", null);
                                 }
                                 that.table = table;
                                 //console.log($(table).data("operate-dongjie"));
                                 return Table.api.formatter.operate.call(that, value, row, index);
                             }
                        },
                    ]
                ],
            };
            // 初始化表格
            table.bootstrapTable(tableOptions);

            // 为表格绑定事件
            Table.api.bindevent(table);

            //绑定TAB事件
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                // var options = table.bootstrapTable(tableOptions);
                var typeStr = $(this).attr("href").replace('#','');
                var options = table.bootstrapTable('getOptions');
                options.pageNumber = 1;
                options.queryParams = function (params) {
                    // params.filter = JSON.stringify({type: typeStr});
                    params.type = typeStr;

                    return params;
                };
                table.bootstrapTable('refresh', {});
                return false;

            });

            //必须默认触发shown.bs.tab事件
            // $('ul.nav-tabs li.active a[data-toggle="tab"]').trigger("shown.bs.tab");
            // 给表单绑定事件
            Form.api.bindevent($("#edit-form"), function (){
                $("input[name='row[password]']").val('');
                var url = Backend.api.cdnurl($("#c-avatar").val());
                top.window.$(".user-panel .image img,.user-menu > a > img,.user-header > img").prop("src", url);

                var cid = $('#sub_pid').val();
                $('#form-group').html('');
                $.ajax({
                      type: "GET",
                      url:  $.fn.bootstrapTable.defaults.extend.sub_url,
                      data: {cid:cid},
                      success: function(data) {
                           if((data.rows).length !==0){
                            $.each(data.rows,function(index, value) {
                                $("#form-group").append('<div class="form-group"><div class="col-xs-12 col-sm-12">CatID:'+value.id+'<input type="text" class="form-control" id="slide_name" name="row[data]['+value.id+']" value="'+value.name+'" data-rule=""/>');
                            });
                            }else{
                                $("#form-group").append('<div class="form-group"><div class="col-xs-12 col-sm-12">没有找到匹配的记录</div></div>');
                            }
                      }
                });

                return true;
            });

            //ajax 从获取输出三级分类
            $(document).on("click", "tr", function (){
                var cid = $(this).find("span").data('id');
                $(this).addClass("on").siblings().removeClass("on");

                 //记录二级分类的id
                $('#sub_pid').val(cid);
                $('#form-group').html('');
                $.ajax({
                      type: "GET",
                      url:  $.fn.bootstrapTable.defaults.extend.sub_url,
                      data: {cid:cid},
                      success: function(data) {
                           if((data.rows).length !==0){
                            $.each(data.rows,function(index, value) {
                                $("#form-group").append('<div class="form-group"><div class="col-xs-12 col-sm-12"><input type="text" class="form-control" id="slide_name" name="row[data]['+value.id+']" value="'+value.name+'" data-rule=""/>');
                            });
                            }else{
                                $("#form-group").append('<div class="form-group"><div class="col-xs-12 col-sm-12">没有找到匹配的记录</div></div>');
                            }
                      }
                });
            });

            $(document).on("click", "#add_sub", function (){

                var sub_pid = $('#sub_pid').val();
                if(sub_pid !==''){
                    $("#form-group").append('<div class="form-group"><div class="col-xs-12 col-sm-12"><input type="text" class="form-control" id="slide_name" name="row[new][]" value="" data-rule=""/></div></div>');
                }else{

                    alert('请选择二级分类!');
                }
            });
            $('.columns,.search').hide();
            $('#table thead').hide();
        },

        add: function () {
            Controller.api.bindevent();
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    get_category: 'api/index/get_category',
                }
            });

            //选择分类
            $(function(){

                get_category(14,'cat_id','0');  // 14：产品分类
                //一级选择
                $(document).on("change",'#cat_id',function(){
                    var idname = 'cat_id_2';
                    get_category($(this).val(),idname,'0');
                    $('#' + idname).empty().html("<option value='0'>请选择商品分类</option>");
                });

                //二级选择
                $(document).on("change",'#cat_id_2',function(){
                    var idname = 'cat_id_3';
                    get_category($(this).val(),idname,'0');
                    $('#' + idname).empty().html("<option value='0'>请选择商品分类</option>");
                })
            });
        },

        category_add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
            $(function(){

                $('.danxuan').eq(0).addClass("on");
                 $('.danxuan').each(function(){
                  $(this).click(function(){
                    $(this).addClass("on").siblings().removeClass("on");
                   });
                 });
            });

            //选择分类
            $(function(){
                var cat_id_01 = $('#cat_id_01').val();
                var cat_id_02 = $('#cat_id_02').val();
                var cat_id_03 = $('#cat_id_03').val();
                if(cat_id_02){
                    get_category(cat_id_01,'cat_id_2',cat_id_02,0);
                }
                if(cat_id_03){
                    get_category(cat_id_02,'cat_id_3',cat_id_03,0);
                }

                $(document).on("change",'#cat_id',function(){
                    get_category($(this).val(),'cat_id_2','0');
                    $('#cat_id_2').empty().html("<option value='0'>请选择商品分类</option>");
                });

                $(document).on("change",'#cat_id_2',function(){
                    get_category($(this).val(),'cat_id_3','0');
                    $('#cat_id_3').empty().html("<option value='0'>请选择商品分类</option>");
                })
            });

        }, 
            api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };


    $(function(){
        $('.discount input').each(function(){
            $(this).click(function(){
                var type = $(this).val();
                if(type == 3){
                    $('#discount_time').show();
                }else{
                    $('#discount_time').hide();
                }

               // $(this).addClass("on").siblings().removeClass("on");
            });
        });

    });


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


/**
 * 获取多级联动的商品分类
 */

function get_category(id,next,select_id){

    if(id == 0){
        return false;
    }
    $.ajax({
        type : "GET",
        url  : $('#get_category_url').val(),
        data:{parent_id:id},
        dataType:'json',
        success: function(data) {
            if(data.status == 1){
                var html = "<option value='0'>请选择商品分类</option>";
                $.each(data.result,function(index, value) {
                    if(value.id == select_id){
                        var selected = 'selected';
                        html+= "<option value='"+value.id+"' selected>"+value.name+"</option>";
                    }else {
                        html+= "<option value='"+value.id+"'>"+value.name+"</option>";
                    }
                   // html+= "<option value='"+value.id+"' selected>"+value.name+"</option>";
                });
                $('#'+next).empty().append(html).show();
            }
        }
    });
}

    $("input[name='discount_type']").click(function(){
        if($(this).val() == 3){
            $('#discount_time').show();
        }else {
            $('#discount_time').hide();
        }

        $('#no_checked').show();
    });

    $("#no_checked").click(function(){

        $("input[name='discount_type']").each(function(){
            console.log($(this).val());
            $(this).removeAttr('checked');
        });
        $(this).hide();
        $('#discount_time').hide();
    });


    $("input[name=is_inland]").click(function(){
        if( $(this).val() == 1){
            $('#is_inland_tip').text('*已選擇內地專區，請輸入人民幣價格！');
        }else{
            $('#is_inland_tip').text('');
        }
    });
    return Controller;
});