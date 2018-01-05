/**
 * Created by Mr.liang on 2017/7/20.
 */
layui.config({
    base: rooturl+'Public/static/js/'
}).use(['form','layer','laypage','laydate','validator','upload'], function() {
    var $ = layui.jquery,
        form = layui.form(),
        layer = layui.layer,
        laypage = layui.laypage,
        validator = layui.validator,
        laydate = layui.laydate;

    //点击搜索按钮的触发事件
    form.render('select');
    $("#search").on('click', function() {
        var college_id = $('#colid').val();
        var type_id = $('#typeid').val();
        var keyword = $('#keyword').val();
        var searchword = "";
        if(college_id== "" && type_id== "" && keyword == "") {
            layer.msg('请先输入内容', {time:1000});
        }else {
            searchword = searchword+"&college_id="+college_id+"&type_id="+type_id;
            window.location.href=listurl+"?keyword="+keyword+searchword;
        }
    });
    //点击查看全部的事件处理
    $('#searchAll').on('click', function(){
        window.location.href=listurl;
    });
    laypage({
        cont: 'page',
        pages: pages,
        groups: 5,
        skip: true, //是否开启跳页
        curr: curr,
        jump: function(obj, first) {
            var curr = obj.curr;
            var searchword = "&keyword="+keyword+"&college_id="+college_id+"&type_id="+type_id;
            if(!first) {
                window.location.href = listurl+"?requestPage="+curr+searchword;
            }
        }
    });
    $('.detail').on('click', function() {
        var id = $(this).data('id');
        //弹出即全屏
        var index = layer.open({
            type: 2,
            title: ['   详细信息', 'text-align:center;'],
            content: detailurl+"?id="+id,
            maxmin: true,
        });
        layer.full(index);
    });
    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    // $('#search').on('click', function() {
    //    var index = layer.open({
    //        type:1,
    //        content:$('.testtest'),
    //        area:['320px', '195px'],
    //    });
    // });
    // $('.test2').on('click', function() {
    //     var index2 = parent.layer.open({
    //         type: 2,
    //         content: 'http://layim.layui.com',
    //         //area: ['320px', '195px'],
    //         maxmin: true
    //     });
    //     parent.layer.full(index2);
    // });
});