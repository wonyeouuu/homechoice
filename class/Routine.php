<?
class routine
{
	public $product_image_width = 251;
	public $product_image_height = 161;
	
	function __construct()
	{
	}
	
	public function paginate($count_sql, $query_sql, $options = array())
	{
		
		$options = (array) $options;
		$options += array(
			'page' =>1,//目前頁數
			'total_page' => NULL,//已知總頁數
			'page_interval' => 10,//顯示總頁數
			'page_count' => 10,//一頁有幾筆
			'page_range' => 4,//當頁前後有幾頁
			'type' => 1,//頁數種類 1: page_interval(1-10) 2:page_range(目前頁數置中)
			'now_id' => NULL//目前所在資料ID
		);
		$page = $options['page'];
		//算出總筆數
		$result = mysql_query($count_sql);
		$result_num = mysql_num_rows($result);
		if($result_num > 1) $total_num = $result_num;
		else list($total_num) = mysql_fetch_row($result);
		if($total_num == 0)
		{
			$return['page'] = 1;
			$return['total_num'] = 0;
			$return['total_page'] = 0;
			$return['start_no'] = $return['end_no'] = 0;
			return $return;
		}
		
		$total_page = ceil($total_num / $options['page_count']);//總頁數
		$page = $page < 1 ? 1 : ($page > $total_page ? $total_page : $page);//目前頁數
		
		$start_no = ($page - 1) * $options['page_count'] + 1;
		$end_no = $start_no + $options['page_count'] - 1 >= $total_num ? $total_num : $start_no + $options['page_count'] - 1;
		$query_sql = $query_sql." limit ".(($page - 1) * $options['page_count']).", ".$options['page_count'];
		
		$previous_page = $page == 1 ? NULL : $page - 1;//上一頁
		$next_page = $page == $total_page ? NULL : $page + 1;//下一頁
		
		$return['page'] = $page;
		$return['total_num'] = $total_num;
		$return['total_page'] = $total_page;
		$return['query_sql'] = $query_sql;
		$return['previous_page'] = $previous_page;
		$return['next_page'] = $next_page;
		$return['start_no'] = $start_no;
		$return['end_no'] = $end_no;
		if($options['type'] == 2)
		{
			$previous_area = $page - $options['page_range'] > 1 ? $page - $options['page_range'] : $page == 1 ? NULL : 1;
			$next_area = $page + $options['page_range'] <= $total_page  ? ($page + $options['page_range']) : $total_page;
			$return['previous_area'] = $previous_area;
			$return['next_area'] = $next_area;
		}
		elseif($options['type'] == 1)
		{
			$start_page = floor(($page - 1) / $options['page_interval']) * $options['page_interval'] + 1;
			$end_page = $start_page + $options['page_interval'] - 1 >= $total_page ? $total_page : $start_page + $options['page_interval'] - 1;
			$previous_area = $start_page - $options['page_interval'] > 0 ? $start_page - $options['page_interval'] : NULL;
			$next_area = $end_page + 1 <= $total_page ? $end_page + 1 : NULL;
			$return['start_page'] = $start_page;
			$return['end_page'] = $end_page;
			$return['previous_area'] = $previous_area;
			$return['next_area'] = $next_area;
		}
		return $return;
	}
	
	public function upload_file($file, $to_path, $file_name = NULL, $optoins = array())
	{
		$size = $file['size'];
		if($size > 0)
		{
			
			$path = pathinfo($file['name']);
			if($file_name === NULL)
			{
				$file_name = $file['name'];
			}
			else
			{
				$extension = $path['extension'];
				$file_name = $file_name.'.'.$extension;
			}
			if(is_uploaded_file($file['tmp_name']))
			{
				$res = move_uploaded_file($file['tmp_name'], $to_path.$file_name);
				chmod($to_path.$file_name, 0777);
				return $file_name;
			}
			else return false;
		}
		else return false;
	}
	
	/**
	 The MIT License
	
	 Copyright (c) 2007
<Tsung-Hao>
Permission is hereby granted, free of charge, to any person obtaining a copy
	 of this software and associated documentation files (the "Software"), to deal
	 in the Software without restriction, including without limitation the rights
	 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 copies of the Software, and to permit persons to whom the Software is
	 furnished to do so, subject to the following conditions:
	
	 The above copyright notice and this permission notice shall be included in
	 all copies or substantial portions of the Software.
	
	 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	 THE SOFTWARE.
	 *
	 * 抓取要縮圖的比例, 下述只處理 jpeg
	 * $from_filename : 來源路徑, 檔名, ex: /tmp/xxx.jpg
	 * $save_filename : 縮圖完要存的路徑, 檔名, ex: /tmp/ooo.jpg
	 * $in_width : 縮圖預定寬度
	 * $in_height: 縮圖預定高度
	 * $quality  : 縮圖品質(1~100)
	 *
	 * Usage:
	 *   ImageResize('ram/xxx.jpg', 'ram/ooo.jpg');
	 */
	function ImageResize($from_filename, $save_filename, $in_width = 251, $in_height = 161, $quality=100)
	{
		
		
		$allow_format = array('jpeg', 'png', 'gif', 'jpg');
		$sub_name = $t = '';
	
		// Get new dimensions
		$img_info = getimagesize($from_filename);
		$width    = $img_info['0'];
		$height   = $img_info['1'];
		$imgtype  = $img_info['2'];
		$imgtag   = $img_info['3'];
		$bits     = $img_info['bits'];
		$channels = $img_info['channels'];
		$mime     = $img_info['mime'];
	
		list($t, $sub_name) = split('/', $mime);
		if ($sub_name == 'jpg') {
			$sub_name = 'jpeg';
		}
	
		if (!in_array($sub_name, $allow_format)) {
			return false;
		}
	
		// 取得縮在此範圍內的比例
		//$percent = $this -> getResizePercent($width, $height, $in_width, $in_height);
		$percent = 1;
		$new_width  = $in_width * $percent;
		$new_height = $in_height * $percent;
	
		// Resample
		$image_new = imagecreatetruecolor($new_width, $new_height);
	
		// $function_name: set function name
		//   => imagecreatefromjpeg, imagecreatefrompng, imagecreatefromgif
		/*
		// $sub_name = jpeg, png, gif
		$function_name = 'imagecreatefrom'.$sub_name;
		$image = $function_name($filename); //$image = imagecreatefromjpeg($filename);
		*/
		if($sub_name == 'jpeg' || $sub_name == 'jpg'){
			$image = imagecreatefromjpeg($from_filename);
		}elseif($sub_name == 'gif'){
			$image = imagecreatefromgif($from_filename);
		}elseif($sub_name == 'png'){
			$image = imagecreatefrompng($from_filename);
		}
	
		imagecopyresampled($image_new, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		
		if($sub_name == 'jpeg' || $sub_name == 'jpg'){
			return imagejpeg($image_new, $save_filename, $quality);
		}elseif($sub_name == 'gif'){
			return imagegif($image_new, $save_filename, $quality);
		}elseif($sub_name == 'png'){
			return imagepng($image_new, $save_filename, 9);
		}
	}
	
	/**
	 * 抓取要縮圖的比例
	 * $source_w : 來源圖片寬度
	 * $source_h : 來源圖片高度
	 * $inside_w : 縮圖預定寬度
	 * $inside_h : 縮圖預定高度
	 *
	 * Test:
	 *   $v = (getResizePercent(1024, 768, 400, 300));
	 *   echo 1024 * $v . "\n";
	 *   echo  768 * $v . "\n";
	 */
	function getResizePercent($source_w, $source_h, $inside_w, $inside_h)
	{
		if ($source_w < $inside_w && $source_h < $inside_h) {
			return 1; // Percent = 1, 如果都比預計縮圖的小就不用縮
		}
	
		$w_percent = $inside_w / $source_w;
		$h_percent = $inside_h / $source_h;
	
		return ($w_percent > $h_percent) ? $h_percent : $w_percent;
	}
	
	function remove_query($query)
	{
		$arr = explode('&', $_SERVER['QUERY_STRING']);
		if(is_array($arr))
		{
			foreach($arr as $key => $value)
			{
				$sarr = explode('=', $arr);
				if($sarr[0] == $query)
				{
					unset($arr[$key]);
				}
			}
			$res = implode('&', $arr);
			$_SERVER['QUERY_STRING'] = $res;
		}
	}
	
	function get_vars($options = array())
	{
		$options = (array) $options;
		$options += array(
			'assign' => true,//是否指定同名變數
			'type' => 'post'//種類 request, post, get
		);
		
		if($options['type'] == 'post') $this -> filter_vars($_POST);
		elseif($options['type'] == 'get') $this -> filter_vars($_GET);
		elseif($options['type'] == 'request') $this -> filter_vars($_REQUEST);
		if($options['assign'] == true) $this -> assign_vars($options['type']);
		
	}
	
	function get_post()
	{
		$this -> get_vars(array('type' => 'post'));
	}
	
	function get_get()
	{
		$this -> get_vars(array('type' => 'get'));
	}
	
	function filter_vars(&$post)
	{
		if(is_array($post) && count($post) > 0)
		{
			foreach($post as $key => &$value)
			{
				if(is_array($value) || is_object($value)) $this -> filter_vars($value);
				elseif(is_string($value) || is_numeric($value)) $post[$key] = get_magic_quotes_gpc() ? trim($value) : addslashes(trim($value));
				else $post[$key] = $value;
			}
		}
	}
	
	function assign_vars($type)
	{
		if($type == 'post')
		{
			foreach($_POST as $key => $value)
			{
				global $$key;
				$$key = $value;
			}
		}
		elseif($type == 'get')
		{
			foreach($_GET as $key => $value)
			{
				global $$key;
				$$key = $value;
			}
		}
		elseif($type == 'request')
		{
			foreach($_REQUEST as $key => $value)
			{
				global $$key;
				$$key = $value;
			}
		}
	}
	
	function clear_array(&$arr, $reset_key = false)
	{
		if(is_array($arr) && count($arr) > 0)
		{
			foreach($arr as $key => $value)
			{
				if(is_string($value) || is_numeric($value))
				{
					if(empty($value))
					{
						unset($arr[$key]);
					}
				}
				elseif(is_object($value) || is_array($value))
				{
					$this -> clear_array($value);
				}
				elseif(is_bool($value))
				{
					$arr[$key] = $value;
				}
			}
			
		}
		return $arr;
	}
} 