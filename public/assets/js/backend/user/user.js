define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/user/index',
                    multi_url: 'user/user/multi',
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'user.id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true},
                        {field: 'username', title: '用戶名稱', operate: 'LIKE'},
                        {field: 'email', title:'用戶電郵', operate: 'LIKE'},
                        {field: 'mobile', title: '手機號碼', operate: 'LIKE'},
                        {field: 'level', title:'用户类型', visible: true, searchList: {1: __('Male'), 0: __('Female')}, operate:false},
                        {field: 'score', title: '積分', operate: 'BETWEEN', sortable: true},
                        {field: 'join_source', title:'注册類型', operate:false},
                        {field: 'jointime', title:'注册日期', formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'addtabs',
                                    title: __('查看詳情'),
                                    text:'查看詳情',
                                    classname: 'btn btn-xs btn-detail btn-addtabs',
                                    icon: '',
                                    url: 'user/user/detail'
                                },
                                {
                                    name: 'ajax',
                                    title: __('发送Ajax'),
                                    text:'凍結',
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