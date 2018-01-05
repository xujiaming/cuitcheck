layui.config({
    base: rooturl+'Public/static/js/'
}).use(['form', 'layedit', 'laydate','layer','upload'], function() {
    var form = layui.form(),
        $ = layui.jquery,
        layer = layui.layer,
        layedit = layui.layedit,
        laydate = layui.laydate;

    var index1;
    var index2;

    layui.upload({
        url: updatePhotoUrl,
        ext: 'jpg|png|gif,jpeg',
        elem: '#uerPhoto',
        before:function (input) {
            layer.msg('玩命上传中');
        },
        success: function (res,input) {
            if(res.code == 1){
                layer.msg(res.msg);
                LAY_demo_upload.src = res.data.src;
                window.location.reload();
            }else {
                layer.msg(res.msg);
            }
        }
    });


    form.verify({
        title: function(value) {
            if(value.length < 5 || value == null) {
                return '内容至少得5个字符啊';
            }
        },
        pass: [/(.+){6,12}$/, '密码必须6到12位，且不能出现空格'],
        id:function (value) {
            if(value.length > 5) {
                return '系统异常！';
            }
        },
        content: function(value) {
            layedit.sync(editIndex);
        },
        pass2: function (value) {
            var pass1 = $('#first').val();
            layer.msg(pass1);
            if(pass1 != value){
                return '两次密码不一致！';
            }
        }
    });

    $('#fixInfo').on('click',function () {
        index1 = layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['350px', '400px'], //宽高
            content: $('.window1'),
            end: function (){
                window.location.reload(); //重新加载页面
            }
        });
    });

    $('#fixPass').on('click',function () {
        index2 = layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['350px', '260px'], //宽高
            content: $('.window2'),
            end: function (){
                    //window.location.reload();
            }
        });
    });


    form.on('submit(submit1)', function(data){
        layer.confirm('确认修改？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            layer.msg('已提交！', {icon: 1});

            $.ajax({
                url:updateUserUrl,
                type:"POST",
                data:data.field,
                beforeSend: function(){
                    //
                },
                success:function(data2)
                {
                    if(data2.success){
                        layer.close(index1);
                        layer.msg(data2.msg, {icon: 1});
                    }else {
                        layer.msg(data2.msg, {icon: 2});
                    }
                },
                error: function(){
                    layer.msg('请求服务器超时', {icon: 2});
                }
            });

            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。

        }, function(){

        });
    });

    form.on('submit(submit2)', function(data){
        layer.confirm('确认修改？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            layer.msg('已提交！', {icon: 1});
            //console.log(data.field);
            $.ajax({
                url:updatePassUrl,
                type:"POST",
                data:data.field,
                beforeSend: function(){

                },
                success:function(data2)
                {
                    if(data2.success){
                        layer.close(index2);
                        layer.msg(data2.msg, {icon: 1});
                    }else {
                        layer.msg(data2.msg, {icon: 2});
                    }
                },
                error: function(){
                    layer.msg('系统错误', {icon: 2});
                }
            });
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        }, function(){
            //layer.msg('not ok！', {icon: 2});
        });
    });

});