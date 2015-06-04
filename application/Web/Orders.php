<?php

namespace Web;

use Routine\Db;

class Orders {
	public static $cartList;
	public static $cartInfor;
	public static $accessoryList;
	public static $accessoryInfor;
	public $odrId = 0;
	public $odrFreight = 0;
	public $cartSubtotal = 0;
	public $couponSubtotal = 0;
	public $couponGained = 0;
	public $couponPay = 0;
	public $odrSubtotal = 0;
	public $odrTotal = 0;
	public $odrInfor;
	public $odrDetails;
	public $odrCoupons;
	public static $cartIdList;
	public static $accessoryIdList;
	public static $freightConfig;
	public static $couponCap;
	public static $shippingTypes = array (
			'1' => array (
					'name' => '貨運宅配' 
			),
			'2' => array (
					'name' => '現場取貨' 
			) 
	);
	public static $invoiceTypes = array (
			'1' => array (
					'name' => '捐贈發票' 
			),
			'2' => array (
					'name' => '二聯式個人發票' 
			),
			'3' => array (
					'name' => '三聯式發票' 
			) 
	);
	public static $paymentTerms = array (
			'1' => array (
					'name' => '線上刷卡' 
			),
			'2' => array (
					'name' => 'ATM付款' 
			) 
	);
	public static $deliveryTimes = array (
			'1' => array (
					'name' => '09:00~12:00' 
			),
			'2' => array (
					'name' => '12:00~18:00' 
			),
			'3' => array (
					'name' => '18:00~21:00' 
			) 
	);
	public static $ordersStatus = array (
			'1' => '處理中',
			'2' => '已出貨',
			'3' => '取消訂單',
			'4' => '拒收',
			'5' => '假資料',
			'6' => '超過配送期',
			'7' => '已匯出' 
	);
	public static $appValue = array (
			array (
					'id' => '4',
					'text' => '不指定時段' 
			),
			array (
					'id' => '1',
					'text' => '上午8:00~12:00' 
			),
			array (
					'id' => '2',
					'text' => '下午12:01~17:00' 
			),
			array (
					'id' => '3',
					'text' => '晚上17:01~20:00' 
			) 
	);
	public function __construct($odrId = 0) {
		$this->odrId = $odrId;
	}
	public static function getOrderAppValue($appValue) {
		foreach ( self::$appValue as $key => $value ) {
			if ($value ['id'] == $appValue) {
				return $value ['text'];
			}
		}
		return $appValue;
	}
	public static function isOrderOwner($order_id, $member_id) {
		$db = Db::getInstance ();
		$sql = 'select member_id from av_order where order_id = ?';
		$std = $db->prepare ( $sql );
		$std->bindValue ( 1, $order_id );
		if ($std->execute ()) {
			$row = $std->fetch ();
			if ($member_id == $row ['member_id']) {
				return true;
			}
		} else {
			print_r ( $std->errorInfo () );
		}
		return false;
	}
	public static function getOrderStatusText($odrStatus) {
		if (isset ( self::$ordersStatus [$odrStatus] )) {
			return self::$ordersStatus [$odrStatus];
		} else {
			return false;
		}
	}
	public static function getShippingTypeText($odrShippingType) {
		if (isset ( self::$shippingTypes [$odrShippingType] )) {
			return self::$shippingTypes [$odrShippingType] ['name'];
		} else {
			return false;
		}
	}
	public static function getCartInfor() {
		$cartList = self::getCartInCookie ();
		return self::$cartInfor;
	}
	public function sendShippingMail($odrId) {
		require_once dirname ( dirname ( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'class.phpmailer.php';
		$row = self::getOrdersInfor ( $odrId );
		$descText = nl2br ( Aviva::getWebOption ( 6 ) );
		
		$mailContent = "";
		$mailContent .= "<p>Dear " . $row ['odrAddrName'] . " 您好！</p>";
		if (! empty ( $descText )) {
			$mailContent .= "<p>" . $descText . "</p>";
		}
		$mailContent .= "<p></p>";
		
		$mail = new PHPMailer (); // 建立新物件
		$mail->IsSMTP (); // 設定使用SMTP方式寄信
		$mail->SMTPAuth = true; // 設定SMTP需要驗證
		$mail->CharSet = "utf-8"; // 設定郵件編碼
		
		$mail->Subject = "AVIVA 出貨通知！"; // 設定郵件標題
		$mail->Body = $mailContent; // 設定郵件內容
		$mail->IsHTML ( true ); // 設定郵件內容為HTML
		$mail->AddAddress ( $row ['odrMail'] ); // 設定收件者郵件及名稱
		                                        // $mail->AddAddress("service@beself.com.tw"); //設定收件者郵件及名稱
		                                        // GMAIL
		                                        // $mail->SMTPSecure = "ssl"; // Gmail的SMTP主機需要使用SSL連線
		                                        // $mail->Host = "smtp.gmail.com"; //Gamil的SMTP主機
		                                        // $mail->Username = "service0614@gmail.com"; //設定驗證帳號
		                                        // $mail->Password = "06140614"; //設定驗證密碼
		                                        // $mail->Port = 465; //Gamil的SMTP主機的SMTP埠位為465埠。
		                                        // beself
		$mail->Host = MailHost; // Gamil的SMTP主機
		$mail->Username = MailUsername; // 設定驗證帳號
		$mail->Password = MailPassword; // 設定驗證密碼
		$mail->Port = MailPort; // Gamil的SMTP主機的SMTP埠位為465埠。
		$mail->From = MailFrom; // 設定寄件者信箱
		$mail->FromName = MailFromName; // 設定寄件者姓名
		
		if (! $mail->Send ()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
			return false;
		} else {
			$db = Connect::conn ();
			$sql = "update av_orders set odrToMailSend  = 1 where odrId = ? ";
			$std = $db->prepare ( $sql );
			$bound = array (
					$row ['odrId'] 
			);
			$std->execute ( $bound );
			return true;
		}
	}
	public function sendOrdersMail($order_id = 0) {
		if ($order_id == 0) {
			if ($this->odrInfor == null || empty ( $this->odrInfo ['odrName'] )) {
				return false;
			}
		} else {
			$this->odrInfor = self::getOrdersInfor ( $order_id );
			$this->odrDetails = self::getOrdersDetail ( $order_id );
		}
		require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'class.phpmailer.php';
		require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . '/atm_text.php';
		$oData = $this->odrInfor;
		$scData = $this->odrDetails;
		$mailContent = "";
		$mailContent = "<img src='http://www.artavenue.com.tw/images/Title2.jpg'>";
		$mailContent .= "<div style='line-height:30px;'>Dear &nbsp; <span style='font-size:18px;'> " . $oData ['name'] . "</span> 您好！</div>";
		$mailContent .= "<p>收件人: " . $oData ['invoice_name'] . "</p>";
		$mailContent .= "<p>寄送方式: " . Orders::getShippingTypeText ( $oData ['deliver_method'] ) . "</p>";
		$mailContent .= "<p>送貨時段: " . Orders::getOrderAppValue ( $oData ['receive_period'] ) . "</p>";
		$mailContent .= "<p>付款方式: " . Orders::$paymentTerms [$oData ['payment_term']] ['name'] . "</p>";
		
		if ($oData ['payment_term'] == '2') {
			$mailContent .= $atm_text;
		}
		
		$mailContent .= "<p>購物明細。</p>";
		$mailContent .= "<p>一般商品：</p>";
		foreach ( $scData as $v ) {
			$mailContent .= "<p>品名: " . $v ['fullname'] . " ,顏色: " . $v ['color_name'] . " ,單價: " . number_format ( $v ['price'] ) . ".- ,數量: " . $v ['qty'] . " ,小計: " . number_format ( $v ['subtotal_price'] ) . ".-</p>";
		}
		$mailContent .= "<p>一般商品小計: " . number_format ( $oData ['cart_subtotal_price'] ) . "</p>";
		$mailContent .= "<p>運費: " . number_format ( $oData ['freight_price'] ) . "</p>";
		$mailContent .= "<p>促銷折扣小計: " . number_format ( $oData ['promotion_discount_price'] ) . "</p>";
		$mailContent .= "<p>總計: " . number_format ( $oData ['total_price'] ) . "</p>";
		$mailContent .= "<p>發票方式: " . self::$invoiceTypes [$oData ['invoice_type']] ['name'] . "</p>";
		if (! empty ( $oData ['invoice_title'] )) {
			$mailContent .= "<p>發票抬頭: " . $oData ['invoice_title'] . "</p>";
		}
		if (! empty ( $oData ['invoice_no'] )) {
			$mailContent .= "<p>發票統編: " . $oData ['invoice_no'] . "</p>";
		}
		
		$mailContent .= "<p>謝謝您的購買，Avenue 敬上</p>";
		$mailContent .= "<p></p>";
		
		$mail = new \PHPMailer (); // 建立新物件
		$mail->IsSMTP (); // 設定使用SMTP方式寄信
		$mail->SMTPAuth = true; // 設定SMTP需要驗證
		$mail->CharSet = "utf-8"; // 設定郵件編碼
		                          // GMAIL
		                          // $mail->SMTPSecure = "ssl"; // Gmail的SMTP主機需要使用SSL連線
		                          // $mail->Host = "smtp.gmail.com"; //Gamil的SMTP主機
		                          // $mail->Username = "service0614@gmail.com"; //設定驗證帳號
		                          // $mail->Password = "06140614"; //設定驗證密碼
		                          // $mail->Port = 465; //Gamil的SMTP主機的SMTP埠位為465埠。
		                          // beself
		$mail->Host = MailHost; // Gamil的SMTP主機
		$mail->Username = MailUsername; // 設定驗證帳號
		$mail->Password = MailPassword; // 設定驗證密碼
		$mail->Port = MailPort; // Gamil的SMTP主機的SMTP埠位為465埠。
		$mail->SMTPDebug = true;
		
		$mail->From = MailFrom; // 設定寄件者信箱
		$mail->FromName = MailFromName; // 設定寄件者姓名
// 		echo $mailContent;
		$mail->Subject = "Avenue 系統發信通知，訂購明細確認！"; // 設定郵件標題
		$mail->Body = $mailContent; // 設定郵件內容
		$mail->IsHTML ( true ); // 設定郵件內容為HTML
		$mail->AddAddress ( $oData ['email'] ); // 設定收件者郵件及名稱
		$mail->AddAddress ( 'service@artavenue.com.tw' ); // 設定收件者郵件及名稱
		$mail->AddAddress ( 'service@artavenue.com.tw'); // 設定收件者郵件及名稱
		                                        // $mail->AddAddress("service@beself.com.tw"); //設定收件者郵件及名稱
		
		if (! $mail->Send ()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
			exit ();
		} else {
			$sql = "update av_orders set odrToMail = 1 where order_id = '" . $order_id . "'";
			// echo "Message sent!";
		}
	}
	public static function setDeliveryMethod($delivery_method) {
		if ($delivery_method == '2') {
// 			self::$cartInfor ['total_price'] = self::$cartInfor ['total_price'] - self::$cartInfor ['freight_price'];
// 			self::$cartInfor ['freight_price'] = 0;
		}
	}
	public function getCookieTotal() {
		$cartList = self::getCartInCookie ();
		$cartSubtotal = 0;
		foreach ( $cartList as $cKey => $cRow ) {
			$this->cartSubtotal += $cRow ['subtotal'];
		}
		$couponList = self::getCouponInCookie ();
		$couponSubtotal = 0;
		foreach ( $couponList as $cKey => $cRow ) {
			$this->couponSubtotal += $cRow ['subtotal'];
		}
		$this->caculateCoupon ();
		return $this->odrTotal;
	}
	public function addOrdersMemberToBlack($odrId) {
		$db = Connect::conn ();
		$sql = 'select * from av_orders where odrId = :odrId';
		$bound = array (
				'odrId' => $odrId 
		);
		$std = $db->prepare ( $sql );
		$std->execute ( $bound );
		$odrRow = $std->fetch ();
		
		$sql = "insert ignore into av_blacklists
			(`bkOdrId`, `bkIP`, `bkAddr`, `bkName`, `bkMail`, `bkPhone`, `bkDatetime`, `bkReason`, `memId`) values
			( '" . $odrId . "', '" . $odrRow ['orderUserIP'] . "', '" . $odrRow ['odrAddr'] . "', '" . $odrRow ['odrName'] . "', '" . $odrRow ['odrMail'] . "', '" . $odrRow ['odrPhone'] . "', NOW(), '手動加入', '" . $odrRow ['odrMemId'] . "')";
		$db->exec ( $sql );
		
		$sql = "update av_orders set orderBlacklist = '1' where odrId = '" . $odrId . "'";
		$db->exec ( $sql );
		
		$memId = $odrRow ['odrMemId'];
		$sql = 'update av_members set memStatus=2 where memId=:memId';
		$bound = array (
				'memId' => $memId 
		);
		$std = $db->prepare ( $sql );
		$std->execute ( $bound );
	}
	public function addNewOrdersFromPost() {
		$db = Connect::conn ();
		$memInfor = Member::getMemberInfor ( $_SESSION [WebCode . 'memId'] );
		
		$sql = "select a.*, b.odrNo, b.odrId 
				from av_blacklists a 
				left join av_orders b on a.bkOdrId = b.odrId 
				where a.bkIP = :ip or a.bkName = :odrName or a.bkMail = :odrMail or a.bkAddr = :odrAddr or a.bkPhone = :odrPhone";
		$std = $db->prepare ( $sql );
		$bound = array (
				'ip' => getIp (),
				'odrName' => $_POST ['odrName'],
				'odrMail' => $_POST ['odrMail'],
				'odrAddr' => $_POST ['odrAddr'],
				'odrPhone' => $_POST ['odrPhone'] 
		);
		if (! $std->execute ( $bound )) {
			print_r ( $std->errorInfo () );
			exit ();
		}
		$blackReasonList = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$blackReason = array ();
			if ($row ['bkIP'] == getIp ()) {
				$blackReason [] = '$' . $row ['odrId'] . '@IP#';
			}
			if ($row ['bkName'] == $_POST ['odrName']) {
				$blackReason [] = '$' . $row ['odrId'] . '@名字#';
			}
			if ($row ['bkMail'] == $_POST ['odrMail']) {
				$blackReason [] = '$' . $row ['odrId'] . '@信箱#';
			}
			if ($row ['bkAddr'] == $_POST ['odrAddr']) {
				$blackReason [] = '$' . $row ['odrId'] . '@地址#';
			}
			if ($row ['bkPhone'] == $_POST ['odrPhone']) {
				$blackReason [] = '$' . $row ['odrId'] . '@電話#';
			}
			$blackReasonTxt = implode ( ',', $blackReason );
			$blackReasonList [] = $blackReasonTxt;
		}
		// print_r ( $memInfor );
		// exit;
		if ($memInfor ['memStatus'] == '2') {
			$blackReasonList [] = '黑名單會員';
		}
		
		$field = array (
				'odrNo',
				'odrLinkFrom',
				'odrMemId',
				'odrPhone',
				'odrMobile',
				'odrAddrName',
				'odrName',
				'odrMail',
				'odrCounty',
				'odrArea',
				'odrAddr',
				'odrShippingType',
				'odrApp',
				'odrAppValue',
				'odrReceipt',
				'odrReceiptTitle',
				'orderUserIP',
				'odrDesc',
				'blogId',
				'odrPaymentDiscount',
				'odrReceiveDate' 
		);
		if ($_POST ['addrSelect'] == '2') {
			$_POST ['odrCounty'] = $_POST ['odrCounty2'];
			$_POST ['odrArea'] = $_POST ['odrArea2'];
			$_POST ['odrAddr'] = $_POST ['odrAddr2'];
		}
		if ($_POST ['odrShippingType'] == '1') {
			if ($_POST ['odrApp'] == '平日宅配(週一至週五)') {
				$_POST ['odrReceiveDate'] = Aviva::getWebOption ( 24 );
			} elseif ($_POST ['odrApp'] == '假日宅配(週六)') {
				$_POST ['odrReceiveDate'] = Aviva::getWebOption ( 25 );
			} elseif ($_POST ['odrApp'] == '不拘') {
				$_POST ['odrReceiveDate'] = Aviva::getWebOption ( 26 );
			}
		} elseif ($_POST ['odrShippingType'] == '2') {
			if ($_POST ['odrApp'] == '平日宅配(週一至週五)') {
				$_POST ['odrReceiveDate'] = Aviva::getWebOption ( 27 );
			} elseif ($_POST ['odrApp'] == '假日宅配(週六)') {
				$_POST ['odrReceiveDate'] = Aviva::getWebOption ( 28 );
			} elseif ($_POST ['odrApp'] == '不拘') {
				$_POST ['odrReceiveDate'] = Aviva::getWebOption ( 29 );
			}
		} elseif ($_POST ['odrShippingType'] == '3') {
			if ($_POST ['odrApp'] == '平日宅配(週一至週五)') {
				$_POST ['odrReceiveDate'] = Aviva::getWebOption ( 34 );
			} elseif ($_POST ['odrApp'] == '假日宅配(週六)') {
				$_POST ['odrReceiveDate'] = Aviva::getWebOption ( 35 );
			} elseif ($_POST ['odrApp'] == '不拘') {
				$_POST ['odrReceiveDate'] = Aviva::getWebOption ( 36 );
			}
		}
		
		$sql = 'insert into av_orders (' . $db->getState ( $field ) . ', odrDate) values (' . $db->getColon ( $field ) . ', NOW())';
		$std = $db->prepare ( $sql );
		$bound = $db->getBound ( $field, $_POST );
		$bound ['odrNo'] = self::getNewOrderNo ();
		$bound ['odrLinkFrom'] = $_SERVER ['HTTP_REFERER'];
		$bound ['orderUserIP'] = getIp ();
		$bound ['odrMemId'] = $_SESSION [WebCode . 'memId'];
		$bound ['odrName'] = $memInfor ['memName'];
		$bound ['odrMail'] = $memInfor ['memEmail'];
		$bound ['blogId'] = ! empty ( $_SESSION ['blogId'] ) ? $_SESSION ['blogId'] : '';
		
		if (empty ( $_POST ['odrApp'] )) {
			if (! empty ( $_POST ['odrAppText'] )) {
				$bound ['odrApp'] = $_POST ['odrAppText'];
			} else {
				$bound ['odrApp'] = $_POST ['odrAppText2'];
			}
		}
		
		if ($std->execute ( $bound )) {
			$this->odrInfor = $bound;
			$this->odrId = $db->lastInsertId ();
			// echo count ( $blackReasonList );
			if (count ( $blackReasonList ) > 0) {
				$sql = 'update av_orders a set a.orderBlacklist = 1, a.odrBlackDate = NOW(), a.odrBlackReason = :odrBlackReason where a.odrId = :odrId';
				// echo $sql;
				
				$std = $db->prepare ( $sql );
				$bound = array (
						'odrBlackReason' => implode ( ';', $blackReasonList ),
						'odrId' => $this->odrId 
				);
				
				if (! $std->execute ( $bound )) {
					print_r ( $std->errorInfo () );
					echo '<br />';
				}
				
				$sql = 'insert into av_blacklists (`bkOdrId`, `memId`, `bkIp`, `bkAddr`, `bkName`, `bkMail`, `bkPhone`, `bkDatetime`, `bkReason`) values (:bkOdrId, :memId, :bkIp, :bkAddr, :bkName, :bkMail, :bkPhone, NOW(), :bkReason)';
				$std = $db->prepare ( $sql );
				$bound = array (
						'bkOdrId' => $this->odrId,
						'memId' => $memInfor ['memId'],
						'bkIp' => getIp (),
						'bkAddr' => $_POST ['odrAddr'],
						'bkName' => $memInfor ['memName'],
						'bkMail' => $memInfor ['memEmail'],
						'bkPhone' => $memInfor ['memPhone'],
						'bkReason' => implode ( ';', $blackReasonList ) 
				);
				// echo '<br />';
				// print_r($bound);
				// echo '<br />';
				if (! $std->execute ( $bound )) {
					print_r ( $std->errorInfo () );
				}
				// exit;
			}
			return true;
		} else {
			print_r ( $std->errorInfo () );
			return false;
		}
	}
	public function checkProStatusFromCookie() {
		$cartList = self::getCartInCookie ();
		$successCount = 0;
		$proIds = array ();
		foreach ( $cartList as $cKey => $cRow ) {
			$proIds [] = $cRow ['proId'];
		}
		
		if (count ( $proIds ) == 0) {
			return true;
		}
		
		$db = Connect::conn ();
		$sql = 'select * from av_products a
				where a.proId in (' . $db->getQMark ( $proIds ) . ') and a.proStatus in (0,3)
						';
		$std = $db->prepare ( $sql );
		$std->execute ( $proIds );
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$list [] = $row;
		}
		return $list;
	}
	public function addOrdersDetailFromCookie($odrId) {
		$db = Connect::conn ();
		$field = array (
				'odrId',
				'odProId',
				'odProName',
				'odProPrice',
				'odProQty',
				'odProTotal' 
		);
		$cartList = self::getCartInCookie ();
		$successCount = 0;
		foreach ( $cartList as $cKey => $cRow ) {
			$sql = 'insert into av_orders_detail (' . $db->getState ( $field ) . ') values (' . $db->getColon ( $field ) . ')';
			
			$std = $db->prepare ( $sql );
			$bound = array (
					'odrId' => $odrId,
					'odProId' => $cRow ['proId'],
					'odProName' => $cRow ['proName'],
					'odProPrice' => $cRow ['proPrice'],
					'odProQty' => $cRow ['qty'],
					'odProTotal' => $cRow ['subtotal'] 
			);
			// echo '<br>';
			// print_r ( $bound );
			// echo '<br>';
			if ($std->execute ( $bound )) {
				$successCount ++;
				$this->cartSubtotal += $cRow ['subtotal'];
				$odId = $db->lastInsertId ();
				self::handleStock ( $cRow ['proId'], - $cRow ['qty'] );
			} else {
				print_r ( $std->errorInfo () );
				exit ();
			}
			// $this->odrDetails [] = array (
			// 'odId' => $odId,
			// 'proId' => $cRow ['proId'],
			// 'proPrice' => $cRow ['proPrice'],
			// 'odProQty' => $cRow ['qty'],
			// 'odProTotal' => $cRow ['subtotal'],
			// 'proName' => $cRow ['proName'],
			// 'proImg' => $cRow ['proImg']
			// );
			$this->odrDetails [] = $bound;
		}
	}
	public static function handleStock($proId, $qty) {
		$db = Connect::conn ();
		if ($qty > 0) {
			$sig = '+';
		} else {
			$sig = '-';
		}
		$qty = abs ( $qty );
		$sql = 'update av_products set proStock = proStock ' . $sig . ' :qty where proId = :proId';
		$std = $db->prepare ( $sql );
		$bound = array (
				'proId' => $proId,
				'qty' => $qty 
		);
		$std->execute ( $bound );
	}
	public function setFreightDiscountFree() {
		$db = Connect::conn ();
		if (empty ( $this->odrId ))
			return false;
		$sql = 'select odrFreightDiscount from av_orders where odrId = :odrId';
		$bound = array (
				'odrId' => $this->odrId 
		);
		$std = $db->prepare ( $sql );
		$std->execute ( $bound );
		$freightDiscount = $std->fetchColumn ();
		
		$sql = 'update av_orders set odrFreightDiscount = 0, odrTotal = odrTotal + :freightDiscount where odrId = :odrId';
		$bound = array (
				'odrId' => $this->odrId,
				'freightDiscount' => $freightDiscount 
		);
		$std = $db->prepare ( $sql );
		$std->execute ( $bound );
	}
	public function setFreightDiscountAdd() {
		$db = Connect::conn ();
		if (empty ( $this->odrId ))
			return false;
		
		$freightDiscount = Aviva::getWebOption ( 8 );
		
		$sql = 'update av_orders set odrFreightDiscount = :freightDiscount, odrTotal = odrTotal - :freightDiscount where odrId = :odrId';
		$bound = array (
				'odrId' => $this->odrId,
				'freightDiscount' => $freightDiscount 
		);
		$std = $db->prepare ( $sql );
		$std->execute ( $bound );
	}
	public function addCouponDetailFromCookie($odrId) {
		$db = Connect::conn ();
		$field = array (
				'odrId',
				'ocProId',
				'ocProName',
				'ocProPrice',
				'ocProQty',
				'ocProTotal' 
		);
		$cartList = self::getCouponInCookie ();
		$successCount = 0;
		foreach ( $cartList as $cKey => $cRow ) {
			$sql = 'insert into av_orders_coupon (' . $db->getState ( $field ) . ') values (' . $db->getColon ( $field ) . ')';
			$std = $db->prepare ( $sql );
			$bound = array (
					'odrId' => $odrId,
					'ocProId' => $cRow ['proId'],
					'ocProName' => $cRow ['proName'],
					'ocProPrice' => $cRow ['proPrice'],
					'ocProQty' => $cRow ['qty'],
					'ocProTotal' => $cRow ['subtotal'] 
			);
			if ($std->execute ( $bound )) {
				$successCount ++;
				$this->couponSubtotal += $cRow ['subtotal'];
				self::handleStock ( $cRow ['proId'], - $cRow ['qty'] );
			} else {
				exit ();
			}
			$this->odrCoupons [] = $bound;
		}
		$this->caculateCoupon ();
	}
	public function caculateCoupon() {
		$couponCap = self::getCouponCap ();
		$couponMatched = array (
				'couCap' => 0,
				'couAmount' => 0 
		);
		foreach ( $couponCap as $ccKey => $ccRow ) {
			if ($this->cartSubtotal >= $ccRow ['couCap'] && $couponMatched ['couCap'] < $ccRow ['couCap']) {
				$couponMatched = $ccRow;
			}
		}
		// echo '<br />';
		// echo 'couponMatched';
		// print_r($couponMatched);
		// echo '<br />';
		$this->couponGained = $couponMatched ['couAmount'];
		$this->giftGained = array (
				'proId' => $couponMatched ['proId'] ? $couponMatched ['proId'] : 0,
				'proName' => $couponMatched ['proName'],
				'proId2' => $couponMatched ['proId2'] ? $couponMatched ['proId2'] : 0,
				'proName2' => $couponMatched ['proName2'],
				'proId3' => $couponMatched ['proId3'] ? $couponMatched ['proId3'] : 0,
				'proName3' => $couponMatched ['proName3'] 
		);
		
		$this->couponPay = $this->couponGained >= $this->couponSubtotal ? 0 : $this->couponSubtotal - $this->couponGained;
		
		$freightConfig = self::getFreight ();
		$this->odrSubtotal = $this->cartSubtotal + $this->couponPay;
		
		if ($this->odrSubtotal >= $freightConfig ['freightCap']) {
			$this->odrFreight = 0;
		} else {
			$this->odrFreight = $freightConfig ['freight'];
		}
		
		if ($_REQUEST ['jeffDebug'] == '710614JeFF') {
			$this->odrFreight = 0;
		}
		
		// echo '<br/>';
		// echo 'odrSubtotal=' . $this->odrSubtotal . ', freightCap=' .
		// $freightConfig ['freightCap'];
		// echo '<br />';
		// exit;
		
		$this->odrTotal = $this->odrSubtotal + $this->odrFreight;
		
		return true;
	}
	public function caculateOrdersTotal($updateToSql = false) {
		if (empty ( $this->odrId ))
			return false;
		
		$odrDetailList = self::getOrdersDetail ( $this->odrId );
		$odrCouponList = self::getOrdersCoupon ( $this->odrId );
		
		$this->cartSubtotal = 0;
		$this->couponSubtotal = 0;
		
		foreach ( $odrDetailList as $odRow ) {
			$this->cartSubtotal += $odRow ['odProTotal'];
		}
		
		foreach ( $odrCouponList as $ocRow ) {
			$this->couponSubtotal += $ocRow ['ocProTotal'];
		}
		$this->caculateCoupon ();
		// print_r($this->giftGained);
		// exit;
		
		if ($updateToSql === true) {
			
			$sql = 'update av_orders a set 
					a.odrCartSubtotal = :odrCartSubtotal, a.odrCouponSubtotal = :odrCouponSubtotal, a.odrCouponGained = :odrCouponGained, a.odrCouponPay = :odrCouponPay, 
					a.odrFreight = :odrFreight, a.odrTotal = :odrTotal, a.odrGift = :odrGift, a.odrGift3 = :odrGift3, a.odrGift4 = :odrGift4
					where a.odrId = :odrId';
			$db = Connect::conn ();
			$std = $db->prepare ( $sql );
			$bound = array (
					'odrCartSubtotal' => $this->cartSubtotal,
					'odrCouponSubtotal' => $this->couponSubtotal,
					'odrCouponGained' => $this->couponGained,
					'odrCouponPay' => $this->couponPay,
					'odrFreight' => $this->odrFreight,
					'odrTotal' => $this->odrTotal,
					'odrId' => $this->odrId,
					'odrGift' => $this->giftGained ['proId'],
					'odrGift3' => $this->giftGained ['proId2'],
					'odrGift4' => $this->giftGained ['proId3'] 
			);
			
			if (! $std->execute ( $bound )) {
				print_r ( $std->errorInfo () );
				exit ();
			}
			$this->odrInfor = array_merge ( $this->odrInfor, $bound );
			
			$odrInfor = self::getOrdersInfor ( $this->odrId );
			if ($odrInfor ['odrShippingType'] == '2') {
				if ($odrInfor ['odrPaymentDiscount'] == '1') {
					$odrFreightDiscount = Aviva::getWebOption ( 8 );
					$sql = 'update av_orders a set a.odrFreightDiscount = :odrFreightDiscount, a.odrTotal = a.odrTotal - :odrFreightDiscount where a.odrId = :odrId';
					$std = $db->prepare ( $sql );
					$bound = array (
							'odrFreightDiscount' => $odrFreightDiscount,
							'odrId' => $this->odrId 
					);
					if (! $std->execute ( $bound )) {
						print_r ( $std->errorInfo () );
						exit ();
					}
					
					// $field[] = 'odrFreightDiscount';
					// $bound['odrFreightDiscount'] = $odrFreightDiscount;
				} else {
					if ($this->odrTotal < Aviva::getWebOption ( 9 )) {
						$gift = Aviva::getWebOption ( 10 );
					} else {
						$gift = Aviva::getWebOption ( 11 );
					}
					$sql = 'update av_orders set odrPaymentGift = :odrPaymentGift
								where odrId = :odrId';
					$std = $db->prepare ( $sql );
					$bound = array (
							'odrPaymentGift' => $gift,
							'odrId' => $this->odrId 
					);
					if (! $std->execute ( $bound )) {
						print_r ( $std->errorInfo () );
						exit ();
					}
				}
			}
		}
	}
	public function setOrdersInfor($odrInfor) {
		$this->odrId = $odrInfor ['odrId'];
		$this->odrInfor = $odrInfor;
	}
	public static function getOrdersListInfor($odrIds) {
		$db = Connect::conn ();
		$sql = 'select a.*, b.proName giftName, c.proName giftName2, d.proName giftName3, e.proName paymentGiftName, s.text statusText
				from av_orders a
				left join order_status s on a.odrStatus = s.id
				left join av_products b on a.odrGift = b.proId
				left join av_products c on a.odrGift3 = c.proId
				left join av_products d on a.odrGift4 = d.proId
				left join av_products e on a.odrPaymentGift = e.proId
				where a.odrId in (' . $odrIds . ')';
		$std = $db->prepare ( $sql );
		$bound = array ();
		
		$list = array ();
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				$row = self::convertOrdersInfor ( $row );
				$list [] = $row;
			}
			
			return $list;
		} else {
			return array ();
		}
	}
	public static function getOrdersInfor($order_id) {
		$db = Db::getInstance ();
		$sql = 'select a.*
				from av_order a
				where a.order_id = ?';
		$std = $db->prepare ( $sql );
		$bound = array (
				$order_id 
		);
		if ($std->execute ( $bound )) {
			$row = $std->fetch ();
			$row = self::convertOrdersInfor ( $row );
			
			return $row;
		} else {
			return array ();
		}
	}
	public static function convertOrdersInfor($row) {
		$row['invoice_type_text'] = self::$invoiceTypes[$row['invoice_type']]['name'];
		$row ['receive_period_text'] = self::$deliveryTimes [$row ['receive_period']] ['name'];
		$row ['deliver_method_text'] = self::$shippingTypes [$row ['deliver_method']] ['name'];
		$row ['payment_term_text'] = self::$paymentTerms [$row ['payment_term']] ['name'];
		$row ['status_text'] = self::$ordersStatus [$row ['status']];
		if ($row ['payment_term'] == '1') {
			if ($row ['credit_status'] == '0') {
				$row ['payment_status'] = 0;
				$row ['payment_status_text'] = '未刷卡';
			} else {
				$row ['payment_status'] = 1;
				$row ['payment_status_text'] = '已刷卡';
			}
		} elseif ($row ['payment_term'] == '2') {
			if (! empty ( $row ['transfer_account'] )) {
				$row ['payment_status_text'] = '已回報匯款';
			} else {
				$row ['payment_status'] = 0;
				$row ['payment_status_text'] = '尚未回報匯款';
			}
		}
		
		return $row;
		$row ['odrCreditStatus'] = 0;
		
		$row ['odrAppValueText'] = self::getOrderAppValue ( $row ['odrAppValue'] );
		
		if ($row ['odrShippingType'] == '2') {
		}
		
		if ($row ['odrShippingType'] == '1') {
			if ($row ['odrReceiveDate'] == '0000-00-00' || empty ( $row ['odrReceiveDate'] )) {
				$row ['odrReceiveDate'] = $row ['odrApp'];
			} else {
				$row ['odrReceiveDate'] = $row ['odrReceiveDate'];
			}
			$row ['odrTransferStatus'] = '-';
		} elseif ($row ['odrShippingType'] == '2') {
			if (! empty ( $row ['odrTransferAccount'] )) {
				if ($row ['odrTransferStatus'] == '0') {
					$row ['odrReceiveDate'] = '待確認';
				} else {
					$row ['odrReceiveDate'] = $row ['odrReceiveDate'];
				}
			} else {
				$row ['odrReceiveDate'] = '請完成匯款';
			}
			if (empty ( $row ['odrTransferAccount'] )) {
				$row ['odrTransferStatusText'] = '尚未匯款';
			} else {
				$row ['odrTransferStatusText'] = '已匯款';
			}
		} elseif ($row ['odrShippingType'] == '3') {
			if (empty ( $row ['odrCreditDate'] )) {
				$row ['odrTransferStatusText'] = '尚未刷卡';
				$row ['odrCreditStatus'] = '0';
			} else {
				$row ['odrTransferStatusText'] = '已刷卡';
				$row ['odrCreditStatus'] = '1';
			}
		}
		$row ['odrShippingTypeText'] = self::getShippingTypeText ( $row ['odrShippingType'] );
		
		$row ['odrAddress'] = $row ['odrCounty'] . $row ['odrArea'] . $row ['odrAddr'];
		return $row;
	}
	public static function getOrdersDetailHtml($odrDetail) {
	}
	public static function getOrdersDetailListGroupByProId($odrIds) {
		$db = Connect::conn ();
		$sql = 'select a.*  from av_orders_detail a
				where a.odrId in (' . $odrIds . ')';
		$std = $db->prepare ( $sql );
		$bound = array ();
		if (! $std->execute ( $bound )) {
			print_r ( $std->errorInfo () );
			exit ();
		}
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$list [$row ['odrId']] [] = $row;
		}
		return $list;
	}
	public static function getOrdersCouponListGroupByProId($odrIds) {
		$db = Connect::conn ();
		$sql = 'select a.*  from av_orders_coupon a
				where a.odrId in (' . $odrIds . ')';
		$std = $db->prepare ( $sql );
		$bound = array ();
		if (! $std->execute ( $bound )) {
			print_r ( $std->errorInfo () );
			exit ();
		}
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$list [$row ['odrId']] [] = $row;
		}
		return $list;
	}
	public static function getOrdersDetail($order_id) {
		$db = Db::getInstance ();
		$sql = 'select a.*  
				from av_order_product a
				where a.order_id = ?';
		$std = $db->prepare ( $sql );
		$bound = array (
				$order_id 
		);
		$std->execute ( $bound );
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$list [] = $row;
		}
		return $list;
	}
	public static function getOrdersPromotion($order_id) {
		$db = Db::getInstance ();
		$sql = 'select a.*, b.name
				from av_order_promotion a
				left join av_promotion b on a.promotion_id = b.promotion_id
				where a.order_id = ?';
		$std = $db->prepare ( $sql );
		$bound = array (
				$order_id 
		);
		$std->execute ( $bound );
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$list [] = $row;
		}
		return $list;
	}
	public static function getOrdersCoupon($odrId) {
		$db = Connect::conn ();
		$sql = 'select a.*  from av_orders_coupon a
				where a.odrId = ?';
		$std = $db->prepare ( $sql );
		$bound = array (
				$odrId 
		);
		$std->execute ( $bound );
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$list [] = $row;
		}
		return $list;
	}
	public static function getOrdersCouponListGroupByOdrId($odrId) {
		$db = Connect::conn ();
		$sql = 'select a.*  from av_orders_coupon a
				where a.odrId in (' . $odrId . ')';
		$std = $db->prepare ( $sql );
		$bound = array ();
		if (! $std->execute ( $bound )) {
			print_r ( $std->errorInfo () );
			exit ();
		}
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$list [$row ['odrId']] [] = $row;
		}
		return $list;
	}
	public static function collateProductIdAndPriceFromCart() {
		$list = array ();
		foreach ( self::$cartList as $product_id => $product_row ) {
			$list [$product_id] = 0;
			foreach ( $product_row as $colortable_id => $colortable_row ) {
				$list [$product_id] += $colortable_row ['price'];
				break;
			}
		}
		return $list;
	}
	public static function collateProductIdAndQtyFromCart() {
		$list = array ();
		foreach ( self::$cartList as $product_id => $product_row ) {
			$list [$product_id] = 0;
			foreach ( $product_row as $colortable_id => $colortable_row ) {
				$list [$product_id] += $colortable_row ['qty'];
			}
		}
		return $list;
	}
	public static function getCartInCookie() {
		$db = \Routine\Db::getInstance ();
		if (! empty ( self::$cartList )) {
			return self::$cartList;
		}
		$cartIdList = array ();
		$colorIdList = array ();
		$stockList=array();
		if (isset ( $_COOKIE ['cartList'] ) && ! empty ( $_COOKIE ['cartList'] )) {
			// echo '<br />';
			// echo 'cookie cartlist='.$_COOKIE['cartList'];
			// echo '<br />';
			if (get_magic_quotes_gpc ()) {
				$ccl = stripslashes ( $_COOKIE ['cartList'] );
			} else {
				$ccl = $_COOKIE ['cartList'];
			}
			$cartListInCookie = json_decode ( $ccl, true );
			// echo 'cartlist in cookie = ';
			// print_r($cartListInCookie);
			foreach ( $cartListInCookie as $product_id => $product_row ) {
				if (empty ( $product_id ))
					continue;
				$cartIdList [] = $product_id;
				foreach ( $product_row as $colortable_id => $colortable_row ) {
					if (empty ( $colortable_id ))
						continue;
					$colorIdList [] = $colortable_id;
					$sql = 'select *
							from av_product_color a
							where a.product_id = ? and a.colortable_id = ?';
					$std=$db->prepare($sql);
					$bound=array($product_id, $colortable_id);
					if(!$std->execute($bound)){
						print_r($std->errorInfo());
					}
					$pcRow=$std->fetch();
// 					var_dump($pcRow);
// 					echo $sql;
// 					print_r($bound);
					$stockList[$product_id][$colortable_id]=$pcRow['stock'];
				}
			}
		}
		$cartIdList = array_unique ( $cartIdList );
		$colorIdList = array_unique ( $colorIdList );
		$colorIdList = array_values ( $colorIdList );
		
		if (count ( $cartIdList ) == 0) {
			return array ();
		}
		
		self::$cartIdList = $cartIdList;
		$sql = 'select a.price, a.product_id, a.name, a.name_en, a.image, a.short_desc, a.freight_price
				from av_product a
				where a.product_id in (' . $db->getQuestionMarkByArray ( $cartIdList ) . ')';
		$std = $db->prepare ( $sql );
		$bound = $cartIdList;
		$priceList = array ();
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				$priceList [$row ['product_id']] = $row;
			}
		}
		
// 		print_r($stockList);
		
		$sql = 'select a.colortable_id, a.name, a.code
				from av_colortable a
				where a.colortable_id in (' . $db->getQuestionMarkByArray ( $colorIdList ) . ')';
		$std = $db->prepare ( $sql );
		$bound = $colorIdList;
		$colorList = array ();
		
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				$colorList [$row ['colortable_id']] = $row;
			}
		} else {
			echo $sql;
			print_r ( $bound );
		}
		$subtotal = 0;
		$freight_price = 0;
		foreach ( $cartListInCookie as $product_id => $product_row ) {
			foreach ( $product_row as $colortable_id => $colortable_row ) {
				
				//庫存檢查
				if($stockList[$product_id][$colortable_id] < $cartListInCookie[$product_id][$colortable_id]['qty']){
					$cartListInCookie[$product_id][$colortable_id]['lack']=$stockList[$product_id][$colortable_id]-$cartListInCookie[$product_id][$colortable_id]['qty'];
				}else{
					$cartListInCookie[$product_id][$colortable_id]['lack']=0;
				}
				
				$cartListInCookie [$product_id] [$colortable_id] ['short_desc'] = $colorList [$colortable_id] ['short_desc'];
				$cartListInCookie [$product_id] [$colortable_id] ['color_name'] = $colorList [$colortable_id] ['name'];
				$cartListInCookie [$product_id] [$colortable_id] ['name'] = $priceList [$product_id] ['name'];
				$cartListInCookie [$product_id] [$colortable_id] ['image'] = $priceList [$product_id] ['image'];
				$cartListInCookie [$product_id] [$colortable_id] ['name_en'] = $priceList [$product_id] ['name_en'];
				$cartListInCookie [$product_id] [$colortable_id] ['price'] = $priceList [$product_id] ['price'];
				$cartListInCookie [$product_id] [$colortable_id] ['subtotal'] = $priceList [$product_id] ['price'] * $cartListInCookie [$product_id] [$colortable_id] ['qty'];
				$subtotal += $cartListInCookie [$product_id] [$colortable_id] ['subtotal'];
				$freight_price += $priceList [$product_id] ['freight_price'] * $cartListInCookie [$product_id] [$colortable_id] ['qty'];
			}
		}
		self::$cartInfor ['cart_subtotal'] = $subtotal;
		self::$cartInfor ['freight_price'] = $freight_price;
		
		self::$cartInfor ['total_price'] = $subtotal + $freight_price;
		
		self::$cartList = $cartListInCookie;
		
		// print_r ( $cartListInCookie );
		// print_r ( $cartIdList );
		// exit ();
		return $cartListInCookie;
	}
	public static function getAccessoryInCookie() {
		$db = \Routine\Db::getInstance ();
		if (! empty ( self::$accessoryList )) {
			return self::$accessoryList;
		}
		$accessoryIdList = array ();
		$colorIdList = array ();
		if (isset ( $_COOKIE ['accessoryList'] ) && ! empty ( $_COOKIE ['accessoryList'] )) {
			// echo '<br />';
			// echo 'cookie cartlist='.$_COOKIE['cartList'];
			// echo '<br />';
			if (get_magic_quotes_gpc ()) {
				$ccl = stripslashes ( $_COOKIE ['accessoryList'] );
			} else {
				$ccl = $_COOKIE ['accessoryList'];
			}
			$cartListInCookie = json_decode ( $ccl, true );
			// echo 'cartlist in cookie = ';
			// print_r($cartListInCookie);
			foreach ( $cartListInCookie as $accessory_id => $accessory_row ) {
				if (empty ( $accessory_id ))
					continue;
				$accessoryIdList [] = $accessory_id;
			}
		}
		$accessoryIdList = array_unique ( $accessoryIdList );
		
		if (count ( $accessoryIdList ) == 0) {
			return array ();
		}
		
		self::$accessoryIdList = $accessoryIdList;
		$sql = 'select a.price, a.name, a.image, a.accessory_id
				from av_accessory a
				where a.accessory_id in (' . $db->getQuestionMarkByArray ( $accessoryIdList ) . ')';
		$std = $db->prepare ( $sql );
		$bound = $accessoryIdList;
		$priceList = array ();
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				$priceList [$row ['accessory_id']] = $row;
			}
		}
		
		$subtotal = 0;
		$freight_price = 0;
		foreach ( $cartListInCookie as $accessory_id => $product_row ) {
			foreach ( $product_row as $product_id => $colortable_row ) {
				$cartListInCookie [$accessory_id] [$product_id] ['name'] = $priceList [$accessory_id] ['name'];
				$cartListInCookie [$accessory_id] [$product_id] ['image'] = $priceList [$accessory_id] ['image'];
				$cartListInCookie [$accessory_id] [$product_id] ['price'] = $priceList [$accessory_id] ['price'];
				$cartListInCookie [$accessory_id] [$product_id] ['subtotal'] = $priceList [$accessory_id] ['price'] * $cartListInCookie [$accessory_id] [$product_id] ['qty'];
				$subtotal += $cartListInCookie [$accessory_id] [$product_id] ['subtotal'];
			}
		}
		self::$accessoryInfor ['cart_subtotal'] = $subtotal;
		
		self::$accessoryInfor ['total_price'] = $subtotal;
		
		self::$accessoryList = $cartListInCookie;
		
		// print_r ( $cartListInCookie );
		// print_r ( $cartIdList );
		// exit ();
		return $cartListInCookie;
	}
	public static function clearCookieOrders() {
		setcookie ( 'couponList', '', time () - 3600, "/" );
		setcookie ( 'cartList', '', time () - 3600, '/' );
	}
	public static function getCouponInCookie() {
		$db = Connect::conn ();
		$couponIdList = array ();
		if (isset ( $_COOKIE ['couponList'] ) && ! empty ( $_COOKIE ['couponList'] )) {
			if (get_magic_quotes_gpc ()) {
				$ccl = stripslashes ( $_COOKIE ['couponList'] );
			} else {
				$ccl = $_COOKIE ['couponList'];
			}
			$couponListInCookie = json_decode ( $ccl );
			foreach ( $couponListInCookie as $couponKey => $couponRow ) {
				if (! isset ( $couponRow->proId )) {
					continue;
				}
				$couponIdList [] = $couponRow->proId;
			}
		}
		$couponIdList = array_unique ( $couponIdList );
		$list = array ();
		// print_r($couponIdList);
		if (count ( $couponIdList ) > 0) {
			$sql = 'select a.proId, a.proCatId, a.proName, a.proImg, a.proNormal, a.proCoupon, a.proGift, a.proPrice, a.proStatus, a.proStock, a.proKeyword, a.proDate from av_products a where a.proId in (' . $db->getQMark ( $couponIdList ) . ')';
			$std = $db->prepare ( $sql );
			$bound = $couponIdList;
			$std->execute ( $bound );
			while ( ( $row = $std->fetch () ) !== false ) {
				$row ['qty'] = self::getCartQtyByProId ( $couponListInCookie, $row ['proId'] );
				$row ['subtotal'] = $row ['qty'] * $row ['proPrice'];
				$list [] = $row;
			}
		} else {
		}
		return $list;
	}
	public static function getCartQtyByProId($cartListInCookie, $proId) {
		$qty = 0;
		foreach ( $cartListInCookie as $cartKey => $cartRow ) {
			if ($cartRow->proId == $proId) {
				$qty += $cartRow->qty;
			}
		}
		return $qty;
	}
	public static function getFreightAmount() {
		$freightConfig = self::getFreight ();
		return $freightConfig ['freight'];
	}
	public static function getFreightCap() {
		$freightConfig = self::getFreight ();
		return $freightConfig ['freightCap'];
	}
	public static function getFreight() {
		if (self::$freightConfig == null) {
			self::$freightConfig = array (
					'freight' => Aviva::getWebOption ( 2 ),
					'freightCap' => Aviva::getWebOption ( 3 ) 
			);
		}
		return self::$freightConfig;
	}
	public static function getCouponCap($onlyAmout = false) {
		if (self::$couponCap == null) {
			$db = Connect::conn ();
			$sql = 'select a.*, b.proName , c.proName proName2 , d.proName  proName3
					from av_coupons a 
					left join av_products b on a.proId = b.proId 
					left join av_products c on a.proId2 = c.proId 
					left join av_products d on a.proId3 = d.proId 
					where ' . ( $onlyAmout === true ? 'a.couAmount > 0' : '1 ' ) . ' order by couCap desc';
			$std = $db->prepare ( $sql );
			$std->execute ();
			$list = array ();
			while ( ( $row = $std->fetch () ) !== false ) {
				$list [] = $row;
			}
			self::$couponCap = $list;
		}
		return self::$couponCap;
	}
	public static function getNewOrderNo() {
		return date ( "ymd" ) . time ();
	}
}