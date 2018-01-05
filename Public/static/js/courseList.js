layui.config({
	base: rooturl+'Public/static/js/'
}).use(['element','layer'], function(){
	var element = layui.element(),
		$ = layui.jquery,
		layer = layui.layer;
	//element.init();

	//展示科目详情
	$('.course-box').on('click', function(){

		var id = $(this).data('id');
		layer.open({
			type:2,
			title: ['查看课程信息', 'text-align:center;'],
			content: editurl+"?type=update&id="+id,
			area:['500px', '350px'],  //宽高
			resize: false,		//是否允许拉伸
			scrollbar: false,
			end: function(){
				location.reload();
			}
		});
	});

	$('.panel-toggle').click(function(){
		var obj = $(this).parent('.panel-heading').next('.panel-body');
		if (obj.css('display') == "none") {
			$(this).find('i').html('&#xe619;');
			obj.slideDown();
		} else {
			$(this).find('i').html('&#xe61a;');
			obj.slideUp();
		}
	})
	//点击添加科目的事件处理
	$('.add').on('click', function(){

		layer.open({
			type: 2,
			title: ['添加课程信息', 'text-align:center;'],
			content: editurl+"?type=add",
			area:['500px', '350px'],  //宽高
			resize: false,		//是否允许拉伸
			scrollbar: false,
			end: function(){
				location.reload();
			}
		});
	});
});