<?php 

/*
$op = new insert();

$field = array('name', 'code');
$data = array('name1', 'code1');
$op -> setTable('test') -> setField($field) -> setData($data) -> addFuncField(array('tDate' => 'NOW()')) -> go();
echo $op -> sqlResult;
*/


final class Insert extends connect
{
	public $insertTable = '';
	public $insertPre = '';
	public $mltInsert = false;
	public $mltInsertCount = 0;
	public $insertField = '';
	public $insertState = NULL;
	public $funcField = array();
	
	function __construct()
	{
		parent:: __construct();
	}
	
	function setTable($table)
	{
		$this -> insertTable = $table;
		return $this;
	}
	
	function setMltInsert($insertRows)
	{
		$this -> mltInsert = true;
		$this -> mltInsertCount = $insertRows;
		return $this;
	}
	
	
	
	function setPre($pre)
	{
		$this -> insertPre = $pre;
		return $this;
	}
	
	function setField($field)
	{
		$this -> insertField = $field;
		return $this;
		
	}
	
	//如果field == NULL 查詢類型= ?
	//如果field == array 查詢類型 = :
	
	function setState($field = NULL)
	{
		$this -> insertState = $field;
		return $this;
	}
	
	function setData($data)
	{
		$this -> insertData = $data;
		return $this;
	}
	
	function strokeColumn()
	{
		$fieldArr = array();
		foreach($this -> insertField as $v)
		{
			$fieldArr[] = '`'.$v.'`';
		}
		return implode(',', $fieldArr);
	}
	
	function strokeField()
	{
		
		$field = array_merge($this -> insertField, array_keys($this -> funcField));
		$fieldArr = array();
		foreach($field as $v)
		{
			$fieldArr[] = '`'.$v.'`';
		}
		
		
		$this -> insertFieldStr = '(' . implode(',', $fieldArr) . ')';
		return $this -> insertFieldStr;
	}
	
	function addFuncField($field = array())
	{
		if(is_array($field) && count($field) > 0)
		{
			$this -> funcField = array_merge($this -> funcField, $field);
		}
		return $this;
	}
	
	function strokeState()
	{
		$state = $this -> insertState;
		$stateStr = '';
		$stateArr = array();
		
		if($this -> mltInsert == true)
		{
			$mltInsertCount = $this -> mltInsertCount;
			$fieldCount = count($this -> insertField);
			if(empty($this -> mltInsertCount))
			{
				$this -> mltInsertCount = count($this -> insertData);
			}
			
			if(is_null($this -> insertState))//(?, ?, ?), (?, ?, ?)
			{
				
				$tmpStateStr = $this -> __getQuote($fieldCount);
				for($j = 0;$j < $mltInsertCount;$j ++)
				{
					$stateArr[] = $tmpStateStr;
				}
				$stateStr = implode(',', $stateArr);
			}
			
		}
		else//single insert
		{
			if(is_null($this -> insertState))//??
			{
				$stateStr = $this -> __getQuote(count($this -> insertField));
				
			}
			elseif(is_array($this -> insertState))//:
			{
				$stateStr = $this -> __getColon();
			}
			else
			{
				throw new Exception('Insert state error');
			}
		}
		
		return $stateStr;
		
	}
	
	function combineSql()
	{
		$sql = 'insert ';
		
		if(!empty($this -> insertPre))
		{
			$sql .= ' '.$this -> insertPre;
		}
		
		if(empty($this -> insertTable))
		{
			throw new Exception('Insert table empty');
			return fasle;
		}
		$sql .= ' into ';
		$sql .= ' '.$this -> insertTable;
		$sql .= ' ' . $this -> strokeField();
		$sql .= ' values ';
		
		$sql .= $this -> strokeState();
		$this -> sqlResult = $sql;
		return $this;
	}
	
	function strokeData()
	{
		$field = $this -> insertField;
		$data = $this -> insertData;
		
	}
	
	function go()
	{
		$this -> combineSql();
		$sql = $this -> sqlResult;
		
		if($this -> mltInsert == true)
		{
			
		}
		else//Single Insert
		{
			if(is_null($this -> insertState))
			{
			}
			elseif(is_array($this -> insertState))
			{
				if(count($this -> insertData) != count($this -> insertField))
				{//echo count($this -> insertData) .','. count($this -> insertField);print_r($this -> insertData);
					$tmpData = array();
					foreach($this -> insertField as $v)
					{
						$tmpData[$v] = $this -> insertData[$v];
					}
					$this -> insertData = $tmpData;
				}
				else
				{
					$tmpData = array();
					foreach($this -> insertField as $k => $v)
					{
						$tmpData[$v] = $this -> insertData[$k];
					}
					$this -> insertData = $tmpData;
				}
			}
		}
		
		//print_r($this -> insertData);
		//exit;
		$pre = $this -> prepare($sql);
			print_r($this -> insertData);
			echo '<br>';
			echo $sql;
			echo '<br>';
			//exit;
		if(!$pre -> execute($this -> insertData))
		{
			print_r($db -> errorInfo());
			return false;
		}
		$this -> newId = $this -> new_id = $this -> lastInsertId();
		
		return $this;
	}
	
	function __getColon()
	{
		$tmpState = array();
		foreach($this -> insertField as $v)
		{
			$tmpState[] = ':' . $v;
		}
		
		if(count($this -> funcField) > 0)
		{
			foreach($this -> funcField as $k => $v)
			{
				$tmpState[] = $v;
			}
		}
		
		$tmpStateStr = '(' . implode(',', $tmpState) . ')';
		return $tmpStateStr;
	}
	
	
	function strokeColon()
	{
		$tmpState = array();
		foreach($this -> insertField as $v)
		{
			$tmpState[] = ':' . $v;
		}
		
		
		$tmpStateStr = implode(',', $tmpState);
		return $tmpStateStr;
	}
	function __getQuote($quoteNum = 1)
	{
		$tmpState = array();
		
		
		for($j = 0;$j < $quoteNum;$j ++)
		{
			$tmpState[] = '?';
		}
		
		if(count($this -> funcField) > 0)
		{
			foreach($this -> funcField as $k => $v)
			{
				$tmpState[] = $v;
			}
		}
		
		
		$tmpStateStr = '(' . implode(',', $tmpState) . ')';
		return $tmpStateStr;
	}
}
?>