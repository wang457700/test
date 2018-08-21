define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'Myadminjson/coupon_index',
                    add_url: 'coupon/add',
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
                        {field: 'data1', title: __('ID'), operate: false},
                        {field: 'data2', title: __('優惠名稱'),operate: false},
                        {field: 'data3', title: __('有效期'), operate: false},
                        {field: 'data4', title: __('優惠碼'), operate: false},
                        {field: 'data5', title: __('優惠內容'), operate: false},
                        {field: 'data6', title: __('剩餘數量'),operate: false},
                        {field: 'data7', title: __('會員類別'), operate: false},
 
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    title: __('修改'),
                                    classname: 'btn btn-xs btn-detail btn-dialog',
                                      text:'修改',
                                    url: 'coupon/edit',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },{
                                    name: 'ajax',
                                    title: __('发送Ajax'),
                                    text:'冻结',
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
                                    name: 'detail',
                                    title: __('詳情'),
                                    classname: 'btn btn-xs btn-detail',
                                    text:'詳情',
                                    url: 'coupon/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                            ], 
                        formatter: Table.api.formatter.operate},
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        }      , disposable_index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'Myadminjson/coupon_index',
                    add_url: 'coupon/disposable_add',
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
                        {field: 'data1', title: __('ID'), operate: false},
                        {field: 'data2', title: __('優惠名稱'),operate: false},
                        {field: 'data3', title: __('有效期'), operate: false},
                        {field: 'data4', title: __('優惠碼'), operate: false},
                        {field: 'data5', title: __('優惠內容'), operate: false},
                        {field: 'data6', title: __('剩餘數量'),operate: false},
                        {field: 'data7', title: __('會員類別'), operate: false},
 
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    title: __('修改'),
                                    classname: 'btn btn-xs btn-detail btn-dialog',
                                      text:'修改',
                                    url: 'coupon/disposable_edit',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },{
                                    name: 'ajax',
                                    title: __('发送Ajax'),
                                    text:'冻结',
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
                                    name: 'detail',
                                    title: __('詳情'),
                                     text:'詳情',
                                    url: 'coupon/disposable_detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
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
        },detail: function () {

             // 初始化表格参数配置
            Table.api.init({
                search: false,
                advancedSearch: false,
                pagination: true,
                extend: {
                    "index_url": "Myadminjson/coupon_detail",
                    "add_url": "",
                    "edit_url": "",
                    "del_url": "",
                    "multi_url": "",
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {field: 'data1', title: '訂單ID'},
                        {field: 'data2', title: '用戶ID',operate: false},
                        {field: 'data3', title: '用戶名', operate: false},
                        {field: 'data4', title: '使用日期', operate: false},
                        {field: 'data5', title: '訂單金額', operate: false},
                    ]
                ],
                commonSearch: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);//当内容渲染完成后

            // 给上传按钮添加上传成功事件
            $("#plupload-avatar").data("upload-success", function (data) {
                var url = Backend.api.cdnurl(data.url);
                $(".profile-user-img").prop("src", url);
                Toastr.success("上传成功！");
            });
            
            // 给表单绑定事件
            Form.api.bindevent($("#update-form"), function () {
                $("input[name='row[password]']").val('');
                var url = Backend.api.cdnurl($("#c-avatar").val());
                top.window.$(".user-panel .image img,.user-menu > a > img,.user-header > img").prop("src", url);
                return true;
            });

        },
        disposable_add: function () {
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
        disposable_edit: function () {

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