layui.config({
	base: rooturl+'Public/static/js/'
}).use(['form','layer','laypage','laydate','validator','upload'], function() {
	var $ = layui.jquery,
	form = layui.form(),
	layer = layui.layer,
	laypage = layui.laypage,
	validator = layui.validator,
	laydate = layui.laydate;
	
	//点击搜索按钮的触发事
	form.render('select');
	$("#search").on('click', function() {
		var dcol = $('#demo-col').val();
		var dmaj = $('#demo-maj').val();
		var dgra = $('#demo-gra').val();
		var dcla = $('#demo-cla').val();
		var keyword = $('#keyword').val();
		var searchword = "";
		if(dcol== "" && dmaj== "" && dgra== "" && dcla== "" && keyword == "") {
			layer.msg('请先输入内容', {time:1000});
		}else {
				searchword = searchword+"&dcol="+dcol+"&dmaj="+dmaj+"&dgra="+dgra+"&dcla="+dcla;			
				window.location.href=listurl+"?keyword="+keyword+searchword;
				}	
	});
	//点击查看全部的事件处理
	$('#searchAll').on('click', function(){

		window.location.href=listurl;
	});

	//添加学生事件
	$(".add").click(function() {
		layer.open({
			title: '添加学生',
			type: 2,
			shadeClose: true,
			shade: 0.8,
			fix:true,
			shift: 2,
			maxmin: true,
			area: ['480px', '500px'],
			content: addurl,
			scrollbar: false,
		});
	});
	//学院专业年级与班级的联动查询
	// form.render('select');
	// form.on('select(grade)',function(data) {
	// 	$.ajax({
	// 		url: linkselecturl,
	// 		type: 'POST',
	// 		data: 'grade_id='+$('#gra').val(),
	// 		//contentType: "application/json; charset=utf-8",
	// 		dataTpye: 'json',
	// 		success:function(data) {
	// 			$('form').find('select[name=class_id]').html(data).parent().show();
	// 			form.render();
	// 		}
	// 	});
	// });

	//学院专业与班级联动选择
	//form.render('select');
	// form.on('select(collegeSS)',function() {
	// 	console.log("45456");
	// 	var dept_id = $('#demo-col').val();
	// 	console.log(dept_id);
	// 	$.ajax({
	// 		url: linkselecturl,
	// 		type: 'POST',
	// 		data: {'dept_id': dept_id},
	// 		//contentType: "application/json; charset=utf-8",
	// 		dataTpye: 'json',
	// 		success: function (data) {
	// 			console.log(data);
	// 			$('form').find('select[name=majorSS]').html(data.major).parent().show();
	// 			$('form').find('select[name=classSS]').html(data.class).parent().show();
	// 			form.render('select');
	// 		}
	// 	});
	// });

	form.render();
	form.on('submit(addsave)', function(rec) {
		if(!validator.IsNotEmpty(rec.field['account'])) {
				layer.msg('请填写账户！');
			}else if(!validator.IsNotEmpty(rec.field['name'])) {
				layer.msg('请填写姓名！');
			}else {
				$.ajax({
				url: addhandleurl,
				type: 'POST',
				data: rec.field,
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
							parent.location.href = afteraddurl;
						});
					}else{
						layer.msg(data.msg, {
							time: 1000
						});
					}
				}
			});
			}
		return false;
	});
	$(".detail").on('click', function() {
		var id = $(this).data('id');
		//弹出即全屏
		var index = layer.open({
		  type: 2,
		  title: ['学生详细信息', 'text-align:center;'],
		  content: detailurl+"?id="+id,
		  area: ['320px', '195px'],
		  maxmin: true,
		});
		layer.full(index);
	});
	//编辑学生信息
	$(".edit").on('click', function() {
		var id = $(this).data('id');
		layer.open({
			type:2,
			title: ['修改学生信息', 'text-align:center;'],
			area: ['480px', '500px'],
			scrollbar: false,
			content:editurl+"?id="+id,
		});
	});
	form.on('submit(editsave)', function(rec) {
		if(!validator.IsNotEmpty(rec.field['account'])) {
				layer.msg('请填写账户！');
			}else if(!validator.IsNotEmpty(rec.field['name'])) {
				layer.msg('请填写姓名！');
			}else {
				$.ajax({
				url: edithandleurl,
				type: 'POST',
				data: rec.field,
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
							parent.location.href = afterediturl;
						});
					}else{
						layer.msg(data.msg, {
							time: 1000
						});
					}
				}
			});
			}
		return false;
	});
	//删除学生
	$('.del').on('click', function(){

		var id = $(this).data('id');
		layer.confirm('确定删除该学生?', {
			icon: 3,
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
	//点击重置密码
	$('.resetps').on('click', function() {
		var id = $(this).data('id');
		layer.confirm('确定将该账号的密码重置为学生学号?', {
			icon: 3,
			btn: ['确定', '取消']
		}, function(){
			$.ajax({
				url: resetpsurl,
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
	//点击批量导入的事件
	$('.batchadd').click(function() {
		layer.open({
			title: ['批量导入学生','text-align:center'],
			type: 2,
			shadeClose: true,
			shade: 0.8,
			fix:true,
			shift: 2,
			maxmin: true,
			area: ['680px', '410px'],
			content: batchurl,
			scrollbar: false,
		});
	});
	

});