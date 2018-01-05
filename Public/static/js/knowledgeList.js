/**
 * Created by Administrator on 2017/3/23 0023.
 */
layui.use(['laydate','tree','form','layer'], function() {
    var $ = layui.jquery,
        layer = layui.layer,
        laydate = layui.laydate,
        form = layui.form();

    var index2;

    //加载内容
    $.ajax({
        url:'getChapterList',
        type:"POST",
//            data:data.field,
        timeout: 2000,
        beforeSend: function(){

        },
        success:function(data2)
        {
            layui.tree({
                elem: '#chapterTree',
                nodes: data2,
                click: function(node){
                    //console.log(node);
                    var data = {
                        "keyword":"",
                        //"beginDate":"",
                        //"endDate":"",
                        "chapter_id":"",
                        "course_id":""
                    };
                    // var index = layer.load(2,{offset: ['45%', '60%']});
                    if(node.homePart == 'kids'){
                        data.chapter_id = node.id;
                        var index = layer.load(2,{offset: ['45%', '60%']});
                        $('#knowledgeItem').load(listurl,data,function(){
                            layer.close(index);
                        });
                    }else if(node.homePart == 'parent'){
                        data.lession_id = node.id;
                        var index = layer.load(2,{offset: ['45%', '60%']});
                        $('#knowledgeItem').load(listurl,data,function(){
                            layer.close(index);
                        });
                    }else if(node.homePart == 'stop'){
                        //当点击的树节点不是课程或者章节时，返回提示信息
                        layer.open({
                            type: 1,
                            shade: false,
                            title: false, //不显示标题
                            content: $('.notice'), //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                        });

                    }
                }
            });

            // var index = layer.load(2,{offset: ['45%', '60%']});
            // $('#knowledgeItem').load(listurl,{course_id:1},function(){
            //     layer.close(index);
            // });

        },
        error: function(){
            layer.msg('列表请求失败!', {icon: 2});
        }
    });
    $('.cut').on('click', function(){
        var col = $('#demo-col').val();
        $("#chapterTree").html("");  //在每次ajax切换的时候，将该页原本的tree清空
        console.log(col);
        $.ajax({
            url:'getChapterList',
            type:"POST",
            data: {'choiceDept':col},
            timeout: 2000,
            beforeSend: function(){

            },
            success:function(data2)
            {
                layui.tree({
                    elem: '#chapterTree',
                    nodes: data2,
                    click: function(node){
                        //console.log(node);
                        var data = {
                            "keyword":"",
                            //"beginDate":"",
                            //"endDate":"",
                            "chapter_id":"",
                            "course_id":""
                        };
                        var index = layer.load(2,{offset: ['45%', '60%']});
                        if(node.homePart == 'kids'){
                            data.chapter_id = node.id;
                            $('#knowledgeItem').load(listurl,data,function(){
                                layer.close(index);
                            });
                        }else if(node.homePart == 'parent'){
                            data.course_id = node.id;
                            $('#knowledgeItem').load(listurl,data,function(){
                                layer.close(index);
                            });
                        }
                    }
                });

                // var index = layer.load(2,{offset: ['45%', '60%']});
                // $('#knowledgeItem').load(listurl,{course_id:1},function(){
                //     layer.close(index);
                // });

            },
            error: function(){
                layer.msg('列表请求失败!', {icon: 2});
            }
        });
    });

    form.verify({
        title: function(value) {
            if(value.length > 25) {
                return '内容最多25个字符';
            }
        },
        chapter:function(value){
            if(value.length < 1 || value == null) {
                return '请选择！';
            }
        }
    });

    //搜索内容
    form.on('submit(submit2)', function(data){
        //layer.alert(JSON.stringify(data.field), {
        //    title: '最终的提交信息'
        //});
        //formData = data.field;
        var searchword = "";
        //var bd = new Date(data.field.beginDate);
        //var ed = new Date(data.field.endDate);
        if (data.field.keyword == "" && data.field.course_id == ""){
            layer.msg('请先输入内容', {time: 1000});
        }else{
            var index = layer.load(2,{offset: ['45%', '60%']});
            $('#knowledgeItem').load(listurl,data.field,function(){
                layer.close(index);
            });
        }
        return false;
    });


    $('.addList').on('click',function(){
        var index =layer.open({
            title: ['批量导入知识点','text-align:center'],
            type: 2,
            shadeClose: true,
            shade: 0.8,
            fix:true,
            shift: 2,
            maxmin: true,
            area: ['680px', '410px'],
            content: importurl,
            scrollbar: false,
        });
    });


    //点击查看全部的事件处理
    $('#searchAll').on('click', function(){
        var index = layer.load(2,{offset: ['45%', '60%']});
        $('#knowledgeItem').load(listurl,{},function(){
            layer.close(index);
        });
    });


});
