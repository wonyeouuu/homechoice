<?php
function go_to($url) {
	echo "<script>";
	echo "location.href='" . $url . "'";
	echo "</script>";
	exit ();
}
function msg($title, $content = null, $url = null) {
	echo "<script>\r\n";
	echo "$.SmartMessageBox({";
	if ($content) {
		echo "content  : \"".$content."\",";
	}
	echo "title : \"" . $title . "\",";
	echo "}";
	if ($url !== null) {
		echo ", function(ButtonPressed) {";
		echo "location.href='" . $url . "'";
		echo "}";
	}
	echo ");";
	echo "</script>\r\n";
}

function alert($message){
	echo "<script type='text/javascript'>alert('".(string)$message."');</script>";
}
