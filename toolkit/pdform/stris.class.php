<?PHP


class stris {
	function int($input) {
		$newVal = self::numeric($input);
		if(isset($newVal)) {
			if($newVal == (int)$newVal) {
				return (int) $newVal;
			}
		}
	}
	
	function float($input) {
		$newVal = self::numeric($input);
		if(isset($newVal)) {
			if($newVal == (float)$newVal) {
				return (float) $newVal;
			}
		}
	}
	
	function email($input,$checkDNS = FALSE) {
		if( filter_var($input, FILTER_VALIDATE_EMAIL) ) {
			if($checkDNS) {
				$tmp = explode("@",$input);
				$domain = $tmp[1];
				if( dns_get_record( $domain )) {
					return $input;
				} else {
					return NULL;
				}
			} else {
				return $input;
			}
		} else {
			return NULL;
		}
	}
	
	function url($input,$checkDNS = FALSE) {
		$sanitized = filter_var($input, FILTER_SANITIZE_URL);
		$schemepos = strpos( substr( $sanitized, 0, 9 ), "://");

		if($schemepos === false) {
			$sanitized = "http://".$sanitized;
		}
		if($schemepos === 0) {
			$sanitized = "http".$sanitized;
		}
		
		if( filter_var($sanitized, FILTER_VALIDATE_URL) ) {
			if($checkDNS) {
				$tmp = parse_url($sanitized);
				$domain = $tmp['host'];
				if( dns_get_record( $domain )) {
					return $sanitized;
				} else {
					return NULL;
				}
			} else {
				return $sanitized;
			}
		} else {
			return NULL;
		}
	}
	
	function numeric($input) {
		$input = str_replace(",",".",$input);
		if( is_numeric($input)) {
			return (float) $input;
		}
	}
}


?>