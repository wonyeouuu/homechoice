<?php
use Model\Db\Db;
use Model\Log\Log;
require_once '../connect.php';

$row = array (
		'UserID' => 1,
		'Name' => '凃振祐',
		'Phone' => '0935185201',
		'QuickNo' => '1000201008',
		'Address' => '中山路72號',
		'PaymentType' => 1,
		'BalanceUsed' => 100,
		'Products' => array (
				array (
						'ProductID' => 1,
						'Qty' => 3,
						'Ucoin' => 15 
				),
				array (
						'ProductID' => 2,
						'Qty' => 1,
						'Ucoin' => 40 
				) 
		) 
);

$Set = new Model\Order\Set ();

$Set->addNewOrder ( $row );

exit ();

$db = Db::getInstance ();

$db->beginTransaction ();

$sql = 'insert into tb_Order (`UserID`, `Name`, `CreateDateTime`)
		values (:UserID, :Name, NOW())';
$std = $db->prepare ( $sql );
$bound = array (
		'UserID' => 2,
		'Name' => '凃振祐' 
);

if ($std->execute ( $bound )) {
	$OrderID = $db->lastInsertId ();
	
	$sql = 'insert into tb_OrderProduct (`OrderID`, `ProductID`, `Qty`, `Price`, `SubtotalPrice`)
			values (:OrderID, :ProductID, :Qty, :Price, :SubtotalPrice)';
	$std = $db->prepare ( $sql );
	$bound = array (
			'OrderID' => $OrderID,
			'ProductID' => 1,
			'Qty' => 3,
			'Price' => 100,
			'SubtotalPrice' => 300 
	);
	if (! $std->execute ( $bound )) {
		Log::log ( $std->errorInfo () );
		Log::log ( $sql );
		$db->rollBack ();
		return false;
	}
	$db->commit ();
}

?>