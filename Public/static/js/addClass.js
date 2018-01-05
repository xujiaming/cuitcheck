layui.config({
	base: rooturl+'Public/static/js/'
}).use(['form', 'layer'], function(){

	var $ = layui.jquery,
		form = layui.form(),
		layer = layui.layer;

	//点击添加班级的事件处理
	$('.add').on('click', function(){

		layer.open({
			type: 2,
			title: ['添加班级', 'text-align:center;'],
			content: editurl+"?testpaper_id="+testpaper_id,
			area:['500px', '400px'],  //宽高
			resize: false,		//是否允许拉伸
			scrollbar: false,
			end: function(){
				location.reload();
			}
		});
	});

	//点击删除的事件处理
	$('.del').on('click', function(){

		var courserclass_id = $(this).data('id');
		layer.confirm('确定删除该班级?', {
			btn: ['确定', '取消']
		}, function(){
			$.ajax({
				url: deleteurl,
				type: 'POST',
				dataType: 'json',
				data: {'courserclass_id': courserclass_id, 'testpaper_id':testpaper_id},
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