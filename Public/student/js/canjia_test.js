/**
 * Created by Mr.liang on 2017/7/16.
 */
layui.use(['laypage', 'layer','form'], function() {
    var laypage = layui.laypage,
        layer = layui.layer,
        form = layui.form();


    //获取试卷的详细信息
    function getPaperList(curr, type_id) {
        $.getJSON(canjia_testUrl, {'requestPage': curr, 'type_id':type_id}, function(data) {
            var json = eval(data); //数组
            //console.log(json);
            if(json.status == 1) {
                var list = json.info.list;
                //console.log(json.info.list);
                $("#canjia_test").html("");  //在每次跳转分页的时候，将该页原本追加的div清空
                //循环json数组，依次赋值div
                $.each(list, function (index, item) {
                    var flag = checkTime(item.start_time, item.end_time);   //比较考试时间与当前的时间
                    //循环获取数据，拼接html标签以及内容
                    html = '<div class="testList" data-id="'+item.id+'" data-courseid="'+item.courseclass_id+'">'+
                        '<div class="testListLeft">'+
                        '<div class="testName">'+item.name+'</div>'+
                        '<div class="testClass">'+item.courseclass_name+'</div>'+
                        '</div>'+
                        '<div class="testListRight">'+
                        '<div class="testMessage"><font color="#666">●</font>&nbsp;试卷总分：'+item.full_score+'</div>'+
                        '<div class="testMessage"><font color="#666">●</font>&nbsp;及格分数：'+item.pass_score+'</div>'+
                        '<div class="testMessage"><font color="#666">●</font>&nbsp;考试开始时间：'+item.start_time+'</div>'+
                        '<div class="testMessage"><font color="#666">●</font>&nbsp;考试结束时间：'+item.end_time+'</div>';
                    if(flag == 1) {
                        html += '<div class="testStatusN">考试还未开始</div>'+'</div></div>';
                    }
                    else if(flag == 2) {
                        html += '<div class="testStatusD">考试已经结束</div>'+'</div></div>';
                    }else {
                        html += '<div class="testStatusY">考试正在进行</div>'+'</div></div>';
                    }

                    $("#canjia_test").append(html); //每一次循环，将html追加一次

                });
            }

            var pages = json.info !== undefined?json.info.pages:0;

            laypage({
                cont: 'nl-page2',
                pages: pages,
                skin: '#6a96df',
                curr: curr || 1,
                jump: function(obj, first) {
                    if(!first) {
                        getPaperList(obj.curr, type_id);
                    }
                }
            });
        });
    }
    getPaperList();     //执行函数

    //时间比较，判断当前时间与考试时间的关系
    function checkTime(start_time, end_time){
        var date = new Date();
        var str = "" + date.getFullYear() + "-";
        str += (date.getMonth()+1) + "-";
        str += date.getDate();
        str += ' '+ date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
        var tady = new Date(str.replace("-", "/").replace("-", "/"));
        var startTime = start_time;
        var endTime = end_time;
        var start=new Date(startTime.replace("-", "/").replace("-", "/"));
        var end=new Date(endTime.replace("-", "/").replace("-", "/"));
        //console.log(start+'<br>'+end);console.log(tady);console.log(str);
        if(tady < start){
            return 1;   //考试时间未到
        }else if(tady > end){
            return 2;      //考试时间已经结束
        }else {
            return 0;       //在时间范围中
        }
    }


    // 扩展post提交数据, 并进行跳转
    $.extend({
        StandardPost:function(url,args){
            var body = $(document.body),
                form = $("<form method='post'></form>"),
                input;
            form.attr({"action":url});
            $.each(args,function(key,value){
                input = $("<input type='hidden'>");
                input.attr({"name":key});
                input.val(value);
                form.append(input);
            });

            form.appendTo(document.body);
            form.submit();
            document.body.removeChild(form[0]);
        }
    });

    //动态添加元素后，获取data属性
    $(document).on('click', '.testList', function() {
        var id = $(this).data('id');        //获取试卷的id
        var courseclass_id = $(this).data('courseid');
        $.StandardPost(guoduUrl, {'id':id,'courseclass_id':courseclass_id});
    });
    //监听select选择框
    $('#paperListType').change(function() {
        var type_id = $(this).val(); //获取类型的id值
        getPaperList(1, type_id);    //执行ajax函数获取数据
    });

});
