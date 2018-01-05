layui.config({
    base: rooturl+'Public/static/js/'
}).use(['laypage', 'layer','form', 'laydate','element'], function() {
    var $ = layui.jquery,
        laypage = layui.laypage,
        layer = layui.layer,
        laydate = layui.laydate;
        form = layui.form(),
        element = layui.element()

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
                window.location.href=listurl+"?requestPage="+curr+"&beginDate="+beginDate+"&endDate="+endDate+
                "&keyword="+keyword;
            }
        }
    });


    // 查看全部 
    $('#searchAll').on('click', function(){
        window.location.href=listurl;
    });


    // 搜索
    $('#search').on('click',function(){
        var keyword = $("#keyword").val();
        var beginDate = $('#beginDate').val();
        var endDate = $('#endDate').val();
        var bd = new Date(beginDate);
        var ed = new Date(endDate);
        var searchword = "";
        // alert(bd);
        // alert(ed);
        // alert(keyword);
        if(beginDate=="" && endDate=="" && keyword==""){
            layer.msg('请先输入内容', {time: 1000});
        } else {
            if((beginDate=="" || endDate=="") && (beginDate!="" || endDate!="")){
                layer.msg('开始时间不能大于结束时间！', {time: 1000});
            } else if(beginDate != "" && endDate != "" && bd > ed){
                layer.msg("开始时间不能大于结束时间！", {time:1000});
            }else{
                if (beginDate != ""){
                    searchword = searchword+"&beginDate="+beginDate+"&endDate="+endDate;
                }
                window.location.href=listurl+"?keyword="+keyword+searchword;

            }
        }
    });

    // 编辑信息
    $(".edit").on('click',(function() {
        var id = $(this).data('id');
        layer.open({
            type: 2,
            title: ['修改信息', 'text-align:center;'],
            content: editurl+"?type=update&id="+id,
            area: ['370px', '370px'],
            resize: false,        //是否允许拉伸
            scrollbar: false,
            end: function(){
                location.reload();
            }

        });
    }));


    // 删除信息
    $(".del").on('click',(function(event) {
        var id = $(this).data('id');
        layer.confirm('确定删除该信息?', {
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
    }));



    // 添加信息
     $(".add").on('click',(function(event) {
        layer.open({
            type: 2,
            title: '添加信息',
            resize: false,        //是否允许拉伸
            scrollbar: false,
            area: ['370px', '370px'],
            content: editurl,  
            end: function(){
                location.reload();
            }            

        });
    }));


     var start = {
        istime: true,
        format: 'YYYY-MM-DD hh:mm:ss',
        festival: false,
        choose: function(datas){
            end.min = datas; //开始日选好后，重置结束日的最小日期
            end.start = datas //将结束日的初始值设定为开始日
        }
    };

    var end = {
        istime: true,
        format: 'YYYY-MM-DD hh:mm:ss',
        festival: false,
        choose: function(datas){
            start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };

    document.getElementById('beginDate').onclick = function(){
        start.elem = this;
        laydate(start);
    }
    document.getElementById('endDate').onclick = function(){
        end.elem = this
        laydate(end);
    }


});