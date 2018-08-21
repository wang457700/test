define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'Myadminjson/product_index',
                    add_url: 'product/add',
                    edit_url: '',
                    del_url: '',
                    multi_url: 'order/index/multi',
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


            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'user.id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'data1', title: __('產品ID'), operate: false},
                        {field: 'data2', title: __('產品名稱'),operate: false},
                        {field: 'data3', title: __('品牌'), operate: false},
                        {field: 'data4', title: __('產品規格'), operate: false},
                        {field: 'data5', title: __('產品價格'), operate: false},
                        {field: 'data6', title: __('產品圖片'),formatter: Table.api.formatter.image,  operate: false},
                        {field: 'data7', title: __('SKU'), operate: false},
                        {field: 'data8', title: __('庫存'), operate: false},
                        {field: 'data9', title: __('貨品ID'), operate: false},
                        {field: 'data10', title: __('狀態'), operate: false},
 
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    title: __('修改'),
                                    classname: 'btn btn-xs btn-detail btn-dialog',
                                      text:'修改',
                                    url: 'product/edit',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },{
                                    name: 'ajax',
                                    title: __('发送Ajax'),
                                    text:'删除',
                                    classname: 'btn btn-xs btn-detail btn-magic btn-ajax',
                                    url: 'order/index/detail',
                                    success: function (data, ret) {
                                        //Layer.alert(ret.msg + ",返回数据：" + JSON.stringify(data));
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        //Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                                {
                                    name: 'ajax',
                                    title: __('发送Ajax'),
                                    text:'下架',
                                    classname: 'btn btn-xs btn-detail btn-magic btn-ajax',
                                    url: 'order/index/detail',
                                    success: function (data, ret) {
                                        //Layer.alert(ret.msg + ",返回数据：" + JSON.stringify(data));
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        //Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                            ], 
                        formatter: Table.api.formatter.operate},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        category: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'Myadminjson/category_index',
                    add_url: 'category/add',
                    edit_url: 'category/edit',
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
                        {field: 'data1', title:'',addclass:'aaaaaa',data:'1', formatter: function (value, row, index) {
                                return '<span data-id="' + row.id + '">' + value + '</span>';
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
                return true;
            });

            //ajax 从获取输出三级分类
            $(document).on("click", "tr", function (){
                var id = $(this).find("span").data('id'); 
                $('#form-group').html('');
                for (var i=0;i<Math.floor(Math.random()*100);i++){ 

                    $("#form-group").append('<div class="form-group"><div class="col-xs-12 col-sm-8"><input type="text" class="form-control" id="slide_name" name="row[data1]" value="'+$(this).find("span").text()+i+'" data-rule="required;data1"/>');  
                }
            });

        },

        add: function () {
            Controller.api.bindevent();

            
            $(function(){
                $('.danxuan').eq(0).addClass("on");
                 $('.danxuan').each(function(){
                  $(this).click(function(){
                    $(this).addClass("on").siblings().removeClass("on");
                   });
                 });
            });

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
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});