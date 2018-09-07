define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/shared/index',
                    add_url: 'user/shared/add',
                    edit_url: '',
                    del_url: '',
                    multi_url: 'user/shared/multi',
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                orderName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'product_name', title: __('title'), operate: 'LIKE %...%', placeholder: '模糊搜索，*表示任意字符'},
                        {field: 'category_name', title: __('分类'), operate: false},
                        {field: 'product_pic', title: __('封面图'), formatter: Table.api.formatter.image, operate: false},
                        {field: 'user_name', title: __('用戶名'), operate: false},
                        {field: 'user_id', title: __('用戶ID'), operate: false},
                        {field: 'status_text', title: __('狀態'), operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'ajax',
                                    title: __('通过'),
                                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                                    text: __('通过'),
                                    icon: '',
                                    url: 'user/shared/status',
                                    success: function (data, ret) {
                                        //Layer.alert(ret.msg + ",返回数据：" + JSON.stringify(data));
                                        $('.btn-refresh').click();
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        //Layer.alert(ret.msg);
                                        return false;
                                    }
                                },{
                                    name: 'detail',
                                    title: __('查看详细信息'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'user/shared/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                            ], 
                        formatter: Table.api.formatter.operate,formatter: function (value, row, index) {
                            var that = $.extend({}, this);
                            var table = $(that.table).clone(true); 
                            if (row.status != 0)
                                $(table).data("operate-ajax", null);
                            that.table = table;
                             console.log($(table).data());
                            return Table.api.formatter.operate.call(that, value, row, index);
                        }},
                    ]
                ],
                //禁用默认搜索
                search: false,
                searchFormVisible: true,
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
            $(document).on('click', '.btn-callback', function () {
                Fast.api.close($("input[name=callback]").val());
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