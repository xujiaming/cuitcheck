layui.config({
	base: rooturl+'Public/static/js/'
}).use(['form', 'layer'], function(){
	var $ = layui.jquery,
		layer = layui.layer;


	//点击添加的事件处理
	$('.add').on('click', function(){
		layer.open({
			type: 2,
			title: ['添加授权学院','text-align:center;'],
			content: editurl+"?type=add&testdb_id="+testdb_id,
			area:['500px', '400px'],  //宽高
			resize: false,		//是否允许拉伸
			scrollbar: false,
			end: function(){
				location.reload();
			}
		});
	});

	//点击修改的事件处理
	$('.edit').on('click', function(){
		var college_id = $(this).data('id');
		if (create_id == college_id){
			layer.msg("不可修改创建学院的权限", {time: 1500, icon: 5});
		}else{
			layer.open({
				type: 2,
				title: ['修改权限','text-align:center;'],
				content: editurl+"?type=update&college_id="+college_id+"&testdb_id="+testdb_id,
				area:['500px', '400px'],  //宽高
				resize: false,		//是否允许拉伸
				scrollbar: false,
				end: function(){
					location.reload();
				}
			});
		}
	});

	//点击搜索的事件处理
	$('#search').on('click', function(){
		var keyword = $("#keyword").val();
		if (keyword == ""){
			layer.msg('请先输入内容', {time: 1000});
		}else{
			window.location.href = listurl+"?keyword="+keyword+"&id="+testdb_id;
		}
	});

	//点击查看全部的事件处理
	$('#searchAll').on('click', function(){
		window.location.href=listurl+"?id="+testdb_id;
	});

	//点击删除的事件处理
	$('.del').on('click', function(){
		var college_id = $(this).data('id');

		if (create_id == college_id){
			layer.msg("不可修改创建学院的权限", {time: 1500, icon: 5});
		}else{
			layer.confirm('确定删除该学院权限，删除后该学院将无法访问操作该题库', {
				btn:['确定', '取消']
			}, function(){
				$.ajax({
					url: deleteurl,
					type: 'POST',
					dataType: 'json',
					data: {'college_id': college_id, 'testdb_id': testdb_id},
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
		}
	});

});