<?php
namespace Routine;

class GridCustomer {
	public static function getCustomerWhereSql($config, $where_sql, $search, $bound = array()) {
		if (isset ( $config ['name'] )) {
			switch ($config ['name']) {
				case 'odrTransferStatus' :
					if ($search ['odrTransferStatus'] == '1') {
						$where_sql .= " and a.odrShippingType = 2 and (a.odrTransferAmount is null or a.odrTransferAmount = 0)";
					} else if ($search ['odrTransferStatus'] == '2') {
						$where_sql .= " and a.odrShippingType = 2 and (a.odrTransferAmount > 0)";
					}
					break;
				case 'odrCreditDate' :
					if ($search ['odrCreditDate'] == '1') {
						$where_sql .= " and (a.odrCreditDate is null and a.odrShippingType = 3)";
					}elseif ($search ['odrCreditDate'] == '2') {
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
				case 'odr_date' :
					if (! empty ( $search ['start_date'] ) && ! empty ( $search ['end_date'] )) {
						$where_sql .= ' and (a.add_date between "' . $search ['start_date'] . ' 00:00:00" and "' . $search ['end_date'] . ' 23:59:59")';
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