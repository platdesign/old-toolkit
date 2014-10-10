<?PHP

require_once "pdform_elements.class.php";
require_once "pdform_validator_rules.class.php";
require_once "stris.class.php";
require_once "pdStdForm.class.php";

class pdform {
	
	var $elements = array();
	var $enctype = "application/x-www-form-urlencoded";
	
	public static function create($array=array()) {
		$form = new pdform();
		foreach($array as $element) {
			$form->addElement($element);
		}
		$form->validate();
		return $form;
	}
	
	public function addElement($array=array()) {
		$array = (array) $array;
		if( isset($array['type']) ) {
			$elementClass = "pdform_element_".$array['type'];
			if( class_exists($elementClass) ) {
				
				if(!isset( $array['name'] ) ) {
					$array['name'] = "_".count((array)$this->elements);
				}
				$this->elements{ $array['name'] } = new $elementClass($array);
				
				if(in_array($array['type'],array("file","image"))) {
					$this->enctype = "multipart/form-data";
				}
			}
		}
	}

	public function validate() {
		$this->createFormId();
		$this->readPOST();
		
		if( $this->isSend() ) {
			$this->val_status = TRUE;
			
			foreach($this->elements as $name => $element) {
				if( $element->isUserInput() ) {
					
					$status = $element->validate($this->elements);
					
					if(isset($element->validation->newVal)) {
						$element->attr['value'] = $element->validation->newVal;
					} else if($element->postVal) {
						$element->attr['value'] = $element->postVal;
					}
					
					$element->value = $element->attr['value'];
					
					if($status === FALSE) {
						$this->val_status = FALSE;
					}
				}
			}
			
		}
	}

	public function render() {
		foreach($this->elements as $name => $element) {
			$elements .= $element->render();
		}
		return $this->openForm() . $this->renderMessage() . $elements . $this->closeForm();
	}
	
	public function renderMessage() {
		if($this->errorMessage) {
			return html::div($this->errorMessage,array("class"=>"errorMessage"));
		}
		if($this->successMessage) {
			return html::div($this->successMessage,array("class"=>"successMessage"));
		}
	}
	
	public function openForm() {
		return 
			html::openTag("form",array("class"=>"pdform","method"=>"POST","enctype"=>$this->enctype)).
			html::input(array(
				"type"	=>	"hidden",
				"value"	=>	$this->id,
				"name"	=>	"formid",
			));
		
	}
	
	public function closeForm() {
		return html::closeTag("form");
	}
	
	public function getVals() {
		if( $this->val_status == TRUE ) {
			foreach($this->elements as $name => $element) {
				if( $element->isUserInput() ) {
					$return[$name] = $element->value;
				}
			}
			return $return;
		}
	}
	
	public function callback($function=null) {
		$vals = $this->getVals();
		
		if( isset($vals)) {
			if(isset($function)) {
				return $function($vals,$this);
			} else {
				return true;
			}
		} 
		
	}
	
	private function readPOST() {
		if( $this->isSend() ) {
			foreach($this->elements as $name => $element) {
				if($element->attr['type'] == 'image') {
					if( $_FILES[$name] ) {
						if(!empty($_FILES[$name]['tmp_name'])) {
							$element->postVal = $_FILES[$name];
						} else {
							$element->postVal = $_POST[$name."_old"];
						}
					}
				} else {
					if( isset($_POST[$name]) ) {
						if( $element->isUserInput() ) {
							$element->postVal = trim($_POST[$name]);
						}
					}
				}
				
			}
		}
	}

	private function createFormId() {
		foreach($this->elements as $element) {
			$string .= $element->attr['name'];
		}
		$this->id = md5($string."pdform");
	}

	public function isSend() {
		if(isset($_POST) AND $_POST['formid'] == $this->id) {
			return TRUE;
		}
	}

	function reset() {
		foreach($this->elements as $element) {
			unset($element->postVal);
			unset($element->newVal);
			unset($element->attr['value']);
		}
	}
}



class pdform_element {
	function __construct($attr) {
		$this->attr = $this->obj2array($attr);
	}
	
	function render() {
		$class = ( isset($this->attr['size']) ) ? "element ".$this->attr['size'] : "element one-one";
		$class .= " ".$this->attr['type'];
		return 
			html::div( 
				$this->render_element(),
				array(
					"class"=>$class,
					"name"=>$this->attr['name'],
					"data-validation" => json_encode($this->attr['validation'])
				) 
			);
	}
	
	function render_label() {
		return html::label($this->attr['label'],array("title"=>$this->attr['label'],"class"=>"title","for"=>$this->attr['name']));
	}
	
	function render_error() {
		if($this->validation->status === FALSE) {
			return html::label($this->validation->message,array("class"=>"error","for"=>$this->attr['name']));
		}
	}
	
	function filterArray() {
		$allowed	= func_get_args();
		$attr 		= $this->attr;
		foreach($attr as $key => $val) {
			if( in_array($key,$allowed) ) {
				$return[$key] = $val;
			}
		}
		
		$return['id'] = $attr['name'];
		return $return;
	}

	function isUserInput() {
		
		$notUserInput = array(
			"submit",
			"html",
		);
		
		
		if(!in_array($this->attr['type'],$notUserInput) ) {
			return true;
		}
	}

	function validate($elements) {
		$this->attr['value'] = $this->postVal;
		
		$this->validation 			= new StdClass;
		$this->validation->status 	= TRUE;
		$this->validation->message 	= NULL;
		
		

		if( is_array($this->attr['validation']) ) {
			
			$rules = $this->attr['validation'];
			
			
			foreach($rules as $rulename => $opt) {
				
				$input = (isset($this->validation->newVal)) ? $this->validation->newVal : $this->postVal;

				if(isset($rules['required']) AND $rules['required'][0] == TRUE OR $input OR $input === "0") {
					
					if($input === "0") {
						$input = (float) 0;
					}
					
					$valy = pdform_validator_rules::$rulename($opt,$input,$elements);
					
					if($valy) {
						foreach($valy as $key => $val) {
							$this->validation->{$key} = $val;
						}
					}
					

					if($this->validation->status == FALSE) {
						return FALSE;
					}
				}
				
				
			}
			
			
			return TRUE;
		}
	}

	function obj2array($obj) {
		foreach($obj as $key => $val) {
			if(is_object($val)) {
				$return[$key] = $this->obj2array($val);
			} else {
				$return[$key] = $val;
			}
		}
		return $return;
	}
}

?>