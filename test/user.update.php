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
		var urlDefault='<?php echo API_URL?>';
		var data = {
				Account: '0981886453',
				Pwd: '123456'
		};
		$.ajax({
			url: '<?php echo API_URL?>/user/login',
			type: 'post',
			dataType: 'json',
			data: data,
			success: function(response){
				if(response.success && response.success == '1'){
					var data = {
							Name: 'xiuce',
							Account: '0981886453',
							Email: 'xiuce@dreamercs.tw',
							Address: [
							          {
								          QuickNo: '6301000009',
								          Name: ''
							          }
							]
					}
					$.ajax({
						url: '<?php echo API_URL?>/user/update',
						type: 'post',
						dataType: 'json',
						data: data,
						success: function(response){
							console.log(response);
						}
					})
				}
			}
		})	

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