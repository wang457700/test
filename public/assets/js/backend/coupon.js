define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form) {
    //读取选中的条目
        $.jstree.core.prototype.get_all_checked = function (full) {
            var obj = this.get_selected(), i, j;
            for (i = 0, j = obj.length; i < j; i++) {
                obj = obj.concat(this.get_node(obj[i]).parents);
            }
            obj = $.grep(obj, function (v, i, a) {
                return v != '#';
            });
            obj = obj.filter(function (itm, i, a) {
                return i == a.indexOf(itm);
            });
            return full ? $.map(obj, $.proxy(function (i) {
                return this.get_node(i);
            }, this)) : obj;
        };


    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'coupon/index',
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
                Form.events.cxselect(form);
                Form.events.selectpage(form);
            });


            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'coupon_id',
                sortName: 'coupon_id',
                sortOrder: 'desc',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'coupon_id', title: __('ID'), operate: false},
                        {field: 'coupon_name', title: __('優惠名稱'),operate: false},
                        {field: 'coupon_time_text', title: __('有效期'), operate: false},
                        {field: 'coupon_sn', title: __('優惠碼'), operate: false},
                        {field: 'coupon_content', title: __('優惠內容'), operate: false},
                        {field: 'coupon_num', title: __('剩餘數量'),operate: false},
                        {field: 'user_level_text', title: __('會員類別'), operate: false},
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
                                    name: 'dongjie',
                                    title: __('冻结'),
                                    text:'冻结',
                                    classname: 'btn btn-xs btn-detail btn-magic btn-ajax',
                                    url: 'coupon/status/is/0',
                                    success: function (data, ret) {
                                        $(".btn-refresh").trigger("click");
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        //Layer.alert(ret.msg);
                                        return false;
                                    }
                                },{
                                    name: 'jiedong',
                                    title: __('解冻'),
                                    text:'解冻',
                                    classname: 'btn btn-xs btn-detail btn-magic btn-ajax',
                                    url: 'coupon/status/is/1',
                                    success: function (data, ret) {
                                        $(".btn-refresh").trigger("click");
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
                            formatter: Table.api.formatter.operate,formatter: function (value, row, index) {
                                console.log(row.status);
                                var that = $.extend({}, this);
                                var table = $(that.table).clone(true);
                                if (row.status != 1){
                                    $(table).data("operate-dongjie", null);
                                }
                                if (row.status != 0){
                                    $(table).data("operate-jiedong", null);
                                }
                                that.table = table;
                                console.log($(table).data());
                                return Table.api.formatter.operate.call(that, value, row, index);
                            }},
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        }      ,
        disposable_index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'coupon/index/category/2',
                    add_url: 'coupon/add/category/2',
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
                pk: 'coupon_id',
                sortName: 'coupon_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'coupon_id', title: __('ID'), operate: false},
                        {field: 'coupon_name', title: __('優惠名稱'),operate: false},
                        {field: 'coupon_time_text', title: __('有效期'), operate: false},
                        {field: 'coupon_sn', title: __('優惠碼'), operate: false},
                        {field: 'coupon_content', title: __('優惠內容'), operate: false},
                        {field: 'coupon_num', title: __('剩餘數量'),operate: false},
                        {field: 'user_level_text', title: __('會員類別'), operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    title: __('修改'),
                                    classname: 'btn btn-xs btn-detail btn-dialog',
                                    text:'修改',
                                    url: 'coupon/edit/category/2',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },{
                                    name: 'dongjie',
                                    title: __('冻结'),
                                    text:'冻结',
                                    classname: 'btn btn-xs btn-detail btn-magic btn-ajax',
                                    url: 'coupon/status/is/0',
                                    success: function (data, ret) {
                                        $(".btn-refresh").trigger("click");
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        //Layer.alert(ret.msg);
                                        return false;
                                    }
                                },{
                                    name: 'jiedong',
                                    title: __('解冻'),
                                    text:'解冻',
                                    classname: 'btn btn-xs btn-detail btn-magic btn-ajax',
                                    url: 'coupon/status/is/1',
                                    success: function (data, ret) {
                                        $(".btn-refresh").trigger("click");
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
                            formatter: Table.api.formatter.operate,formatter: function (value, row, index) {
                                console.log(row.status);
                                var that = $.extend({}, this);
                                var table = $(that.table).clone(true);
                                if (row.status != 1){
                                    $(table).data("operate-dongjie", null);
                                }
                                if (row.status != 0){
                                    $(table).data("operate-jiedong", null);
                                }
                                that.table = table;
                                //console.log($(table).data("operate-dongjie"));
                                return Table.api.formatter.operate.call(that, value, row, index);
                            }},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },  
        add: function () {
            Controller.api.bindevent();

        },
        edit: function () {
            Controller.api.bindevent();

        },
        detail: function () {

             // 初始化表格参数配置
            Table.api.init({
                search: false,
                advancedSearch: false,
                pagination: true,
                extend: {
                    "index_url": "coupon/detail/coupon_id/"+ Config.coupon_id,
                    "add_url": "",
                    "edit_url": "",
                    "del_url": "",
                    "multi_url": "",
                }
            });
            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                sortName: 'coupon_id',
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {field: 'order_sn', title: '訂單ID'},
                        {field: 'user_id', title: '用戶ID',operate: false},
                        {field: 'nickname', title: '用戶名', operate: false},
                        {field: 'addtime', title: '使用日期', operate: false},
                        {field: 'all_price', title: '訂單金額', operate: false},
                    ]
                ],
                commonSearch: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);//当内容渲染完成后


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

            


        },
        disposable_edit: function () {

            Controller.api.bindevent();

        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"), null, null, function () {
                    if ($("#treeview").size() > 0) {
                        var r = $("#treeview").jstree("get_all_checked");
                        $("input[name='row[no_product_categoryids]']").val(r.join(','));
                    }
                    return true;
                });
                //渲染权限节点树
                //变更级别后需要重建节点树
                $(document).ready(function(){
                    var pid = 2;
                    var id = $('input[name=coupon_id]').val();
                    if ($(this).val() == id){
                        $("option[value='" + pid + "']", this).prop("selected", true).change();
                        Backend.api.toastr.error(__('Can not change the parent to self'));
                        return false;
                    }
                    $.ajax({
                        url: "coupon/roletree",
                        type: 'post',
                        dataType: 'json',
                        data: {id: id, pid: $(this).val()},
                        success: function (ret) {
                            if (ret.hasOwnProperty("code")) {
                                var data = ret.hasOwnProperty("data") && ret.data != "" ? ret.data : "";
                                if (ret.code === 1) {
                                    //销毁已有的节点树
                                    $("#treeview").jstree("destroy");
                                    Controller.api.rendertree(data);
                                } else {
                                    Backend.api.toastr.error(ret.data);
                                }
                            }
                        }, error: function (e) {
                            Backend.api.toastr.error(e.message);
                        }
                    });
                });
                //全选和展开
                $(document).on("click", "#checkall", function () {
                    $("#treeview").jstree($(this).prop("checked") ? "check_all" : "uncheck_all");
                });
                $(document).on("click", "#expandall", function () {
                    $("#treeview").jstree($(this).prop("checked") ? "open_all" : "close_all");
                });
                $("select[name='row[pid]']").trigger("change");
            },
            rendertree: function (content) {
                $("#treeview")
                    .on('redraw.jstree', function (e) {
                        $(".layer-footer").attr("domrefresh", Math.random());
                    })
                    .jstree({
                        "themes": {"stripes": true},
                        "checkbox": {
                            "keep_selected_style": false,
                        },
                        "types": {
                            "root": {
                                "icon": "fa fa-folder-open",
                            },
                            "menu": {
                                "icon": "fa fa-folder-open",
                            },
                            "file": {
                                "icon": "fa fa-file-o",
                            }
                        },
                        "plugins": ["checkbox", "types"],
                        "core": {
                            'check_callback': true,
                            "data": content
                        }
                    });
            }
        }
    };

    $('.danxuan').eq(0).addClass("on");
    $('.danxuan').each(function(){


        $(this).click(function(){
            console.log($(this).attr('class'));
            $(this).addClass("on").siblings().removeClass("on");
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

    return Controller;
});