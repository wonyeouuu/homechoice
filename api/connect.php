<?php
require_once dirname(dirname(__FILE__)).'/connect.php';

if(isset($_REQUEST['session_id'])){
	session_id($_REQUEST['session_id']);
}

@session_start();
