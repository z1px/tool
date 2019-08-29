
$(function () {

    // table表格省略号鼠标移动出现提示窗
    $('.table-responsive').on('mouseenter', 'table th, table td', function () {
        // 判断有没有省略点
        if (this.offsetWidth < this.scrollWidth) {

            if(!$(this).attr('data-toggle')){
                $(this).attr('data-toggle', 'tooltip')
                    .attr('data-title', $(this).text())
                    .attr('data-container', 'body')
                    .tooltip('toggle');
            }
        }
    });

    // 下载
    $('a.download').on('click', function () {
        var src = top.location.href;
        if(src.indexOf('?') >= 0){
            src += '&'
        }else{
            src += '?'
        }
        window.top.open(src + 'download=1');
    });

    // form表单提交增加遮罩层等待
    $('form').submit(function () {
        $.loading.show();
        return true;
    });

    // reset重置清空表单和下拉框数据
    $('form input[type=reset], form button[type=reset]').on('click', function () {
        var form = $(this).parents("form");
        form.get(0).reset(); // 重置表单
        form.find("input[type=text]").val(""); // 清空表单
        form.find("select option").attr('selected', false); // 清空下拉框
        if(undefined !== $.fn.selectpicker){
            //搜索框样式重新渲染
            form.find("select").selectpicker('render');
        }
        return false;
    });

    // 分页点击增加遮罩层等待
    $('.pagination a').on('click', function () {
        $.loading.show();
        return true;
    });

    // 数据更新增加遮罩层等待
    $('table td a.update_data').on('click', function () {
        var url = $(this).data("url");
        var task = $(this).data("task");
        var key = $(this).data("key");

        if(!url || !task || !key){
            swal({
                toast: true,
                title: '更新条件异常！',
                type: 'error',
                timer: 2000,
                showConfirmButton: false,
            });
            return false;
        }
        $.app.request({
            loading:true,// 是否显示等待遮罩层
            debug: false, // 是否开启调试模式
            type:'post',
            url: url,
            data:{_task: task, _key:key},
            timeout : 60000, //超时时间设置，单位毫秒
            dataType:'json',
            beforeSend:function () {

            },success:function (result) {
                swal({
                    title: result.msg,
                    type: 1 === result.code ? 'success' : 'error',
                    confirmButtonText: '确定',
                    timer: 3000
                }).then(function() {
                    if(1 === result.code){
                        // top.location.reload();
                    }
                });

            }
        });
        return true;
    })

    // 确认框
    $('table td button.confirm').click(function () {
        var that=$(this);
        var url = that.data('url');
        var title = that.data('title');

        if(!url){
            swal({
                toast: true,
                title: '参数错误',
                type: 'error',
                timer: 2000,
                showConfirmButton: false,
            });
            return false;
        }

        if(!title && that.hasClass('btn-danger')){
            title = '确定要删除？';
        }
        if(!title) title = '确定要执行该操作？';

        that.confirmation({
            animation: true, // 动画
            placement: 'top', // 定位  top | bottom | left | right
            title: title, // 标题
            btnOkLabel: '确定',
            btnCancelLabel: '取消',
            singleton: true, // 是否只允许出现一个确定框；
            popout: true,// 当用户点击其他地方的时候是否隐藏确定框；
            container: 'body',// 向指定元素追加提示工具。；
            onConfirm: function () {
                $.app.request({
                    loading: false,// 是否显示等待遮罩层
                    type: 'post',
                    url: url,
                    success: function (result) {

                        if(1 === result.code){
                            if(that.hasClass('btn-danger')){
                                that.parent('td').parent('tr').remove();
                            }
                        }

                        swal({
                            toast: true,
                            title: result.msg,
                            type: 1 === result.code ? 'success' : 'error',
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(function () {
                            if(0 === result.code){
                                if(undefined !== result.url && result.url){
                                    top.location.href = 'document.referrer' === result.url ? document.referrer : result.url;
                                }
                            }
                        });
                    }
                });
            },
            onCancel: function () {

            }
        }).confirmation('show');
    });

    // checkbox快速切换
    $('table td input[type=checkbox]').click(function () {
        var that = $(this);
        var tr = that.parent('label').parent('td').parent('tr');
        var url = tr.data('url');
        var id = tr.data('id');
        var name = that.data('name');
        var checked = that.prop('checked');

        if(!url || !id || !name){
            swal({
                toast: true,
                title: '参数错误',
                type: 'error',
                timer: 2000,
                showConfirmButton: false,
            });
            that.prop('checked', !checked);
            return false;
        }

        //未选中参数值
        var discheck = that.data('discheck');
        if(undefined === discheck) discheck = 0;

        $.app.request({
            loading: false, // 是否显示等待遮罩层
            url: url,
            data:{id: id, name: name, value: checked ? 1 : discheck},
            success: function (result) {
                if(1 !== result.code){
                    that.prop('checked', !checked);
                }
                swal({
                    toast: true,
                    title: result.msg,
                    type: 1 === result.code ? 'success' : 'error',
                    timer: 2000,
                    showConfirmButton: false,
                }).then(function () {
                    if(0 === result.code){
                        if(undefined !== result.url && result.url){
                            top.location.href = 'document.referrer' === result.url ? document.referrer : result.url;
                        }
                    }
                });
            },
            error: function () {
                that.prop('checked', !checked);
                swal({
                    title: '请求错误！',
                    type: 'error',
                    timer: 2000,
                });
            }
        });
    });

    if(undefined !== $.fn.editable){
        /**
         * table行内编辑
         */
        $('table tr td.editable').editable({
            type: 'text', //编辑框的类型。支持text|textarea|select|date|checklist等
            title: '', //编辑框的标题
            disabled: false, //是否禁用编辑
            emptytext: '', //空值的默认文本
            mode: 'inline', //编辑框的模式：支持popup和inline两种模式，默认是popup
            validate: function (value) { //字段验证
                value = $.trim(value);
                // if (!$.trim(value)) {
                //     return '不能为空';
                // }
                var name = $.trim($(this).data('name'));

                if('mobile' === name){
                    var reg = /^1[3456789]\d{9}$/;
                    if(value && (11 !== value.length || !reg.test(value))){
                        return '手机号格式错误';
                    }
                }
                if('email' === name){
                    var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
                    if(value && (11 !== value.length || !reg.test(value))){
                        return '邮箱格式错误';
                    }
                }
                if('discount' === name||'first_discount'=== name||'refill_discount'=== name){
                    var reg=/^[0-9]+.?[0-9]*/;
                    if(!reg.test(value)){
                        return '格式错误';
                    }
                    if(value<0||value>10){
                        return '折扣区间为0-10';

                    }
                }
                if('weight' === name){
                    var reg= /^[0-9\.]+$/;
                    if(!reg.test(value)){
                        return '权重只能是数字';
                    }
                }
                if('sort' === name){
                    var reg= /^[0-9\.]+$/;
                    if(!reg.test(value)){
                        return '排序只能是数字';
                    }
                }
                if('first_upper_limit' === name){
                    var reg= /^[0-9\.]+$/;
                    if(!reg.test(value)){
                        return '首冲上限只能是数字';
                    }
                }
                if('relation_id' === name){
                    var reg= /^[0-9\.]+$/;
                    if(!reg.test(value)){
                        return '关联id只能是数字';
                    }
                }
                if('tmid' === name){
                    var reg= /^[0-9\.]+$/;
                    if(!reg.test(value)){
                        return '用户只能是数字';
                    }
                }
                if('title' === name){
                    if(!value){
                        return '标题不能为空'
                    }
                    if(value.length<2||value.length>20){
                        return '标题长度为2-20'
                    }
                }
            },
            params: function (params) { //请求ajax前执行的方法，字段处理
                //params的格式是json{name:'',value:'',pk:''}
                //可以改变提交到post请求的值，或url:function(params)中也可以改变
                return {id: $.trim($(this).parent('tr').data('id')), name: $.trim(params.name), value: $.trim(params.value)};
            },
            url: function (params) { // 单独加入url方法，需要去掉data-url属性
                var that = $(this);
                return $.app.request({
                    loading: false,// 是否显示等待遮罩层
                    type: 'post',
                    url: that.parent('tr').data('url'),
                    data: params,
                    dataType: 'json',
                    success: function (result) {
                        if (1 !== result.code) {
                            //更新editable内存对象值，如果不加，那么再次点编辑的时候，输入框显示的值不是转小写字母的值，而是最初输入的原值
                            that.editable('setValue', that.data('value'));
                            //更新页面上的显示值
                            that.html(that.data('value'));
                        }
                        swal({
                            toast: true,
                            title: result.msg,
                            type: 1 === result.code ? 'success' : 'error',
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(function () {
                            if(0 === result.code){
                                if(undefined !== result.url && result.url){
                                    top.location.href = 'document.referrer' === result.url ? document.referrer : result.url;
                                }
                            }
                        });
                    },
                    error: function () {
                        swal({
                            title: '请求错误！',
                            type: 'error',
                            timer: 2000,
                        });
                    }
                });
            }
        });
    }

});