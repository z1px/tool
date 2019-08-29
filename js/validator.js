// 表单默认验证规则
var fields = {/*验证：规则*/
    username: {//验证input项：验证规则
        message: 'The username is not valid',
        validators: {
            notEmpty: {//非空验证：提示消息
                message: '用户名不能为空'
            },
            stringLength: {
                min: 6,
                max: 30,
                message: '用户名长度必须在6到30之间'
            },
            regexp: {
                regexp: /^[a-zA-Z0-9_\.]+$/,
                message: '用户名由数字字母下划线和.组成'
            }
        }
    },
    password: {
        message:'密码无效',
        validators: {
            notEmpty: {
                message: '密码不能为空'
            },
            stringLength: {
                min: 6,
                max: 30,
                message: '用户名长度必须在6到30之间'
            },
            different: {//不能和用户名相同
                field: 'username',//需要进行比较的input name值
                message: '不能和用户名相同'
            },
            regexp: {
                regexp: /^[a-zA-Z0-9_\.]+$/,
                message: 'The username can only consist of alphabetical, number, dot and underscore'
            }
        }
    },
    pay_pwd: {
        message:'支付密码无效',
        validators: {
            notEmpty: {
                message: '支付密码不能为空'
            },
            stringLength: {
                min: 6,
                max: 30,
                message: '支付密码长度必须在6到30之间'
            },
            regexp: {
                regexp: /^[a-zA-Z0-9_\.]+$/,
                message: '支付密码只能是字母，数字及_组成'
            }
        }
    },
    repassword: {
        message: '密码无效',
        validators: {
            notEmpty: {
                message: '重复密码不能为空'
            },
            stringLength: {
                min: 6,
                max: 30,
                message: '用户名长度必须在6到30之间'
            },
            identical: {//相同
                field: 'password',
                message: '两次密码不一致'
            },
            different: {//不能和用户名相同
                field: 'username',
                message: '不能和用户名相同'
            },
            regexp: {//匹配规则
                regexp: /^[a-zA-Z0-9_\.]+$/,
                message: 'The username can only consist of alphabetical, number, dot and underscore'
            }
        }
    },
    email: {
        validators: {
            notEmpty: {
                message: '邮件不能为空'
            },
            emailAddress: {
                message: '请输入正确的邮件地址如：123@qq.com'
            }
        }
    },
    mobile: {
        message: 'The phone is not valid',
        validators: {
            notEmpty: {
                message: '手机号码不能为空'
            },
            stringLength: {
                min: 11,
                max: 11,
                message: '请输入11位手机号码'
            },
            regexp: {
                regexp: /^1[3|4|5|6|7|8|9]{1}[0-9]{9}$/,
                message: '请输入正确的手机号码'
            }
        }
    },
    remark: {
        validators: {
            callback: {
                callback: function(value, validator) {
                    if (value.length > 100) {
                        return {
                            valid: false,
                            message: '备注信息不能超100字符'
                        }
                    }
                    return true;
                }
            }
        }
    },
    // 示例
    text: {
        validators: {
            notEmpty: {//检测非空,radio也可用
                message: '文本框必须输入'
            },
            stringLength: {//检测长度
                min: 6,
                max: 30,
                message: '长度必须在6-30之间'
            },
            regexp: {//正则验证
                regexp: /^[a-zA-Z0-9_\.]+$/,
                message: '所输入的字符不符要求'
            },
            remote: {//将内容发送至指定页面验证，返回验证结果，比如查询用户名是否存在
                url: '指定页面',
                message: 'The username is not available'
            },
            different: {//与指定文本框比较内容相同
                field: '指定文本框name',
                message: '不能与指定文本框内容相同'
            },
            emailAddress: {//验证email地址
                message: '不是正确的email地址'
            },
            identical: {//与指定控件内容比较是否相同，比如两次密码不一致
                field: 'confirmPassword',//指定控件name
                message: '输入的内容不一致'
            },
            date: {//验证指定的日期格式
                format: 'YYYY/MM/DD',
                message: '日期格式不正确'
            },
            choice: {//check控件选择的数量
                min: 2,
                max: 4,
                message: '必须选择2-4个选项'
            }
        }
    },
    tmid: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '请输入账号'
            },
            regexp: {
                regexp: /^\d+(\.\d+)?$/,
                message: '账号必须是数字'
            }
        }
    },
    titles: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: 'titles不能为空'
            },
            stringLength: {
                min: 0,
                max: 50,
                message: 'titles长度必须小于50'
            }
        }
    },
    title: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: 'title不能为空'
            },
            stringLength: {
                min: 2,
                max: 20,
                message: 'title长度必须在2到20之间'
            }
        }
    },
    brief: {
        message: 'The username is not valid',
        validators: {
            stringLength: {
                min: 0,
                max: 120,
                message: 'brief长度必须小于120'
            }
        }
    },
    keywords: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: 'keywords不能为空'
            },
            stringLength: {
                min: 0,
                max: 120,
                message: 'keywords长度必须小于120'
            }
        }
    },
    description: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: 'description不能为空'
            },
            stringLength: {
                min: 2,
                max: 120,
                message: 'description长度必须小于120'
            }
        }
    },
    weight: {
        message: 'The username is not valid',
        validators: {
            regexp: {
                regexp: /^[0-9\.]+$/,
                message: '权重只能是数字'
            }
        }
    },
    intro: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '介绍不能为空'
            }
        }
    },
    content: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '内容不能为空'
            }
        }
    },
    regulations: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '规定不能为空'
            }
        }
    },
    relation_id: {
        message: 'The username is not valid',
        validators: {
            regexp: {
                regexp: /^[0-9\.]+$/,
                message: '关联id只能是数字'
            }
        }
    },
    click_on: {
        message: 'The username is not valid',
        validators: {
            regexp: {
                regexp: /^[0-9\.]+$/,
                message: '点击数只能是数字'
            }
        }
    },
    zone_id: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '请选择专区'
            }
        }
    },
    gameid: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '请选择游戏'
            }
        }
    },
    pid: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '请选择上级id'
            }
        }
    },
    uid: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '请选择用户'
            }
        }
    },
    group_id: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '请选择用户'
            }
        }
    },
    sort: {
        message: 'The username is not valid',
        validators: {
            regexp: {
                regexp: /^[0-9\.]+$/,
                message: '排序只能是数字'
            }
        }
    },
    module: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '模块不能为空'
            }
        }
    },
    controller: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '控制器不能为空'
            }
        }
    },
    action: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '方法不能为空'
            }
        }
    },
    discount: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '请输入折扣'
            },
            regexp: {
                regexp: /^\d+(\.\d+)?$/,
                message: '折扣必须是数字'
            },
            stringLength: {
                min: 0,
                max: 10,
                message: '折扣区间为0-10'
            }
        }
    },
    first_discount: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '请输入首冲折扣'
            },
            regexp: {
                regexp: /^\d+(\.\d+)?$/,
                message: '首冲折扣必须是数字'
            },
            stringLength: {
                min: 0,
                max: 10,
                message: '首冲折扣区间为0-10'
            }
        }
    },
    refill_discount: {
        message: 'The username is not valid',
        validators: {
            notEmpty: {
                message: '请输入续充折扣'
            },
            regexp: {
                regexp: /^\d+(\.\d+)?$/,
                message: '续充折扣必须是数字'
            },
            stringLength: {
                min: 0,
                max: 10,
                message: '续充折扣区间为0-10'
            }
        }
    },
    first_upper_limit: {
        message: 'The username is not valid',
        validators: {
            regexp: {
                regexp: /^\d+(\.\d+)?$/,
                message: '首冲上限必须是数字'
            }
        }
    },
    begin_time: {
        message: 'The username is not valid',
        trigger: 'keydown blur', // 按键按下和表单失去焦点时触发
        validators: {
            callback: {
            }
        }
    },
    end_time: {
        message: 'The username is not valid',
        trigger: 'keydown blur', // 按键按下和表单失去焦点时触发
        validators: {
            callback: {
            }
        }
    },
    overdue_time: {
        message: 'The username is not valid',
        trigger: 'keydown blur', // 按键按下和表单失去焦点时触发
        validators: {
            callback: {
            }
        }
    },
    starttime: {
        message: 'The username is not valid',
        trigger: 'keydown blur', // 按键按下和表单失去焦点时触发
        validators: {
            callback: {
                message: '开始时间不能为空',
                callback: function (value, validator, $field) {
                    if (!value) {
                        return false;
                    }
                    return true;
                }
            }
        }
    },
    endtime: {
        message: 'The username is not valid',
        trigger: 'keydown blur', // 按键按下和表单失去焦点时触发
        validators: {
            callback: {
                message: '结束时间不能为空',
                callback: function (value, validator, $field) {
                    if (!value) {
                        return false;
                    }
                    return true;
                }
            }
        }
    }
};
