layui.use(['layer','form','jquery','laypage'],function() {
    var layer = layui.layer,
        form = layui.form(),
        $ = layui.jquery,
        laypage = layui.laypage;
    //layui.selMeltiple($);


    form.on('select(collegeS)',function() {
        var dept_id = $('#college').val();
        $.ajax({
            url: linkselecturl,
            type: 'POST',
            data: {'dept_id': dept_id},
            dataTpye: 'json',
            success: function (data) {
                //console.log(data);
                $('form').find('select[name=course_id]').html(data.course);
                form.render('select');
                var course_id = $('#course').val();
                getLession(course_id);  //获取课程
                getCourseClass();     //获取班级
            }
        });
    });
    form.on('select(courseS)',function() {
        var course_id = $('#course').val();
        getLession(course_id);  //获取默认试卷
        getCourseClass();      //获取班级
    });
    //获取课程
    function getLession(course_id) {
        $.ajax({
            url: linkselecturl,
            type: 'POST',
            data: {'course_id': course_id},
            dataTpye: 'json',
            success: function (data) {
                //console.log(data);
                $('form').find('select[name=lession_id]').html(data.lession);
                form.render('select');
                //console.log($('#lession').val());
                getPaper();  //获取试卷默认
            }
        });
    }
    form.on('select(gradeS)', function() {
        // var dept_id = $('#college').val();
        // var course_id = $('#course').val();
        // var lession_id = $('#lession').val();
        // var grade_id = $('#grade').val();
        // $.ajax({
        //     url: linkselecturl,
        //     type: 'POST',
        //     data: {'dept_id': dept_id,'course_id': course_id,'lession_id':lession_id,'grade_id':grade_id},
        //     dataTpye: 'json',
        //     success: function (data) {
        //         //console.log(data);
        //         $('form').find('select[name=paper_id]').html(data.paper);
        //         $('form').find('select[name=courseclass_ids]').html(data.courseclass);
        //         form.render('select');
        //         layui.selMeltiple($);
        //     }
        // });
        getCourseClass();
    });

    function getPaper() {
        var dept_id = $('#college').val();
        var lession_id = $('#lession').val();
        if(lession_id == '') {  //如果课程暂时没有值，将其赋值为0
            lession_id = 0;
        }
        $.ajax({
            url: linkselecturl,
            type: 'POST',
            data: {'dept_id': dept_id,'lession_id':lession_id},
            dataTpye: 'json',
            success: function (data) {
                //console.log(data);
                $('form').find('select[name=paper_id]').html(data.paper);
                form.render('select');
            }
        });
    }
    function getCourseClass() {
        var dept_id = $('#college').val();
        var lession_id = $('#lession').val();
        var grade_id = $('#grade').val();
        if(grade_id == '') {
            grade_id = 0;
        }
        if(lession_id == '') {
            lession_id = 0;
        }
        $.ajax({
            url: linkselecturl,
            type: 'POST',
            data: {'dept_id': dept_id,'lession_id':lession_id,'grade_id':grade_id},
            dataTpye: 'json',
            success: function (data) {
                console.log(data.courseclass);
                $('#courseclass_ids').html(data.courseclass);
                form.render('checkbox');
                //layui.selMeltiple($);
            }
        });
    }
    //返回上级
    $('.returnLast').on('click', function() {
        window.location.href = returnUrl;
    });
    $("#search").on('click', function() {
        var keyword = $('#keyword').val();
        var searchword = "";
        if(keyword == "") {
            layer.msg('请先输入内容', {time:1000});
        }else {
            window.location.href=listurl+"?keyword="+keyword+"&paper_id="+paperId+"&courseclass="+courseclass_id;
        }
    });
    //分数段查询
    $('#scoreStage').on('click', function() {
        var score_min = $('#score_min').val();
        var score_max = $('#score_max').val();
        if(score_min == '' && score_max == '') {
            layer.msg('请先输入内容', {time:1000});
        }else{
            window.location.href=listurl+"?paper_id="+paperId+"&courseclass="+courseclass_id+"&score_min="+score_min+"&score_max="+score_max;
        }

    });
    //点击查看全部的事件处理
    $('#searchAll').on('click', function(){
        window.location.href=listurl+"?paper_id="+paperId+"&courseclass="+courseclass_id;
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
                window.location.href = listurl+"?requestPage="+curr+"&paper_id="+paperId+"&courseclass="+courseclass_id+searchword;
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
    //知识点详情
    $('#paperKnowlwdge').on('click', function() {

        layer.open({
            type:2,
            title: ['知识点信息', 'text-align:center;'],
            area: ['1000px', '500px'],
            scrollbar: false,
            content:paperKnowledgeUrl+"?paperId="+paperId+"&courseclass_id="+courseclass_id,
        });
    });

    //表格排序
    //$('table').tablesort().data('tablesort');
    // var ii = 0;
    // $(".table tr th a").click(function() {
    //     if (ii % 2 == 0) {
    //         $(".sj").text('升序');
    //         ii++;
    //     } else {
    //         $(".sj").text('降序');
    //         ii++;
    //     }
    // });
});