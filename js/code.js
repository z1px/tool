//验证码
$(function () {
    var countdown = 60;
    var settime = function() {
        var that = $(".sms_send");
        if (0 === countdown) {
            that.removeClass("disabled").text("获取验证码");
            countdown = 60;
            clearInterval(stopInterval);//停止
        } else {
            that.text("重新获取(" + countdown + ")");
            countdown--;
        }

    };

    //获取验证码
    $("body").on('click', '.sms_send', function () {
        if($(".sms_send.disabled").length>0){
            // swal({
            //     toast: true,
            //     title: '请勿重复获取验证码...',
            //     type: 'error',
            //     timer: 2000,
            //     showConfirmButton: false,
            // });
            return false;
        }
        var that = $(this);

        $.app.request({
            loading: false, // 是否显示等待遮罩层
            type: "post",
            url: "/getMobileCode.do",
            beforeSend: function (result) {
                that.addClass("disabled");
                settime();
                stopInterval = setInterval(settime,1000);//按钮不可以用倒计时
            },
            success: function (result) {
                if(1 !== result.code){
                    countdown = 0;
                    settime();
                    swal({
                        toast: true,
                        title: result.msg,
                        type: 'error',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                }
            }
        });

    });
});