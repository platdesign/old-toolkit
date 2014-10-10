<?PHP
class widget {
	function footer($content=array()) {
		$return = NULL;
		if( isset($content['domain'] )) {
			$return .= html::a($content['domain'],array("href"=>"http://".$content['domain']));
		}
		
		if( isset($content['startYear'] )) {
			$return .= " &copy; ".$content['startYear']."/".date("y");
		}
		
		return $return;
	}
}
?>