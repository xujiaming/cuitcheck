$(document).ready(function(){
	$("#user").css({"z-index":"1"}); 
	
	$(".jump").click(function(){
		var s = $(".in").attr("id");
		alert(s);
		if(s == "user"){
			$("#user").css({"z-index":"0"}); 
			$("#admin").css({"z-index":"1"}); 
		}
		if(s == "admin"){
			$("#user").css({"z-index":"1"}); 
			$("#admin").css({"z-index":"0"});
		}
	})
})