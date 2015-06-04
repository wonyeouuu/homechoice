<?php 
namespace Routine;

class ShowColumn
{
	public $columnSetting = array();
	public $data;
	public $showColumn = array();
	public $result;
	// 'number_format($value, 4, \';\', \'*\')'
	function __construct()
	{
	}
	
	function stroke()
	{
		$endRow = array();
		foreach($this -> columnSetting as $csk => $csv)
		{
			
			
			//值
			if(in_array($csk, $this -> showColumn))//如果在顯示清單裡面 才處理
			{
				$endValue = $sourceValue = $this -> getValue($csk, $this -> data);//先把值算出來
				
				if(is_array($scv))
				{
					if(isset($scv['show_type']))//帶入清單功能
					{
						$endValue = $scv['show_type'][$endValue];
					}
					
					//PHP 內定義函數
					if(isset($scv['func']) && is_array($scv['func']))
					{
						foreach($scv['func'] as $fk => $fv)
						{
							if(is_string($fv))
							{
								eval("\$endValue=\$fv;");
							}
						}
					}
					
					//自定義函數
					if(isset($scv['ufunc']) && is_array($scv['ufunc']))
					{
						foreach($scv['ufunc'] as $fk => $fv)
						{
							if(is_string($fv))
							{
								call_user_func($fv, $endValue);
							}
						}
					}
				}
			}
			
			$endRow[$csk] = $endValue;
		}
		$this -> result = $endRow;
	}
	
	function setData($data)
	{
		$this -> data = $data;
		return $this;
	}
	
	function setColumn($c)
	{
		$this -> columnSetting = $c;
		return $this;
	}
	
	function setShowColumn($c)
	{
		$this -> showColumn = $c;
		return $this;
	}
	
	function getValue($key, $row)
	{
		if(is_array($row) && isset($row[$key])) return $row[$key];
		elseif(is_object($row) && isset($row -> $key)) return $row -> $key;
	}
	
	
}
?>