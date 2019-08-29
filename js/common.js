$(function () {

    if(undefined !== $.fn.datetimepicker){
        // 日历渲染
        $('input[name=start_time], input[name=end_time], .datetimepicker').datetimepicker({
            // singleDatePicker: true ,
            format: 'YYYY-MM-DD',//日期格式化，只显示日期
            locale: 'zh-CN',      //中文化
            // maxDate: moment(),//最大日期
            minDate: '2010-01-01', //最小日期
            showClear: true, //启用删除按钮
            // viewMode: 'years' //View Mode属性。设置浏览器选中模式
        });
    }

    if(undefined !== $.fn.selectpicker){
        //搜索框样式渲染
        //render重新渲染
        //refresh重新加载刷新
        $("select:not(.ignore-select)").selectpicker({size: 8, dropupAuto: false});
    }

    // 回车键表单提交
    // $(document).keyup(function(event){
    //     if(13 === event.keyCode){
    //         $('button[type=submit], input[type=submit]').not('.bv-hidden-submit').submit();
    //     }
    // });

});