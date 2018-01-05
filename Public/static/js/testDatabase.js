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
			var curr = obj.curr
			var searchword = "&keyword="+keyword+"&lession_id="+lession_id+"&course_id="+course_id+"&college_id="+college_id
			+"&status="+status+"&testtype_id="+testtype_id;
			if(!first) {
				window.location.href=listurl+"?requestPage="+curr+searchword;
			}
		}
	});

	// 课程信息联动
	form.on('select(course)', function(data){
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

	//点击搜索的事件处理
	$('#search').on('click', function(){
		var keyword = $("#keyword").val();
		var testtype_id = $("#testtype_id").val();
		var course_id = $("#course_id").val();
		var lession_id = $("#lession_id").val();
		var college_id = $("#college_id").val();
		var status = $("#status").val();


		if (keyword == "" && testtype_id == "" && course_id == "" && lession_id == "" && college_id == "" && status == ""){
			layer.msg('请先输入内容', {time: 1000});
		}else{
			var searchword = "&lession_id="+lession_id+"&course_id="+course_id+"&college_id="+college_id+"&status="+status
			+"&testtype_id="+testtype_id;
			window.location.href=listurl+"?keyword="+keyword+searchword;			
		}

	});

	//点击查看全部的事件处理
	$('#searchAll').on('click', function(){

		window.location.href=listurl;
	});

	//点击添加的事件处理
	$('.add').on('click', function(){
		layer.open({
			type: 2,
			title: ['添加题库', 'text-align:center;'],
			content: editurl+"?type=add",
			area:['500px', '400px'],  //宽高
			resize: false,		//是否允许拉伸
			scrollbar: false,
			end: function(){
				location.reload();
			}
		});
	});

	//点击编辑按钮的事件处理
	$('.edit').on('click', function(){

		var id = $(this).data('id');
		
		layer.open({
			type: 2,
			title: ['修改题库信息', 'text-align:center;'],
			content: editurl+"?type=update&id="+id,
			area:['500px', '400px'],  //宽高
			resize: false,		//是否允许拉伸
			scrollbar: false,
			end: function(){
				location.reload();
			}
		});
		
	});

	//点击查看详情的事件处理
	$('.detail').on('click', function(){
		var id = $(this).data('id');
		var index = layer.open({
			type: 2,
			title: ['题库详情', 'text-align:center;'],
			content: detailurl+"?id="+id,
			area:['1100px', '500px'],  //宽高
			resize: false,		//是否允许拉伸
			//scrollbar: false,
			maxmin: true,
			end: function(){
				location.reload();
			}
		});
		layer.full(index);
	});

	//点击删除按钮的事件处理
	$('.del').on('click', function(){

		var id = $(this).data('id');
		layer.confirm('确定删除该题库?', {
			btn: ['确定', '取消']
		}, function(){
			$.ajax({
				url: deleteurl,
				type: 'POST',
				dataType: 'json',
				data: {'id': id},
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