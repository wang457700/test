define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/index/index',
                    add_url: 'order/index/add',
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
                        {field: 'order_id', title: __('訂單ID'), operate: false},
                        {field: 'order_time', title: __('下單日期'),sortable: true,formatter: Table.api.formatter.datetime,operate: 'RANGE',addclass: 'datetimerange'},
                        {field: 'user_name', title: __('用戶名'), operate: false},
                        {field: 'total_amount', title: __('總金額'), operate: false},
                        {field: 'amount_payable', title: __('應付金額'), operate: false},
                        {field: 'address', title: __('送貨地址'), operate: false},
                        {field: 'freight', title: __('運費'), operate: false},
                        {field: 'donated_amount', title: __('捐款金額'), operate: false},
                        {field: 'payment_type', title: __('支付方式'), operate: false},
                        {field: 'status', title: __('狀態'), operate: false},
 
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'addtabs',
                                    title: __('查看詳情'),
                                    text:'查看詳情',
                                    classname: 'btn btn-xs btn-detail btn-addtabs',
                                    icon: '',
                                    url: 'order/index/detail'
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
    return Controller;
});