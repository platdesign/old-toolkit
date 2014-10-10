<?PHP

class php {

	function postRequest($url,$data=null) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	function isAjaxRequest() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	function obj($array=array()) {
		foreach($array as $key => $val) {
			if(is_array($val)) {
				$return->{$key} = self::obj($val);
			} else {
				$return->{$key} = $val;
			}
		}
		return($return);
	}
	
	
	function object_merge($a,$b) {
		if($b) {
			foreach($b as $key => $val) {
				$a->{$key} = $val;
			}
		}
		return $a;
	}
	
	function formSelectData($array,$label,$value) {
		$return = array();
		if($array) {
			foreach($array as $key => $val) {
				$return[] = array("label"=>$val->{$label},"value"=>$val->{$value});
			}
		}
		return($return);
	}

	function hex2rgb($hex) {
		$hex = str_replace("#","",$hex);

		$return->r = hexdec(substr($hex, 0,2));
		$return->g = hexdec(substr($hex, 2,2));
		$return->b = hexdec(substr($hex, 4,2));
		
		return($return);
	}
	
	
	function getFolder($dir) {
		if(is_dir($dir)) {
			$handle = opendir($dir);
			while ($file = readdir ($handle)) {
			    if($file AND substr($file,0,1)!=".") {
			        if(is_dir($dir."/".$file)) {
			            $return[] = $file;
			        }
			    }
			}
			closedir($handle);
			return($return);
		}
	}
	
	function getFiles($dir,$widthDir=null) {
		if(is_dir($dir)) {
			$handle = opendir($dir);
			while ($file = readdir ($handle)) {
			    if($file AND substr($file,0,1)!=".") {
			        if(!is_dir($dir."/".$file)) {
						if($widthDir) {
							$file = $dir.$file;
						}
			            $return[] = $file;
			        }
			    }
			}
			closedir($handle);
			return($return);
		}
		
	}
	
	function compressCode($code) {
		$return= 	preg_replace('!\s+!smi', ' ', $code);  
		return($return);
	}
	
	
	function get_contents($filename) {
	    if (is_file($filename)) {
	        ob_start();
	        include $filename;
	        return ob_get_clean();
	    }
	    return false;
	}
	

	function append2arrayIndex($array,$index,$appendContent) {
		if(isset($array[$index])) {
			$array[$index] .= $appendContent;
		} else {
			$array[$index] = $appendContent;
		}
		return($array);
	}
	
	
	function require_dir($dir) {
		if(is_dir($dir)) {
			$files = self::getFiles($dir,TRUE);
			if($files) {
				foreach($files as $file) {
					require_once($file);
				}
			}
		}
	}
	
	
	
	function transformData($data=null,$f=null) {
		if($data) {
			foreach($data as $key => $val) {
				if($f) {
					$return[] = $f($val);
				}
			}
		}
		return($return);
	}
	
	
	
	function sendHeader($type,$opt=NULL) {
		if(stripos($type,"/")) {
			header('Content-type: '.$type);
			if($opt) {
				self::sendHeader("filename",$opt);
			}
		} else {
			switch($type) {
				case "CSS":
					self::sendHeader("text/css");
				break;
				case "JS":
					self::sendHeader("text/javascript");
				break;
				case "JSON":
					self::sendHeader("application/json");
				break;
				case "HTML":
					self::sendHeader("text/html; charset=utf-8");
				break;
				case "EXPIRES":
					header("Expires: ".gmdate("D, d M Y H:i:s",$opt) . " GMT");
				break;
				case "filename":
					header('Content-disposition: filename='.$opt); 
				break;
				case "download":
					header('Content-disposition: attachment; filename='.$opt); 
				break;
			}
		}
		
		
	}


	function getClientIp() {
		if (! isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$client_ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		return $client_ip;
	}
}
?>