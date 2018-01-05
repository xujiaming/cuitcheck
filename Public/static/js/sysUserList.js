layui.config({
    base: rooturl+'Public/static/js/'
}).use(['laypage', 'layer', 'laydate'], function() {
    var $ = layui.jquery,
        laypage = layui.laypage,
        layer = layui.layer,
        laydate = layui.laydate;

    //page
    laypage({
        cont: 'page',
        pages: pages //总页数
        ,
        groups: 5 //连续显示分页数
        ,
        curr: curr,//获得当前页码
        jump: function(obj, first) {
            //得到了当前页，用于向服务端请求对应数据
            var curr = obj.curr;
            if(!first) {
                window.location.href=listurl+"?requestPage="+curr+"&beginDate="+beginDate_l+"&endDate="+endDate_l+
                "&keyword="+keyword_l;
            }
        }
    });

    //点击搜索按钮的事件处理
    $('#search').on('click', function() {
//		parent.layer.alert('你点击了搜索按钮')
        var keyword = $("#keyword").val();
        //var beginDate = $('#beginDate').val();
        //var endDate = $('#endDate').val();
        //var bd = new Date(beginDate);
        //var ed = new Date(endDate);
        //var searchword = "";

        if (keyword == ""){
            layer.msg('请先输入内容', {time: 1000});
        }else{
                window.location.href=listurl+"?keyword="+keyword;
        }
    });

    //点击删除按钮的事件处理
    $('.del').on('click', function(){

        var id = $(this).data('id');
        layer.confirm('确定删除该用户?', {
            btn: ['确定', '取消']
        }, function(){
            //检查该用户下方是否存在其他信息，存在则不能删除
            //ajax
            $.ajax({
                url:deleteurl,
                type:"POST",
                data:{
                    'id':id
                },
                beforeSend: function(){
                    //
                },
                success:function(data2)
                {
                    if(data2.success){
                        //layer.close(index1);
                        layer.msg(data2.msg, {time: 1000,icon: 1});
                        location.reload();
                    }else {
                        layer.msg(data2.msg, {time: 1000,icon: 2});
                    }
                },
                error: function(){
                    layer.msg('请求服务器超时', {time: 1000,icon: 2});
                }
            });
        },function(){});
    });

    //点击编辑按钮的事件处理
    $('.edit').on('click', function(){

        var id = $(this).data('id');

        layer.open({
            type: 2,
            title: ['修改用户信息', 'text-align:center;'],
            content: editurl+"?id="+id,
            area:['500px', '500px'],  //宽高
            resize: false,		//是否允许拉伸
            scrollbar: false,
            end: function(){
                location.reload();
            }
        });

    });

    //点击添加学院的事件处理
    $('.add').on('click', function(){

        layer.open({
            type: 2,
            title: ['添加系统用户', 'text-align:center;'],
            content: addurl,
            area:['500px', '500px'],  //宽高
            resize: false,		//是否允许拉伸
            scrollbar: false,
            end: function(){
                location.reload();
            }
        });
    });
    //重置密码
    $('.resetps').on('click', function() {
        var id = $(this).data('id');
        layer.confirm('确定将该账号的密码重置?', {
            icon: 3,
            btn: ['确定', '取消']
        }, function(){
            $.ajax({
                url: resetpsurl,
                type: 'POST',
                dataType: 'json',
                data: {'id': id},
                error: function(request){
                    layer.msg("请求服务器超时", {time: 1000, icon: 5});
                },
                success: function(data){
                    if (data.success){
                        layer.msg(data.msg, {
                            time: 1000
                        }, function(){
                            location.reload();
                        });
                    }else{
                        layer.msg(data.msg, {
                            time: 1000
                        });
                    }
                }
            });
            
        });
    });

    //点击查看全部的事件处理
    $('#searchAll').on('click', function(){

        window.location.href=listurl;
    });

    $('.batchadd').click(function() {
        layer.open({
            title: ['批量导系统用户','text-align:center'],
            type: 2,
            shadeClose: true,
            shade: 0.8,
            fix:true,
            shift: 2,
            maxmin: true,
            area: ['680px', '380px'],
            content: batchurl,
            scrollbar: false,
        });
    });
    

    //var start = {
    //    istime: true,
    //    format: 'YYYY-MM-DD hh:mm:ss',
    //    festival: false,
    //    choose: function(datas){
    //        end.min = datas; //开始日选好后，重置结束日的最小日期
    //        end.start = datas //将结束日的初始值设定为开始日
    //    }
    //};
    //
    //var end = {
    //    istime: true,
    //    format: 'YYYY-MM-DD hh:mm:ss',
    //    festival: false,
    //    choose: function(datas){
    //        start.max = datas; //结束日选好后，重置开始日的最大日期
    //    }
    //};
    //
    //document.getElementById('beginDate').onclick = function(){
    //    start.elem = this;
    //    laydate(start);
    //}
    //document.getElementById('endDate').onclick = function(){
    //    end.elem = this
    //    laydate(end);
    //}

});