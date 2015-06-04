<?php
?>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script>
function ajax(){
	$.ajax({
		url: 'http://api.umesh.tw/api/user/login',
		type: 'post',
		dataType: 'json',
		data: {
			UserID: 'jeff',
			Pwd: '123456'
		},
		success: function(response){
			console.log(response);
		},
// 		xhrFields: {
// 			      withCredentials: true
// 		}
	});
}
function ajax2(){
	$.ajax({
		url: 'http://api.umesh.tw/api/user/login',
		type: 'post',
		dataType: 'json',
		data: {
			UserID: 'jeff',
			Pwd: '123456'
		},
		success: function(response){
			console.log(response);
		},
// 		xhrFields: {
// 			      withCredentials: true
// 		}
	});
}
function ajax3(){
	$.ajax({
		url: 'http://api.umesh.tw/test/echoserver.php',
		type: 'post',
		dataType: 'json',
		data: {
			UserID: 'jeff',
			Pwd: '123456'
		},
		success: function(response){
			console.log(response);
		},
		xhrFields: {
			      withCredentials: true
		}
	});
}
$(function(){
	
})
</script>
</head>
<body>
	<button onclick="ajax()">click</button>
	<button onclick="ajax2()">click</button>
	<button onclick="ajax3()">echo server</button>
</body>
</html>