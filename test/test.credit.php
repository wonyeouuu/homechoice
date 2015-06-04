<?php
// echo strtoupper(sha1('S140327903588881500'));
$merchantCode = 'S1404080176';
$keygen = 'umesh9802';
$price = '5';
$odrInfo = array (
		'odrName' => 'JeffTu',
		'odrPhone' => '0935165728',
		'odrEmail' => 'athelok0614@gmail.com' 
);
$odrId = 1;

?>
<html>
<head>
</head>
<body>


	<form name="form1" action="https://www.esafe.com.tw/Service/Etopm.aspx" method="POST">
		<input type="submit" name="button" id="button" value="送出" /> <input type="submit" class="btn_order btnFace fl-R" value="" /> <input type="hidden" name="web"
			value="<?php echo urlencode($merchantCode);?>" /> <input type="hidden" name="MN" value="<?php echo urlencode($price)?>" /> <input type="hidden" name="Td" value="<?php echo urlencode($odrId)?>" /> <input
			type="hidden" name="sna" value="<?php echo urlencode($odrInfo['odrName'])?>" /> <input type="hidden" name="sdt" value="<?php echo urlencode($odrInfo['odrPhone'])?>" /> <input type="hidden"
			name="email" value="<?php echo urlencode($odrInfo['odrEmail'])?>" /> <input type="hidden" name="ChkValue" value="<?php echo (strtoupper(sha1($merchantCode.$keygen.$price)));?>" />
	</form>
</body>
</html>