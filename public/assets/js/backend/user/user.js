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
                        {field: 'nickname', title: '用戶名稱', operate: 'LIKE'},
                        {field: 'email', title:'用戶電郵', operate: 'LIKE'},
                        {field: 'mobile', title: '手機號碼', operate: 'LIKE'},
                        {field: 'level', title:'用户类型', visible: true, searchList: {1: __('Male'), 0: __('Female')}, operate:false},
                        {field: 'score', title: '積分', operate: false,},
                        {field: 'join_source', title:'注册類型', operate:false},
                        {field: 'reception_romotion_email', title:'是否接收推廣電郵', operate:false},
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
                                    name: 'dongjie',
                                    title: __('凍結'),
                                    text:'凍結',
                                    classname: 'btn btn-xs btn-detail btn-magic btn-ajax',
                                    url: 'user/user/status/is/hidden',
                                    success: function (data, ret) {
                                        $(".btn-refresh").trigger("click");
                                        //Layer.alert(ret.msg + ",返回数据：" + JSON.stringify(data));
                                        //如果需要阻止成功提示，则必须使用return false;
                                        //return false;
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        //Layer.alert(ret.msg);
                                        return false;
                                    }
                                },{
                                    name: 'jiedong',
                                    title: __('解凍'),
                                    text:'解凍',
                                    classname: 'btn btn-xs btn-detail btn-magic btn-ajax',
                                    url: 'user/user/status/is/normal',
                                    success: function (data, ret) {
                                        $(".btn-refresh").trigger("click");
                                    },
                                    error: function (data, ret) {
                                        console.log(data, ret);
                                        //Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                            ], 
                        formatter: Table.api.formatter.operate,formatter: function (value, row, index) {
                            console.log(row.status);
                            var that = $.extend({}, this);
                            var table = $(that.table).clone(true);
                            if (row.status != 'normal'){
                                $(table).data("operate-dongjie", null);
                            }
                            if (row.status != 'hidden'){
                                $(table).data("operate-jiedong", null);
                            }
                            that.table = table;
                            //console.log($(table).data("operate-dongjie"));
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});