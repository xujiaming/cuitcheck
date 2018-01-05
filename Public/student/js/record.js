

layui.use(['laypage', 'layer','form'], function() {
    var laypage = layui.laypage,
    	form = layui.form(),
        layer = layui.layer;

	
    $(function(){
    	getRecordList();
    });
    // 获得考试记录
    function getRecordList(type = '', requestPage = 1){

    	$("#recordlist").html("");
    	$.getJSON(recordlist, {'type':type, 'requestPage':requestPage}, function(data){
    		var list = eval(data.list);		// 获得试卷信息
    		// console.log(list);
    		$.each(list, function(index, item) {
    			var head = '<div class="ui card" style="margin-left: 2%;">';
    			var paper_name = '<div class="content"><div class="header">'+item.name+'</div></div>';
    			var content_head = '<div class="content">';
    			var author = '<h4 class="ui sub header">出题人：'+item.create_by+'</h4>';
    			var paper_head = '<div class="ui small feed">';
    			var full_score = '<div class="event"><div class="content"><div class="summary">试卷总分：'+item.full_score+'</div></div></div>';
    			var pass_score = '<div class="event"><div class="content"><div class="summary">及格分：'+item.pass_score+'</div></div></div>';
    			var start_time = '<div class="event"><div class="content"><div class="summary">考试时间：'+item.start_time+'</div></div></div>';
    			var paper_end = '</div>';
    			var button = '<div class="extra content" style="text-align: center;"><button id="record_detail" data-id='+item.testpaper_id+' class="ui button">查看详情</button></div>';
    			var content_end = '</div>';
    			var end = '</div>';
    			var html = head + paper_name + content_head + author + paper_head + full_score + pass_score + start_time + paper_end + button + content_end + end;
    			$("#recordlist").append(html); 
    		});

    		// 分页信息
		 	laypage({
                cont: 'record_page',
                pages: data.pages,
                skin: '#4db4fc',
                curr: requestPage,
                 jump: function(obj, first) {
                    //得到了当前页，用于向服务端请求对应数据
                    var curr = obj.curr;
                    if(!first) {
                       getRecordList(type, curr);
                    }
                }
            });
    		 
    	});


    }

   	//监听select选择框
    $('#record_type').change(function() {
    	var type = $("#record_type").val();
        getRecordList(type);
    });

    // 扩展post提交数据, 并进行跳转
 	$.extend({
	    StandardPost:function(url,args){
	        var body = $(document.body),
	            form = $("<form method='post'></form>"),
	            input;
	        form.attr({"action":url});
	        $.each(args,function(key,value){
	            input = $("<input type='hidden'>");
	            input.attr({"name":key});
	            input.val(value);
	            form.append(input);
	        });

	        form.appendTo(document.body);
	        form.submit();
	        document.body.removeChild(form[0]);
	    }
	});

     $(document).on('click', '#record_detail', function() {
     	var testpaper_id = $(this).data('id');
     	// post提交并跳转
     	$.StandardPost(recordDetail, {'testpaper_id':testpaper_id});
     });
});