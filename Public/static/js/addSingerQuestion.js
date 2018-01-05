layui.config({
	base: rooturl+'Public/static/js/'
}).use(['form', 'layer'], function(){
	$ = layui.jquery,
        layer = layui.layer,
        form = layui.form();
        layui.selMeltiple($);

    // 获取到选择的课程
    form.on('select(filter1)', function(data){
        var database_id = data.value;
        $.ajax({
            url:getbd,
            type:"POST",
            data: {'database_id':database_id,'college_id':college_id},
            dataType:"json",
            success: function (data) {
                /*清空隐藏域缓存值，和无效隐藏域*/
                $("input[name='chapter_id']").val("");
                $("input[name='undefined']").remove();

                //将option框清空,避免for循环重复
                $(".chapter_id").find("option").each(function(){
                    if(!$(this).val() == "")
                        $(this).remove();
                }); 
                var dbs = data.dbs;

                //动态添加option
   				if (dbs=="") {
   					$(".chapter_id").append("<option value="+"00"+">"+"该题库下暂无课操作章节！"+"</option>");
   				}else{
                for (var i = dbs.length - 1; i >= 0; i--) {
                    // alert(data[i]);
                    $(".chapter_id").append("<option value="+dbs[i].id+">"+dbs[i].name+"</option>");
                }
                }
                // 更新选择框
                form.render('select');
                layui.selMeltiple($);
            }
        });
    });

	/**
	 * 点击查询的事件处理
	 */
	$('#query').on('click', function(){
        var chs = $("input[name='chapter_id']").val() != null ? $("input[name='chapter_id']").val():"";
        var ids = document.getElementById("database_id").value;
        
        if (ids == ""){
        	layer.msg("请先选择题库", {time: 1000, icon: 5});
        }else{
        	window.location.href=queryurl+"?ids="+ids+"&chs="+chs+"&testpaper_id="+testpaper_id;
        }
	});

	/**
	 * 点击添加单个题的事件处理
	 */
	$('.addSinger').on('click', function(){

		var question_id = $(this).data('id');//获取题目question_id
		layer.confirm('确认添加？', {
			btn: ['确认','取消'] //按钮
		}, function(){
			$.ajax({
				url: addSingerurl,
				type: 'POST',
	        	dataType: 'json',
				data: {'question_id':question_id, 'testpaper_id': testpaper_id},
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
});