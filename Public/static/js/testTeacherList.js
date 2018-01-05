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
			var searchword = "&keyword="+keyword;
			if(!first) {
				window.location.href=listurl+"?requestPage="+curr+searchword;
			}
		}
	});

	//点击搜索的事件处理
	$('#search').on('click', function(){
		var keyword = $("#keyword").val();

		if (keyword == ""){
			layer.msg('请先输入内容', {time: 1000});
		}else{
			var searchword = "&keyword="+keyword;
			window.location.href=listurl+"?keyword="+keyword+searchword;
		}
	});

	//查看全部的事件处理
	$('#searchAll').on('click', function(){
		window.location.href=listurl;
	});

	//点击添加权限的事件处理
	$('.add').on('click', function(){
		layer.open({
			type: 2,
			title: ['添加权限', 'text-align:center;'],
			content: editurl+"?type=add",
			area:['500px', '400px'],  //宽高
			resize: false,		//是否允许拉伸
			scrollbar: false,
			end: function(){
				location.reload();
			}
		});
	});

	//点击修改权限的事件处理
	$('.edit').on('click', function(){
		var id = $(this).data('id');
		layer.open({
			type: 2,
			title: ['修改权限', 'text-align:center;'],
			content: editurl+"?type=update&id="+id,
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
		var id = $(this).data('id');
		layer.confirm('确定删除该教师出题权限?删除后教师将没有操作正式考试的权限', {
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