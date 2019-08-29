$(function () {

    if(undefined !== $.fn.bootstrapValidator){

        if(undefined === fields){
            fields = {}
        }

        // 表单验证
        $('form.form-validation').bootstrapValidator('destroy').bootstrapValidator({

            //为每个字段指定通用错误提示语
            message: 'This value is not valid',

            //指定不校验的类型，默认为[':disabled', ':hidden', ':not(:visible)'],可以不设置
            excluded:[':disabled', ':hidden'],//关键配置，表示只对于禁用域不进行验证，其他的表单元素都要验证

            //指定校验时的图标显示，默认是bootstrap风格
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },

            /**
             * 生效规则（三选一）
             * enabled 字段值有变化就触发验证
             * disabled,submitted 当点击提交时验证并展示错误信息
             */
            live: 'enabled',

            //指定提交的按钮，当表单验证不通过时，该按钮为disabled
            submitButtons: 'button[type="submit"]',

            //指定校验字段
            fields: fields
        }).on('success.form.bv', function(e) {
            // Prevent form submission
            e.preventDefault();
            //提交逻辑
        });

        //  重置form表单
        $('form.form-validation input[type=reset], form.form-validation button[type=reset]').on('click', function () {
            $(this).parents('form.form-validation').data('bootstrapValidator').resetForm();
        })
    }

    // form表单提交
    $('form').submit(function () {

        var form = $(this);

        var flag;
        if(undefined !== $.fn.bootstrapValidator && form.hasClass('form-validation')){
            //获取表单对象
            var bootstrapValidator = form.data('bootstrapValidator');

            //手动触发验证
            bootstrapValidator.validate();

            //获取当前表单验证状态，flag = true/false
            flag = bootstrapValidator.isValid();

        }else{
            flag = true;
        }

        if(flag){
            //表单提交的方法、比如ajax提交
            $.app.request({
                loading: true, // 是否显示等待遮罩层
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: function (result) {
                    form.find('button[type=submit], input[type=submit]').attr('disabled', true);
                },
                success: function (result) {
                    swal({
                        title: result.msg,
                        type: 1 === result.code ? 'success' : 'error',
                        allowOutsideClick: false
                    }).then(function() {
                        if(undefined !== result.url && result.url){
                            top.location.href =  'document.referrer' === result.url ? document.referrer : result.url;
                        }else{
                            if(1 === result.code){
                                top.location.reload();
                            }
                        }

                    });
                    form.find('button[type=submit], input[type=submit]').attr('disabled', false);
                }
            });
        }

        return false;
    });

    // 图片上传
    if($('.mange-upload-images').length > 0){

        $('.mange-upload-images').on('click', '.screens', function () {
            var elem = $(this);
            var file = elem.parent('.preview').find('input[type=file]');
            file.click();
        });

        /**
         *上传图片
         **/
        $('.mange-upload-images').on('change', 'input[type=file]', function () {

            var that = $(this);

            $.app.upload({
                loading: true,
                obj: that, // 上传file表单对象
                timeout: 60000, //超时时间设置，单位毫秒
                url: that.parents('.mange-upload-images').data('upload'),
                data: {path: 'bbs'},
                success:function (result) {

                    if(1 === result.code){
                        that.siblings('img').attr('src', result.data.src);
                        that.siblings('input[type=url]').val(result.data.src);
                        that.parent().find('a').show();
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
        });

        /**
         * 删除图片
         */
        $('.mange-upload-images').on('click', '.delImg', function () {

            var that = $(this);

            that.confirmation({
                animation: true, // 动画
                placement: 'top', // 定位  top | bottom | left | right
                title: '确定要删除吗？', // 标题
                btnOkLabel: '确定',
                btnCancelLabel: '取消',
                singleton: true, // 是否只允许出现一个确定框；
                popout: true,// 当用户点击其他地方的时候是否隐藏确定框；
                container: 'body',// 向指定元素追加提示工具。；
                onConfirm: function () {
                    $.app.request({
                        loading: false,
                        type: 'post',
                        url: that.parents('.mange-upload-images').data('del'),
                        data: {img: $('#' + that.data('id') + ' img').attr('src')},
                        dataType: 'json',
                        success:function(result){

                            if(1 === result.code){
                                $('#' + that.data('id') + ' img').attr('src', 'data:image/png;base64,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');
                                $('#' + that.data('id') + ' input[type=url]').val('');
                                $('#' + that.data('id') + ' a').hide();
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
            }).confirmation('toggle');
        });
    }

});