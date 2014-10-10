<?PHP
class controller {
	public function __construct() {
		
	}
	
	public function executeMethod($name,$arguments) {
		if( method_exists($this,$name) ) {
			if( is_callable(array($this,$name)) AND $name != "init") {
				$this->{$name}($arguments);
			}
		}
	}
	
	public function location($location) {
		if(is_array($location)) {
			$location = "?".urldecode(http_build_query($location));
		}
		header("Location: ".$location);
	}
}
?>