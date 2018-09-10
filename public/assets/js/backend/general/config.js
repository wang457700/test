define(['jquery', 'bootstrap', 'backend', 'table', 'form','template'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'general/config/index',
                    add_url: 'general/config/add',
                    edit_url: 'general/config/edit',
                    del_url: 'general/config/del',
                    multi_url: 'general/config/multi',
                    table: 'config',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {field: 'state', checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'intro', title: __('Intro')},
                        {field: 'group', title: __('Group')},
                        {field: 'type', title: __('Type')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            $("form.edit-form").data("validator-options", {
                display: function (elem) {
                    return $(elem).closest('tr').find("td:first").text();
                }
            });
            Form.api.bindevent($("form.edit-form"));

            //不可见的元素不验证
            $("form#add-form").data("validator-options", {ignore: ':hidden'});
            Form.api.bindevent($("form#add-form"), null, function (ret) {
                location.reload();
            });

            //切换显示隐藏变量字典列表
            $(document).on("change", "form#add-form select[name='row[type]']", function (e) {
                $("#add-content-container").toggleClass("hide", ['select', 'selects', 'checkbox', 'radio'].indexOf($(this).val()) > -1 ? false : true);
            });

            //添加向发件人发送测试邮件按钮和方法
            $('input[name="row[mail_from]"]').parent().next().append('<a class="btn btn-info testmail">' + __('Send a test message') + '</a>');
            $(document).on("click", ".testmail", function () {
                var that = this;
                Layer.prompt({title: __('Please input your email'), formType: 0}, function (value, index) {
                    Backend.api.ajax({
                        url: "general/config/emailtest?receiver=" + value,
                        data: $(that).closest("form").serialize()
                    });
                });

            });
        },
        index_meta: function () {
            Table.api.init({
                extend: {
                    eidt_url: 'general/config/index',
                }
            });


            // 初始化表格参数配置
            var table = $("#table");

            // 为表格绑定事件
            Table.api.bindevent(table);
            $("form.edit-form").data("validator-options", {
                display: function (elem) {
                    return $(elem).closest('tr').find("td:first").text();
                }
            });
            Form.api.bindevent($("form.edit-form"));

            $(document).on("click", "#fastadmin", function () {
                Fast.api.open($.fn.bootstrapTable.defaults.extend.eidt_url, "修改", {
                    callback:function(value){

                    }
                });

            });
        },
        remotearea:function (){
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
                                $("#form-group").append('<div class="form-group"><div class="col-xs-12 col-sm-12"><input type="text" class="form-control" id="slide_name" name="row[data]['+value.id+']" value="'+value.name+'" data-rule=""/>');
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
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };

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


    return Controller;
});