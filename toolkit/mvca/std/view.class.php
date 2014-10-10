<?PHP
class view {
	var $data,$body,$head,$jslinks,$csslinks;
	
	public function __construct() {
		$this->jslink("http://a.platdesign.de/js/jqplugins__pdajax");
	}
	
	public function body($inner) {
		$this->body .= $inner;
	}
	
	public function data($getter,$setter=null) {
		if($setter === NULL) {
			return $this->data[$getter];
		} else {
			$this->data[$getter] = $setter;
		}
	}
	
	public function setTemplate($template) {
		$this->template = strtolower($template);
	}
	
	public function jslink($link) {
		$this->jslinks[] = $link;
	}
	
	public function csslink($link) {
		$this->csslinks[] = $link;
	}
	
	public function javascript($code) {
		$this->javascript .= $code;
	}
	
	public function css($code) {
		$this->css .= $code;
	}
	
	public function _show($data,$function) {
		
		if($data) {
			$return = NULL;
			foreach($data as $key => $val) {
				$return .= $function($val,$key);
			}
		}
		return $return;
	}
	
}

?>