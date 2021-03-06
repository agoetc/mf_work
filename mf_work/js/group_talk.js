//IE対策
$.ajaxSetup({ cache: false });

var from_id = "";
var group_id = "";

function update(){
	$.getJSON("../php/show_group_message.php", {group_id: group_id}, function(data){
		var html = "";
		for(i = 0; i < data.length; i++){
			if(data[i]["from_id"] === from_id){
				html += "<font color=red>自分</font>：<br>" + data[i]["message"];
			}else{
				html += "<font color=blue>" + data[i]["from_id"] + "</font>：<br>" + data[i]["message"];
			}
			html += "<hr>";
		}
		$("#main").html(html);
	});
}

$(function(){
	setInterval(update, 1000);
	
	from_id = $("#from_id").val();
	group_id = $("#group_id").val();
	
	$("#submit").click(function(){
		var data = {
			from_id: from_id,
			group_id: group_id,
			message: $("#message").val()
		};
	
		$.post("../php/add_group_message.php", data, update);
		
		$("#message").val("");
		resize();
	});
});