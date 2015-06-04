<?php require_once '../connect.php';?>
<!DOCTYPE html>
<html>
<head>
<title>Umesh API Platform</title>
<script src="js/jquery-1.11.0.min.js"></script>
<link href="css/bootstrap.css" rel="stylesheet">
<script src="js/bootstrap.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
	$(function(){
			
// 			var url = 'http://api.umesh.tw/api/user/register';
			var url = '<?php echo API_URL;?>/user/register';
			var data = {
					Account: 'jeff'+Math.floor((Math.random() * 100) + 1),
					Name: 'Jeff Tu',
					Gender: '男',
					Pwd: '123456',
// 					Address: [
// 					               {QuickNo:"6300100002",Name:"台北市松山區莊敬里"},
// 					               {QuickNo:"6300100003",Name:"八德路三段11號7F"},
					               
// 					],
// 					Socie: [
// // 					            {QuickNo: '1140130002'}, {QuickNo: '1140130001'}
// 					],
// 					Community: [],
					QuickNo: '6300100003',
					Address: '八德路三段11號7F',
					Phone: '0976362717',
					Email: 'athelok0614@gmail.com',
					Birthday: '1982/7/12',
					
			};
			$.ajax({
				url: url,
				type: 'post',
				dataType: 'html',
				data: data,
				success: function(response){
					console.log(response);
					$('#data').html(JSON.stringify(data));
					$('#result').html(response);
				}
			});
			return false;

	})
</script>
<style>
.methods {
	/*     	float:left; */
	/*     	margin-right:30px; */
	
}
</style>
</head>
<body>
	send data:
	<div id="data"></div>
	result:
	<div id="result"></div>

</body>
</html>