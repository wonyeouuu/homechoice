<?php
class GridCustomer {
	public static function getCustomerWhereSql($config, $where_sql, $search, $bound = array()) {
		if (isset ( $config ['name'] )) {
			switch ($config ['name']) {
				case 'DateTime' :
					if (! empty ( $search ['StartDate'] ) && ! empty ( $search ['EndDate'] )) {
						$where_sql .= ' and (a.CreateDateTime between "' . $search ['StartDate'] . ' 00:00:00" and "' . $search ['EndDate'] . ' 23:59:59")';
					}
					break;
				case 'EcDateTime' :
					if (! empty ( $search ['StartDate'] ) && ! empty ( $search ['EndDate'] )) {
						$where_sql .= ' and (a.CreateDateTime between "' . $search ['StartDate'] . ' 00:00:00" and "' . $search ['EndDate'] . ' 23:59:59")';
					}
					break;
				case 'CouponBuyDateTime' :
			if (! empty ( $search ['StartDate'] ) && ! empty ( $search ['EndDate'] )) {
						$where_sql .= ' and (a.BuyDateTime between "' . $search ['StartDate'] . ' 00:00:00" and "' . $search ['EndDate'] . ' 23:59:59")';
					}
					break;
				case 'odrCreditDate' :
					if ($search ['odrCreditDate'] == '1') {
						$where_sql .= " and (a.odrCreditDate is null and a.odrShippingType = 3)";
					} elseif ($search ['odrCreditDate'] == '2') {
						$where_sql .= " and (a.odrCreditDate is not null and a.odrShippingType = 3)";
					}
					break;
				case 'odrTotal' :
					if ($search ['odrTotal'] != '') {
						$search ['odrTotal'] = ( int ) $search ['odrTotal'];
						if ($search ['odrTotal'] != 0) {
							$where_sql .= " and a.odrTotal >= :odrTotal";
							$bound ['odrTotal'] = $search ['odrTotal'];
						}
					}
					break;
				case 'oqStatus' :
					if ($search ['oqStatus'] == 'not') {
						$where_sql .= " and (a.oqAnswer = \"\" or a.oqAnswer is null)";
					}
					break;
				case 'odrDate' :
					if (! empty ( $search ['startDate'] ) && ! empty ( $search ['endDate'] )) {
						$where_sql .= ' and (a.odrDate between "' . $search ['startDate'] . ' 00:00:00" and "' . $search ['endDate'] . ' 23:59:59")';
					}
					break;
				default :
					break;
			}
		}
		// echo $where_sql;
		return array (
				'where_sql' => $where_sql,
				'bound' => $bound 
		);
	}
}