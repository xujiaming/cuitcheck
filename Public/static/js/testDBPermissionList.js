layui.config({
	base: rooturl+'Public/static/js/'
}).use(['form', 'layer', 'laypage'], function(){
	var $ = layui.jquery,
		form = layui.form(),
		layer = layui.layer,
		laypage = layui.laypage;

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
			var searchword = "&keyword="+keyword+"&course_id="+course_id+"&college_id="+college_id+"&status="+status;
			if(!first) {
				window.location.href=listurl+"?requestPage="+curr+searchword;
			}
		}
	});

	//点击搜索的事件处理
	$('#search').on('click', function(){
		var keyword = $("#keyword").val();
		var college_id = $("#college_id").val();
		var course_id = $("#course_id").val();
		var status = $("#status").val();

		if (keyword == "" && course_id == "" && college_id == "" && status == ""){
			layer.msg('请先输入内容', {time: 1000});
		}else{
			var searchword = "&course_id="+course_id+"&college_id="+college_id+"&status="+status;
			// alert(searchword);
			window.location.href=listurl+"?keyword="+keyword+searchword;
		}
	});

	// 课程信息联动
	form.on('select(college)', function(data){
		var college_id = data.value;
		$.ajax({
			url:getCourse,
			type:'POST',
			data:{'college_id':college_id},
			dataType:'json',
			success:function(data){
				//将option框清空,避免for循环重复
	 			$("#course_id").find("option").each(function(){
	 				if(!$(this).val() == "")
						$(this).remove();
				});

	 			//动态添加option
	 			for (var i = data.length - 1; i >= 0; i--) {
	 				// alert(data[i]);
	 				$("#course_id").append("<option value="+data[i]['id']+">"+data[i]['name']+"</option>");
	 			}
			 	// 更新选择框
			 	form.render('select');
			}
		});
	});

	//查看全部的事件处理
	$('#searchAll').on('click', function(){
		window.location.href=listurl;
	});

	//点击管理权限的事件处理
	$('.detail').on('click', function(){
		var id = $(this).data('id');

		var index = layer.open({
			type: 2,
			title: ['权限管理', 'text-align:center;'],
			content: detailurl+"?id="+id,
			resize: false,		//是否允许拉伸
			maxmin: true,
			end: function(){
				location.reload();
			}
		});
		layer.full(index);
	});

	
});