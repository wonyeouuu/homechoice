<?php
class Paginate2
{
	public $where_sql = "";
	public $page = 1;//目前頁數
	public $total_page;//已知總頁數
	public $page_interval = 10;//顯示總頁數
	public $page_count = 10;//一頁有幾筆
	public $page_range = 4;//當頁前後有幾頁
	public $type = 1;//頁數種類 1: page_interval(1-10) 2:page_range(目前頁數置中)
	public $countSql = "";
	public $querySql = "";
	public $result;

	function __construct()
	{
		
	}
	
	public function set_where_sql($where_sql, $par)
	{
		
	}
	
	function setPageInterval($i)
	{
		$this -> page_interval = $i;
	}
	
	function setType($type)
	{
		$this -> type = $type;
	}
	
	function setPageRange($r)
	{
		$this -> page_range = $r;
	}
	
	function setPageCount($count)
	{
		$this -> page_count = $count;
	}
	
	function setPage($page)
	{
		$this -> page = $page;
	}
	
	function setCountSql($sql)
	{
		$this -> countSql = $sql;
	}

	function setQuerySql($sql)
	{
		$this -> querySql = $sql;
	}
	
	function numFetch(){
		
	}
	
	public function paginate($countSql = '', $querySql = '', $options = array())
	{
		
		if(!empty($countSql)) $this -> setCountSql($countSql);
		if(!empty($querySql)) $this -> setQuerySql($querySql);
		
		//算出總筆數
		//$db = new Connect();
		//$sql = $this -> countSql;
		
		//$total_num = $db -> numFetch($this -> countSql);
		//算出總筆數
		$db = new Connect();
		$sql = $this->countSql;
		$pre = $db->prepare($sql);
		$bound = $this -> bound;
		if (!$pre->execute($bound)) {
			echo print_r($pre->errorInfo(), true);
			echo $sql;
		}
		$total_num = $pre -> fetchColumn();
		//echo 'tn='.$total_num;
		//echo 'cs='.$this -> countSql;
		
		if($total_num == 0)
		{
			$return['page'] = 1;
			$return['total_num'] = 0;
			$return['total_page'] = 0;
			$return['start_no'] = $return['end_no'] = 0;
			$return['source_count'] = $count_sql;
			$return['source_query'] = $query_sql;
			$return['query_sql'] = $this -> querySql;
			return $return;
		}
		$total_page = ceil($total_num / $this -> page_count);//總頁數
		$page = $this -> page < 1 ? 1 : ($this -> page > $total_page ? $total_page : $this -> page);//目前頁數
		
		$start_no = ($this -> page - 1) * $this -> page_count + 1;
		$end_no = $start_no + $this -> page_count - 1 >= $total_num ? $total_num : $start_no + $this -> page_count - 1;
		$query_sql = $this -> querySql." limit ".(($this -> page - 1) * $this -> page_count).", ".$this -> page_count;
		
		$previous_page = $this -> page == 1 ? NULL : $this -> page - 1;//上一頁
		$next_page = $this -> page == $total_page ? NULL : $this -> page + 1;//下一頁
		
		$return['page'] = $this -> page;
		$return['total_num'] = $total_num;
		$return['total_page'] = $total_page;
		$return['query_sql'] = $query_sql;
		$return['previous_page'] = $previous_page;
		$return['next_page'] = $next_page;
		$return['start_no'] = $start_no;
		$return['end_no'] = $end_no;
		if($this -> type == 2)
		{
			$previous_area = $this -> page - $this -> page_range > 1 ? $this -> page - $this -> page_range : $this -> page == 1 ? NULL : 1;
			$next_area = $this -> page + $this -> page_range <= $total_page  ? ($this -> page + $this -> page_range) : $total_page;
			$return['previous_area'] = $previous_area;
			$return['next_area'] = $next_area;
		}
		elseif($this -> type == 1)
		{
			$start_page = floor(($this -> page - 1) / $this -> page_interval) * $this -> page_interval + 1;
			$end_page = $start_page + $this -> page_interval - 1 >= $total_page ? $total_page : $start_page + $this -> page_interval - 1;
			$previous_area = $start_page - $this -> page_interval > 0 ? $start_page - $this -> page_interval : NULL;
			$next_area = $end_page + 1 <= $total_page ? $end_page + 1 : NULL;
			$return['start_page'] = $start_page;
			$return['end_page'] = $end_page;
			$return['previous_area'] = $previous_area;
			$return['next_area'] = $next_area;
		}
		$this -> result = $return;
		return $return;
	}
	
} 