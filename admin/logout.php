<?php
require_once ( "inc/init.php" );

$Backend = new Routine\Permission();
$Backend->unRegisterSession();
header('Location: index.php');