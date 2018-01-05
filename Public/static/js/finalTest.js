layui.config({
	base: rooturl+'Public/static/js/'
}).use(['form', 'layer'], function(){

	var $ = layui.jquery,
		form = layui.form(),
		layer = layui.layer;

	//点击分配班级的事件处理
	$('.add').on('click', function(){

		var id = $(this).data('id');

		 $.ajax({
		      url: ispushurl,
		      type: 'POST',
		      data: {'id': id},
		      error: function(request){
		          layer.msg("请求服务器超时", {time: 1000, icon: 5});
		      },
		      success:function(data){
		          if(data.success){
			         var index = layer.open({
						type: 2,
						title: ['分配班级', 'text-align:center;'],
						content: addClassurl+"?testpaper_id="+id,
						area:['1100px', '500px'],  //宽高
						resize: false,		//是否允许拉伸
						//scrollbar: false,
						maxmin: true,
						end: function(){
							location.reload();
						}
					});
					layer.full(index);
		          }else{
		            layer.msg(data.msg, {
		              time: 1000,icon:5
		            });
		          }     
		      }
		 });

		
	});
});