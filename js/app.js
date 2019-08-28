;(function($, window, document, undefined){

    // 插件名称
    var pluginName = 'app';
    // 公共默认配置
    var defaults = {
        init: function () {

        }
    };

    // swal弹窗插件未引入时兼容
    if("undefined" === typeof swal){
        $.extend({
            swal: function (options) {
                if(undefined !== options.title){
                    alert(options.title)
                }else if(undefined !== options.text){
                    alert(options.text)
                }else{
                    alert("swal弹窗插件未引用")
                }
            }
        });
    }else{
        $.extend({
            swal: function (options) {
                swal(options);
            }
        });
    }

    $.extend({
        //sleep等待，单位毫秒
        sleep: function(numberMillis) {
            var now = new Date();
            var exitTime = now.getTime() + numberMillis;
            while (true) {
                now = new Date();
                if (now.getTime() > exitTime)
                    return;
            }
        },
        // 加载遮罩层
        loading:{
            show: function () {
                if($('.loading').length > 0){
                    $('.loading').show();
                }
            },
            hide: function () {
                if($('.loading').length > 0){
                    $('.loading').hide();
                }
            },
            fadeOut: function () {
                if($('.loading').length > 0){
                    $('.loading').fadeOut('slow');
                }
            },
            fadeIn: function () {
                if($('.loading').length > 0){
                    $('.loading').fadeIn('slow');
                }
            },
            auto: function (numberMillis) {
                $.loading.show();
                if(undefined === numberMillis) numberMillis = 300;
                $.sleep(numberMillis);
                $.loading.fadeOut();
            }
        },
        // 时间戳转标准时间
        date: function(timestamp, format) {
            var d;
            if(timestamp){
                d = new Date(timestamp * 1000);
            }else {
                d = new Date()
            }
            if(!format){
                format = "yyyy-MM-dd HH:mm:ss"
            }
            var zeroize = function (value, length)
            {
                if (!length) length = 2;
                value = String(value);
                for (var i = 0, zeros = ''; i < (length - value.length); i++)
                {
                    zeros += '0';
                }
                return zeros + value;
            };

            return format.replace(/"[^"]*"|'[^']*'|\b(?:d{1,4}|m{1,4}|yy(?:yy)?|([hHMstT])\1?|[lLZ])\b/g, function ($0) {
                switch ($0)
                {
                    case 'd': return d.getDate();
                    case 'dd': return zeroize(d.getDate());
                    case 'ddd': return ['Sun', 'Mon', 'Tue', 'Wed', 'Thr', 'Fri', 'Sat'][d.getDay()];
                    case 'dddd': return ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][d.getDay()];
                    case 'M': return d.getMonth() + 1;
                    case 'MM': return zeroize(d.getMonth() + 1);
                    case 'MMM': return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][d.getMonth()];
                    case 'MMMM': return ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][d.getMonth()];
                    case 'yy': return String(d.getFullYear()).substr(2);
                    case 'yyyy': return d.getFullYear();
                    case 'h': return d.getHours() % 12 || 12;
                    case 'hh': return zeroize(d.getHours() % 12 || 12);
                    case 'H': return d.getHours();
                    case 'HH': return zeroize(d.getHours());
                    case 'm': return d.getMinutes();
                    case 'mm': return zeroize(d.getMinutes());
                    case 's': return d.getSeconds();
                    case 'ss': return zeroize(d.getSeconds());
                    case 'l': return zeroize(d.getMilliseconds(), 3);
                    case 'L': var m = d.getMilliseconds();
                        if (m > 99) m = Math.round(m / 10);
                        return zeroize(m);
                    case 'tt': return d.getHours() < 12 ? 'am' : 'pm';
                    case 'TT': return d.getHours() < 12 ? 'AM' : 'PM';
                    case 'Z': return d.toUTCString().match(/[A-Z]+$/);
                    // Return quoted strings with the surrounding quotes removed
                    default: return $0.substr(1, $0.length - 2);
                }
            });
        },
        app:{
            // 获取url中的参数
            parse_url: function(url){
                if(url === undefined){
                    url = location.search;
                }
                //获取url中"?"符后的字串
                var data = new Object();
                if (url.indexOf("?") !== -1) {
                    var str = url.substr(url.indexOf("?") + 1);
                    var params = str.split("&");
                    for(var i = 0; i < params.length; i++) {
                        data[params[i].split("=")[0]] = unescape(params[i].split("=")[1]);
                    }
                }
                return data;
            },
            request: function (options) {
                //请求默认配置文件
                var _default = {
                    loading: false,// 是否显示等待遮罩层
                    debug: false, // 是否开启调试模式
                    type: 'post',
                    url: '',
                    data: {},
                    timeout: 30000, //超时时间设置，单位毫秒
                    dataType: 'json',
                    processData: true,//默认值: true。默认情况下，通过data选项传递进来的数据，如果是一个对象(技术上讲只要不是字符串)，都会处理转化成一个查询字符串，以配合默认内容类型 'application/x-www-form-urlencoded'。如果要发送 DOM 树信息或其它不希望转换的信息，请设置为 false。
                    contentType:'application/x-www-form-urlencoded',//默认值: 'application/x-www-form-urlencoded'。发送信息至服务器时内容编码类型。
                    async: true, // (默认: true) 默认设置下，所有请求均为异步请求
                    cache: true,//设置为 false 将不缓存此页面
                    beforeSend: function (XMLHttpRequest) {

                    },success: function (result, status, xhr) {

                    },complete: function (XMLHttpRequest) {
                        if('timeout' === XMLHttpRequest.statusText){
                            $.swal({
                                toast: true,
                                title: '请求超时...',
                                type: 'warning',
                                timer: 3000,
                                showConfirmButton: false,
                            });
                        }
                    },error: function (e) {
                        $.swal({
                            toast: true,
                            title: '请求错误...',
                            text: e,
                            type: 'error',
                            timer: 3000,
                            showConfirmButton: false,
                        });
                    }
                };

                var _config = $.extend(true, {}, _default, options);

                if(_config.debug) {
                    console.log('config：', _config);
                }

                if(!_config.url){
                    if(_config.debug) {
                        console.log('请求地址不存在...');
                    }
                    $.swal({
                        toast: true,
                        title: '请求地址不存在...',
                        type: 'error',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                    return false;
                }

                $.ajaxSetup({
                    cache: _config.cache//设置为 false 将不缓存此页面
                });

                // ajax提交表单对象
                $.ajax({
                    type: _config.type,
                    url: _config.url,
                    data: _config.data,
                    timeout: _config.timeout, //超时时间设置，单位毫秒
                    dataType: _config.dataType,
                    processData: _config.processData,
                    contentType: _config.contentType,
                    async: _config.async, // (默认: true) 默认设置下，所有请求均为异步请求
                    beforeSend: function (XMLHttpRequest) {

                        if(_config.loading) {
                            $.loading.show();
                        }

                        if(_config.debug) {
                            console.log('请求前回调函数beforeSend：', XMLHttpRequest);
                        }

                        _config.beforeSend();

                    },success: function(result, status, xhr){

                        if(xhr.getResponseHeader('content-type').indexOf('application/json') >= 0) { // 判断返回的是否是json数据
                            if (typeof result === 'string') {
                                result = $.parseJSON(result); // string 转 json
                            }
                            if(_config.loading) {
                                $.loading.hide();
                            }
                        }else {
                            if(_config.loading) {
                                $.loading.fadeOut();
                            }
                        }

                        if(_config.debug) {
                            console.log('请求成功回调函数success：', result, status, xhr);
                        }

                        _config.success(result, status, xhr);

                    },complete: function(XMLHttpRequest){

                        if(_config.loading) {
                            $.loading.hide();
                        }

                        if(_config.debug) {
                            console.log('当请求完成时回调函数complete：', XMLHttpRequest);
                        }

                        if('timeout' === XMLHttpRequest.statusText){
                            console.log('请求超时...');
                        }

                        _config.complete(XMLHttpRequest);

                    },error: function(e){

                        if(_config.loading) {
                            $.loading.hide();
                        }

                        if(_config.debug) {
                            console.log('请求失败error：', e);
                        }

                        _config.error(e);

                    }
                });
            },
            upload: function (options) {
                if(undefined === options.obj){
                    $.swal({
                        toast: true,
                        title: '对象不存在',
                        type: 'error',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                    return false;
                }

                var formData = new FormData();
                var fileData = options.obj[0].files;
                if (fileData) {
                    // 目前仅支持单图上传
                    formData.append(options.obj.attr('name'), fileData[0]);
                }

                // 告诉jQuery不要去处理发送的数据
                options.processData = false;
                // 告诉jQuery不要去设置Content-Type请求头
                options.contentType = false;

                // 附加参数合并
                if ('object' === typeof options.data && options.data) {
                    for (var key in options.data) {
                        formData.append(key, options.data[key]);
                    }
                }

                options.data = formData;

                $.app.request(options);
            },
            createForm: function (url, params, method){
                if(undefined === method) method = 'post';

                var form = '';
                form += '<form action="'+url+'" method="'+method+'" style="display: none;">';
                if(!$.isEmptyObject(params)){
                    $.each(params, function(key, value){
                        form += '<input type="hidden" name="'+key+'" value="'+value+'">';
                    });
                }
                form += '</form>';
                form = $(form);
                $(document.body).append(form);
                form.submit();
            }
        }
    });

    // 定义Plugin的构造函数
    var Plugin = function (element, options) {
        this.element = $(element);
        // 合并配置文件
        this.config = $.extend(true, {}, defaults, options);
    };

    //定义Plugin的方法
    Plugin.prototype = {
        // 初始化
        init: function() {
            this.config.init();
            return this.element;
        },
    };

    //在插件中使用Plugin对象
    $.fn[pluginName] = function(options) {
        //创建插件的实体
        var plugin = new Plugin(this, options);
        //调用其方法
        plugin.init();
        return plugin;
    }

})(jQuery, window, document);