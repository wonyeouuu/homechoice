<?php

namespace Control;

use Model\Feedback;
use Model\Error\Error;
use Model\Backend\Security;

class Backend {
	public static function getBkUserComList() {
		Security::isLogined(true);
		$UserID = $_SESSION ['BkUserID'];
		$db = \Model\Db\Db::getInstance ();
		if ($_SESSION ['BkKind'] == '9') {
			$sql = 'select a.QuickNo, a.Name, a.CommunityID
					from tb_Community a
					order by a.QuickNo asc';
			$std = $db->prepare ( $sql );
			$list = array ();
			if ($std->execute (  )) {
				while ( ( $row = $std->fetch () ) !== false ) {
					$list [] = $row;
				}
			}
			return $list;
		} else {
			$CommunityAdmins = $_SESSION ['BkCommunityAdmin'];
			$C = new \Model\Community\Get ();
			$list = $C->getInforsByCommunityIDs ( $CommunityAdmins );
			$rlist = array ();
			return $list;
		}
	}
}