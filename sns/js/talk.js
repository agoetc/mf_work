//IE対策
$.ajaxSetup({ cache: false });

var from_id = "";
var to_id = "";

function update(){
	$.getJSON("../php/show_message.php", {to_id: to_id}, function(data){
		var html = "";
		for(i = 0; i < data.length; i++){
			if(data[i]["from_id"] === from_id){
				html += "<font color=red>" + data[i]["message"] + "</font>";
			}else{
				html += data[i]["message"];
			}
			html += "<hr>";
		}
		$("#main").html(html);
	});
}

$(function(){
	setInterval(update, 1000);
	
	from_id = $("#from_id").val();
	to_id = $("#to_id").val();
	
	$("#submit").click(function(){
		var data = {
			from_id: from_id,
			to_id: to_id,
			message: $("#message").val()
		};
	
		$.post("../php/add_message.php", data, update);
		
		$("#message").val("");
		resize();
	});
});
