layui.config({
    base: rooturl+'Public/static/js/'
}).use(['tree','form','layer','laypage'], function() {
        var $ = layui.jquery,
                layer = layui.layer,
                laypage = layui.laypage,
                form = layui.form();


        laypage({
            cont: 'page',
            pages: pages, //总页数
            groups: 5, //连续显示分页数
            curr: curr,//获得当前页码
            jump: function(obj, first) {
                //得到了当前页，用于向服务端请求对应数据
                var curr = obj.curr;
                if(!first) {
                   window.location.href=listurl+"?id="+testDBId+"&requestPage="+curr+"&type="+type+"&level="+level+
                "&keyword="+keyword;
                }
            }
        });

        form.verify({
            title: function(value) {
                if(value.length < 5 || value == null) {
                    return '内容至少得5个字符啊';
                }
            }
        });

        // 搜索事件
        $('#search').on('click', function(data){

            var keyword = $("#keyword").val();
            var type = $("#type").val();
            var level = $("#level").val();
            var id = $("#testdb_id").val();
            var searchword = "";

            if(keyword=="" && type=="" && level=="") {
                layer.alert('请先输入内容', {time: 3000});
            }else {
                if(type !="") {
                    searchword = searchword+"&type="+type;
                }
                if(level !="") {
                    searchword = searchword+"&level="+level;
                }
                if(keyword !="") {
                     searchword = searchword+"&keyword="+keyword;
                }
                window.location.href=listurl+"?id="+id+searchword;
            }


            return false;
        });

        //点击添加单个题目的事件处理
        $('.addSingle').on('click', function(){
            var id = $("#testdb_id").val();
            $.post(checkpermis,{id:id},function(data){
                // alert(data.msg);
                if(data.status) {
                    var index = layer.open({
                        type: 2,
                        title:['添加单个题目', 'text-align:center;'],
                        // skin: 'layui-layer-rim my-input', //加上边框
                        area:['1100px', '500px'],  //宽高
                        resize: false,      //是否允许拉伸
                        //scrollbar: false,
                        maxmin: true,
                        content: addonequs+"?testdb_id="+id,
                        end: function (){
                           location.reload();
                        }
                    });
                    layer.full(index);
                }else{
                    layer.msg('对不起,您没有该操作权限!', {time: 3000,icon: 2});
                }
            });

        });

        //点击查看全部的事件处理
        $('#searchAll').on('click', function(){
            var id = $("#testdb_id").val();
            window.location.href=listurl+"?id="+id;
        });

        //点击删除按钮的事件处理
        $('.del2').on('click', function(){
            var testdb_id = $("#testdb_id").val();
            var id = $(this).data('id');
            layer.confirm('确定删除该题目?', {
                btn: ['确定', '取消']
            }, function(){
                 $.post(checkpermis,{id:testdb_id},function(data){
                    if(data.status){
                        $.ajax({
                            url:deletequs,
                            type:"POST",
                            data:{
                                'id':id,
                                'testdb_id':testdb_id
                            },
                            beforeSend: function(){
                                $.post(checkpermis,{id:testdb_id},function(data){
                                    // alert(data.msg);
                                    if(!data.status) {
                                        layer.msg(data2.msg,{time: 3000,icon: 1});
                                        location.reload();
                                    }
                                });
                            },
                            success:function(data2)
                            {
                                if(data2.status){
                                    //layer.close(index1);
                                    layer.msg(data2.msg, {time: 3000,icon: 1});
                                    location.reload();
                                }else {
                                    layer.msg(data2.msg, {time: 3000,icon: 2});
                                }
                            },
                            error: function(){
                                layer.msg('请求服务器超时', {time: 3000,icon: 2});
                            }
                        });
                    }else{
                        layer.msg('对不起,您没有该操作权限!', {time: 3000,icon: 2});
                    }
                 });
            })
        });

    

        //点击编辑按钮的事件处理
        $('.edit2').on('click', function(){

            var testdb_id = $("#testdb_id").val();
            var id = $(this).data('id');
            $.post(checkpermis,{id:testdb_id},function(data){
                // alert(data.msg);
                if(data.status) {
                    var index = layer.open({
                        type: 2,
                        title:['编辑题目', 'text-align:center;'],
                        // skin: 'layui-layer-rim my-input', //加上边框
                        area:['1100px', '500px'],  //宽高
                        resize: false,      //是否允许拉伸
                        //scrollbar: false,
                        maxmin: true,
                        content: editqus+"?testdb_id="+testdb_id+"&id="+id,
                        end: function (){
                           location.reload();
                        }
                    });
                    layer.full(index);
                }else{
                    layer.msg('对不起,您没有该操作权限!', {time: 3000,icon: 2});
                }
            });

            // var id = $(this).data('id');

            // layer.open({
            //     type: 2,
            //     title: ['编辑', 'text-align:center;'],
            //     content: '',//editurl+"?id="+id,
            //     area:['380px', '400px'],  //宽高
            //     resize: false,		//是否允许拉伸
            //     scrollbar: false,
            //     end: function(){
            //         location.reload();
            //     }
            // });

        });



        // 导入题目事件处理
        $(".addList").on('click',function(){
            // 获得题库id
            var testdb_id = $("#testdb_id").val();
            $.post(checkpermis,{id:testdb_id},function(data){
                // alert(data.msg);
                if(data.status) {
                    var index = layer.open({
                        title: ['批量导入题目','text-align:center'],
                        type: 2,
                        shadeClose: true,
                        shade: 0.8,
                        fix:true,
                        shift: 2,
                        maxmin: true,
                        area: ['680px', '410px'],
                        scrollbar: false,
                        content: addList+"?testdb_id="+testdb_id,
                        end: function (){
                           location.reload();
                        }
                    });
                    // layer.full(index);
                }
            });
        });



    });
