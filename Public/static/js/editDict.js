layui.config({
    base: rooturl+'Public/static/js/'
}).use(['form'], function() {
    var form = layui.form(),
        layer = layui.layer,
        $ = layui.jquery;

  
    //});

    //监听提交
    form.on('submit(editDict)', function(data) {
        // console.log(data.field);
            
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