/**
 * Created by Mr.liang on 2017/7/22.
 */
/**
 * Created by Mr.liang on 2017/7/22.
 */
layui.config({
    base: rooturl+'Public/static/js/'
}).use(['form','layer','laypage'], function() {
    var $ = layui.jquery,
        form = layui.form(),
        layer = layui.layer,
        laypage = layui.laypage;

    //点击搜索按钮的触发事件
    form.render('select');
    $("#search").on('click', function() {
        var keyword = $('#keyword').val();
        var searchword = "";
        if(keyword == "") {
            layer.msg('请先输入内容', {time:1000});
        }else {
            window.location.href=listurl+"?keyword="+keyword+"&paperId="+paperId+"&courseclass_id="+courseclass_id;
        }
    });
    //分数段查询
    $('#scoreStage').on('click', function() {
        var score_min = $('#score_min').val();
        var score_max = $('#score_max').val();
        if(score_min == '' && score_max == '') {
            layer.msg('请先输入内容', {time:1000});
        }else{
            window.location.href=listurl+"?paperId="+paperId+"&courseclass_id="+courseclass_id+"&score_min="+score_min+"&score_max="+score_max;
        }

    });
    //点击查看全部的事件处理
    $('#searchAll').on('click', function(){
        window.location.href=listurl+"?paperId="+paperId+"&courseclass_id="+courseclass_id;
    });
    laypage({
        cont: 'page',
        pages: pages,
        groups: 5,
        //skip: true, //是否开启跳页
        curr: curr,
        jump: function(obj, first) {
            var curr = obj.curr;
            var searchword = "&keyword="+keyword;
            if(!first) {
                window.location.href = listurl+"?requestPage="+curr+"&paperId="+paperId+"&courseclass_id="+courseclass_id+searchword;
            }
        }
    });
    $('.export').on('click', function() {
        var index3 = layer.confirm('确定导出成绩吗?', {
            btn: ['确定', '取消']
        }, function(){
            window.open(exporturl+"?paperId="+paperId+"&courseclass_id="+courseclass_id);
            layer.close(index3);
        });
    });

    $('#paperKnowlwdge').on('click', function() {

        layer.open({
            type:2,
            title: ['修改学生信息', 'text-align:center;'],
            area: ['1000px', '500px'],
            scrollbar: false,
            content:paperKnowledgeUrl+"?paperId="+paperId+"&courseclass_id="+courseclass_id,
        });
    });
});