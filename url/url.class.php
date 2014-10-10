<?PHP
class url {
	function merge($array=array(),$domain=null) {
		return $domain."?".urldecode(http_build_query( array_merge($_GET,$array) ));
	}
}
?>