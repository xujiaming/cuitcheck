layui.config({
	base: rooturl+'Public/static/js/'
}).use(['form', 'layer', 'laydate'], function(){
	var $ = layui.jquery,
		form = layui.form();
		layer = layui.layer,
		laydate = layui.laydate;

	//自定义验证规则
	form.verify({
		teacher_id: function(value) {
			if(value == "") {
				return '教师名称不能为空';
			}
		},
		start_time: function(value){
			if(value == ""){
				return '开始时间不能为空';
			}
		},
		end_time: function(value){
			if (value == ""){
				return '结束时间不能为空';
			}
		}
	});

	//监听提交
	form.on('submit(demo1)', function(data) {
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

	var start = {
		istime: true, 
		format: 'YYYY-MM-DD hh:mm:ss', 
		festival: false,
	    choose: function(datas){
	      end.min = datas; //开始日选好后，重置结束日的最小日期
	      end.start = datas //将结束日的初始值设定为开始日
	    }
	  };
	  
	  var end = {
	  	istime: true, 
		format: 'YYYY-MM-DD hh:mm:ss', 
		festival: false,
	    choose: function(datas){
	      start.max = datas; //结束日选好后，重置开始日的最大日期
	    }
	  };
	  
	  document.getElementById('start_time').onclick = function(){
	    start.elem = this;
	    laydate(start);
	  }
	  document.getElementById('end_time').onclick = function(){
	    end.elem = this
	    laydate(end);
	  }
});