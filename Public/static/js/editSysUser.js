layui.config({
    base: rooturl+'Public/static/js/'
}).use(['form'], function() {
    var form = layui.form(),
        layer = layui.layer,
        $ = layui.jquery;

    //自定义验证规则
    //form.verify({
    //    name: function(value) {
    //        if(value == "") {
    //            return '学院名称不能为空';
    //        }
    //    },
    //    leadername: function(value){
    //        if (value == ""){
    //            return '负责人姓名不能为空'
    //        }
    //    }
    //});
    //window.onload = function(){
    //    form.render();
    //}


    //监听提交
    form.on('submit(demo1)', function(data) {
        //修改状态所对应的值
        if(data.field.status == 'on'){
            data.field.status = "1";
        }else {
            data.field.status = "0";
        }

        layer.confirm('确认提交？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            layer.msg('已提交！', {icon: 1});
            $.ajax({
                url:editurl,
                type:"POST",
                data:data.field,
                beforeSend: function(){
                    //
                },
                success:function(data2)
                {
                    if(data2.success){
                        //layer.close(index1);
                        layer.msg(data2.msg, {time: 1000,icon: 1},function(){
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                    }else {
                        layer.msg(data2.msg, {time: 1000,icon: 2});
                    }
                },
                error: function(){
                    layer.msg('请求服务器超时', {time: 1000,icon: 2});
                }
            });
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        }, function(){

        });

        return false;
    });
});