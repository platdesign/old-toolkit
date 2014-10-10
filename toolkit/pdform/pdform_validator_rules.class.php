<?PHP

class pdform_validator_rules {
	
	function required($opt,$input,$elements=null) {
	
		if( empty($input) AND $input !== "0") {
			$return->status = FALSE;
			$return->message = $opt[1];
			if($input === "0") {
				$return->newVal = (int) "asd";
			}
		} else {
			$return->status = TRUE;
			$return->message = NULL;
		}
		return $return;
	}
	
	function min_length($opt,$input,$elements) {
		if( strlen($input) < $opt[0] ) {
			$return->status = FALSE;
			$return->message = $opt[1];
		} else {
			$return->status = TRUE;
			$return->message = NULL;
		}
		return $return;
	}
	
	function max_length($opt,$input,$elements) {
		if( strlen($input) > $opt[0] ) {
			$return->status = FALSE;
			$return->message = $opt[1];
		} else {
			$return->status = TRUE;
			$return->message = NULL;
		}
		return $return;
	}
	
	function range_min($opt,$input,$elements) {
		if( $input < $opt[0]) {
			$return->status = FALSE;
			$return->message = $opt[1];
		} else {
			$return->status = TRUE;
			$return->message = NULL;
		}
		return $return;
	}
	
	function range_max($opt,$input,$elements) {
		if( $input > $opt[0] ) {
			$return->status = FALSE;
			$return->message = $opt[1];
		} else {
			$return->status = TRUE;
			$return->message = NULL;
		}
		return $return;
	}
	
	function type($opt,$input,$elements) {
		
			$type = $opt[0];
			$realVal = stris::$type($input,$opt[2]);

			if( isset($realVal) ) {
				$return->status 	= TRUE;
				$return->message 	= NULL;
				$return->newVal 	= $realVal;
			} else {
				$return->status = FALSE;
				$return->message = $opt[1];
			}
		
		return $return;
	}
	
	function own($function,$input,$elements) {
		
		return $function($input,$elements);
		
	}
	
	function mime($opt,$input,$elements) {
		if(is_array($input)) {
			if(!is_array($opt[0])) { $mimes = array($opt[0]); } else { $mimes = $opt[0]; }

			$file = $input["tmp_name"];
			$mime_type = $input["type"];

		//	$fi = new finfo(FILEINFO_MIME_TYPE);
		//	$mime_type = $fi->buffer(file_get_contents($file));


			if(in_array($mime_type,$mimes)) {
				$return->status = TRUE;
				$return->message = NULL;
			} else {
				$return->status = FALSE;
				$return->message = $opt[1];
			}
		} else {
			$return->status = TRUE;
			$return->message = NULL;
		}
		
		return $return;
	}
}

?>