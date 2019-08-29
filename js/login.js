$(function () {
    $('input').val('');

    // form表单提交
    $('form').submit(function () {

        var username = $('input[name=username]').val();
        var password = $('input[name=password]').val();

        if(!username){
            swal({
                toast: true,
                title: '请输入账号！',
                type: 'error',
                timer: 2000,
                showConfirmButton: false,
            });
            $('input[name=username]').focus();
            return false;
        }
        if(!password){
            swal({
                toast: true,
                title: '请输入密码！',
                type: 'error',
                timer: 2000,
                showConfirmButton: false,
            });
            $('input[name=password]').focus();
            return false;
        }

        var form = $(this);

        $.app.request({
            loading:true,// 是否显示等待遮罩层
            debug: false, // 是否开启调试模式
            type:'post',
            url: location.href,
            data:{username: username, password:password},
            timeout : 60000, //超时时间设置，单位毫秒
            dataType:'json',
            beforeSend:function () {
                form.find('button[type=submit], input[type=submit]').attr('disabled', true);
            },success:function (result) {
                swal({
                    title: result.msg,
                    type: 1 === result.code ? 'success' : 'error',
                    allowOutsideClick: false,
                    timer: 3000
                }).then(function() {
                    if(1 === result.code){
                        if(undefined !== result.url && result.url){
                            top.location.href = 'document.referrer' === result.url ? document.referrer : result.url;
                        }else{
                            top.location.reload();
                        }
                    }
                });
                form.find('button[type=submit], input[type=submit]').attr('disabled', false);
            }
        });

        return false;
    });

});