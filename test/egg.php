<?php

$a = '%e7%99%bc%e5%8d%a1%e9%8a%80%e8%a1%8c%e6%8b%92%e7%b5%95%e4%ba%a4%e6%98%93';
echo urldecode($a);
exit;
for($i=1;$i<=30;$i++){
	echo $i;
	echo ' = ';
	echo dechex(($i * 8) + 6);
	echo '<br />';
}