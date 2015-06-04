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
			
		var url = '<?php echo API_URL;?>/survey/addanswer';
					var data = {
					SurveyID: 1,
					QuestionList: [
					               {
						               QuestionID: 1,
						               Answer1: 1,
						               Answer2: 1,
						               Answer5: 1
					               }
					]
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
	send data: <div id="data"></div>
	result: <div id="result"></div>

</body>
</html>