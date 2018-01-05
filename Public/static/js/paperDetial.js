layui.config({
	base: rooturl+'Public/static/js/'
}).use(['laydate','tree','form','layer','laypage'], function(){
	var $ = layui.jquery,
        layer = layui.layer,
        laydate = layui.laydate,
        form = layui.form();

    var myChart = echarts.init(document.getElementById('detil'));
        var option = {
            title: {
                text: '试卷统计'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: { // 坐标轴指示器，坐标轴触发有效
                    type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            legend: {
                data: ['易题', '中等', '难题'],
                align: 'right',
                right: 10
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [{
                type: 'category',
                data: ['选择', '判断', '填空', '编程']
            }],
            yAxis: [{
                type: 'value',
                name: '题量(道数)',
                axisLabel: {
                    formatter: '{value}'
                }
            }],
            series: [{
                name: '易题',
                type: 'bar',
                data: easyNumArray
            }, {
                name: '中等',
                type: 'bar',
                data: normalNumArray
            }, {
                name: '难题',
                type: 'bar',
                data: hardNumArray
            }]
        };
        myChart.setOption(option);

        //单个题目添加的事件处理
        $('#addSingle').on('click',function () {
            layer.open({
                type: 2,
                title:"选择添加题目",
                skin: 'layui-layer-rim', //加上边框
                area: ['90%', '500px'], //宽高
                content: editAddSingleurl+"?testpaper_id="+testpaper_id+'&college_id='+college_id+'&lession_id='+lession_id+'&type_id='+type_id,
                end: function (){
                    window.location.reload();
                }
            });
        });

        //删除单个题目的事件处理
        $('.delSingle').on('click',function () {
        	var question_id = $(this).data('id');//获取题目question_id
            layer.confirm('确认删除？', {
                btn: ['确认','取消'] //按钮
            }, function(){
            	$.ajax({
					url: deleteurl,
					type: 'POST',
					dataType: 'json',
					data: {'question_id': question_id, 'testpaper_id': testpaper_id},
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

        //更改单个分数的事件处理
        $('.changeVal').on('click',function () {
        	var question_id = $(this).data('id');//获取题目question_id
        	var value = $(this).data('value');
            layer.prompt({
                formType: 0,
                value: value,
                title: '请输入分值',
                end:function(){
                	window.location.reload();
                }
            }, function(value, index, elem){
            	$.ajax({
            		url: changeValueurl,
            		type: 'POST',
	            	dataType: 'json',
					data: {'question_id':question_id, 'testpaper_id': testpaper_id, 'value': value},
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
                layer.close(index);
            });
        });

        //自动生成的事件处理
        $('#autoAdd').on('click',function () {
            layer.open({
                type: 2,
                title:"自动生成试卷",
                skin: 'layui-layer-rim', //加上边框
                area: ['90%', '450px'], //宽高
                resize: false,      //是否允许拉伸
                maxmin: true,
                content: autoCreateurl+"?testpaper_id="+testpaper_id+'&college_id='+college_id+'&lession_id='+lession_id,
                end: function (){
                    window.location.reload();
                }
            });
        });

		//点击刷新的事件处理
        $('#reflush').on('click', function(){
        	window.location.reload();
        });

        //选择题点击显示答案的事件处理
        $('.choice-panel').on('click', function(){

        	var question_id = $(this).data('id');//获取题目question_id
        	var type = $(this).data('type');//获取题目类型

        	var obj = $(this).next('.panel-body');
            if (obj.css('display') == "none") {
                $(this).find('i').html('&#xe619;');
                obj.slideDown();
            } else {
                $(this).find('i').html('&#xe61a;');
                obj.slideUp();
            }

            $.ajax({
            	url: getAnswerurl,
            	type: 'POST',
            	dataType: 'json',
				data: {'question_id':question_id},
				error: function(request){
					layer.msg("请求服务器超时", {time: 1000, icon: 5});
				},
				success: function(data){
					if (data.success){
						if (type == 1){
							var answerstr = "<div><div class=\"grid-6\"><code>A.</code><pre class=\"layui-inline\">"
							            	+(data.list[0]['is_true'] == 1 ? "<mark>"+data.list[0]['content']+"</mark>" : data.list[0]['content'])
							            	+"</pre></div><div class=\"grid-6\"><code>B.</code><pre class=\"layui-inline\">"
							            	+(data.list[1]['is_true'] == 1 ? "<mark>"+data.list[1]['content']+"</mark>" : data.list[1]['content'])
							            	+"</pre></div></div><div><div class=\"grid-6\"><code>C.</code><pre class=\"layui-inline\">"
							            	+(data.list[2]['is_true'] == 1 ? "<mark>"+data.list[2]['content']+"</mark>" : data.list[2]['content'])
							            	+"</pre></div><div class=\"grid-6\"><code>D.</code><pre class=\"layui-inline\">"
							            	+(data.list[3]['is_true'] == 1 ? "<mark>"+data.list[3]['content']+"</mark>" : data.list[3]['content'])
							            	+"</pre></div></div>";

							obj.html(answerstr);
						}else if(type == 2){
						 	if(data.list[0]['is_true'] == 1){
						 		var answerstr = "<span>正确</span>";
						 	}else{
						 		var answerstr = "<span>错误</span>";
						 	}
						 	obj.html(answerstr);
						}else if(type == 3){
							var answerstr = "<pre class=\"layui-inline\">"+data.list[0]['content']+"</pre>";
							obj.html(answerstr);
						}else if(type==4){
							var answerstr = "<code><pre>"+data.list[0]['content']+"</pre></code>";
							obj.html(answerstr);
						}
					}else{
						layer.msg(data.msg, {
							time: 1000
						});
					}
				}
            });
        });
    });