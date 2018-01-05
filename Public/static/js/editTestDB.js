layui.config({
	base: rooturl+'Public/static/js/'
}).use(['form'], function(){
	var form = layui.form(),
		layer = layui.layer,
		$ = layui.jquery;

	//自定义验证规则
	form.verify({
		name: function(value) {
			if(value == "") {
				return '题库名称不能为空';
			}
		},
		type_id:function(value){
			if(value == ""){
				return '所属类型不能为空';
			}
		},
		lession_id: function(value){
			if(value == ""){
				return '所属学科不能为空';
			}
		},
		course_id: function(value){
			if(value == ""){
				return '所属课程不能为空';
			}
		},
		college_id: function(value){
			if (value == ""){
				return '创建学院不能为空';
			}
		}
	});


	// 动态监听学科
	form.on('select(course)', function(data){
		// alert(data.value);
		var course_id = data.value;
		$.ajax({
			url:getLession,
			type:'POST',
			data:{'course_id':course_id},
			dataType:'json',
			success:function(data){
				//将option框清空,避免for循环重复
	 			$(".lession_id").find("option").each(function(){
	 				if(!$(this).val() == "")
						$(this).remove();
				});

	 			//动态添加option
	 			for (var i = data.length - 1; i >= 0; i--) {
	 				// alert(data[i]);
	 				$(".lession_id").append("<option value="+data[i]['id']+">"+data[i]['name']+"</option>");
	 			}
			 	// 更新选择框
			 	form.render('select');
			}
		});
		
	});

	// 动态监听学院, 根据学院更新学科
	form.on('select(college)', function(data){
		// alert(data.value);
		var college_id = data.value;
		$.ajax({
			url:getCourse,
			type:'POST',
			data:{'college_id':college_id},
			dataType:'json',
			success:function(data){
				//将option框清空,避免for循环重复
	 			$(".course_id").find("option").each(function(){
	 				if(!$(this).val() == "")
						$(this).remove();
				});

	 			//动态添加option
	 			for (var i = data.length - 1; i >= 0; i--) {
	 				// alert(data[i]);
	 				$(".course_id").append("<option value="+data[i]['id']+">"+data[i]['name']+"</option>");
	 			}
			 	// 更新选择框
			 	form.render('select');
			}
		});
		
	});

	//监听提交
	form.on('submit(demo1)', function(data) {
		//修改状态所对应的值
        if(data.field.status == 'on'){
            data.field.status = "1";
        }else {
            data.field.status = "0";
        }
		$.ajax({
			url: editurl,
			type: 'POST',
			data: data.field,
			error: function(request){
				layer.msg("请求服务器超时", {time: 1000, icon: 5});
			},
			success: function(data){
				if (data.success){
					layer.msg('提交成功', {
						time: 1000
					}, function(){
						var index = parent.layer.getFrameIndex(window.name);
						parent.layer.close(index);
					});
				}else{
					layer.msg(data.msg, {
						time: 1000
					});
				}
			}
		});
		
		return false;
	});
});