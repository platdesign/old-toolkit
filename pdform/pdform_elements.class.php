<?PHP

class pdform_element_text extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"type"	=>	"text",
						"title"	=>	$this->attr['label'],
						"autocomplete"	=>	"off",
					),
					$this->filterArray("name","value","class","title","placeholder","style")
				);
				
				
		return
			$this->render_label().
			html::input($attr).
			$this->render_error();
	}
}

class pdform_element_textarea extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"type"	=>	"text",
						"title"	=>	$this->attr['label'],
						"autocomplete"	=>	"off",
					),
					$this->filterArray("name","value","class","title","placeholder","style")
				);
				
				
		return
			$this->render_label().
			html::textarea($attr['value'],$attr).
			$this->render_error();
	}
}


class pdform_element_password extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"type"	=>	"password",
						"title"	=>	$this->attr['label'],
					),
					$this->filterArray("name","class","title","placeholder")
				);
				
		return
			$this->render_label().
			html::input($attr).
			$this->render_error();
	}
}

class pdform_element_select extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"title"		=>	(isset($this->attr['label'])) ? $this->attr['label'] : NULL,
						"multiple" 	=>	($this->attr['multiple'] == TRUE) ? "multiple" : NULL,
						"size" 		=>	(isset($this->attr['height'])) ? $this->attr['height'] : NULL,
					),
					$this->filterArray("name","class","value","title","placeholder")
				);
				
		return
			$this->render_label().
			html::select($this->attr['options'],$attr).
			$this->render_error();
	}
}

class pdform_element_radio extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"title"		=>	(isset($this->attr['label'])) ? $this->attr['label'] : NULL,
						"multiple" 	=>	($this->attr['multiple'] == TRUE) ? "multiple" : NULL,
						"size" 		=>	(isset($this->attr['height'])) ? $this->attr['height'] : NULL,
					),
					$this->filterArray("name","class","value")
				);
				
				
		if(is_array($this->attr['options'])) {
			foreach($this->attr['options'] as $name => $opt) {
				$radioAttr = array_merge($attr,array(
					"type"	=>	"radio",
					"id"	=>	$attr['id']."_".$name,
					"title"	=>	NULL,
					"checked"=>	($this->attr['value'] == $name) ? "checked" : NULL,
					"value"	=>	$name,
				));
				$radios .= html::label(html::input($radioAttr).html::span($opt),array("class"=>"radio","title"=>$opt));
			}
		}
				
		return
			$this->render_label().
			html::fieldset(NULL,$radios,array("id"=>$attr['id'])).
			$this->render_error();
	}
}

class pdform_element_checkbox extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"type"	=>	"checkbox",
						"title"	=>	$this->attr['label'],
						"checked" => ($this->attr['value'] == true) ? "checked" : NULL,
						"value"	=> true,
					),
					$this->filterArray("name","class","title","style")
				);
				
		return
			html::fieldset(NULL, html::label(html::input($attr).html::span($this->attr['label']),array("class"=>"checkbox"))).
			$this->render_error();
	}
}

class pdform_element_submit extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"type"	=>	"submit",
						"title"	=>	$this->attr['label'],
						"value"	=>	$this->attr['label'],
						
					),
					$this->filterArray("name","class","title","style")
				);
				
				
		return html::input($attr);
	}
}

class pdform_element_hidden extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"type"	=>	"hidden",
					),
					$this->filterArray("name","value")
				);
				
				
		return html::input($attr);
	}
}

class pdform_element_reset extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"type"	=>	"reset",
						"title"	=>	$this->attr['label'],
						"value"	=>	$this->attr['label'],
						
					),
					$this->filterArray("name","class","title","style")
				);
				
				
		return html::input($attr);
	}
}

class pdform_element_html extends pdform_element {
	function render_element() {
		return $this->attr['content'];
	}
}

class pdform_element_file extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"type"	=>	"file",
						"title"	=>	$this->attr['label'],
						"autocomplete"	=>	"off",
					),
					$this->filterArray("name","class","title","style")
				);
				
		
		return
			$this->render_label().
			$this->attr['value']['name'].
			html::input($attr).
			$this->render_error();
	}
}

class pdform_element_image extends pdform_element {
	function render_element() {
		$attr = array_merge( 
					array(
						"type"	=>	"file",
						"title"	=>	$this->attr['label'],
						"autocomplete"	=>	"off",
					),
					$this->filterArray("name","class","title","style")
				);
		
		$oldVal = (file_exists($this->attr['value']['tmp_name'])) ? $this->attr['value']['name'] : $this->attr['value'];
		
		if($this->attr['preview']) {
			$icon = html::img($this->attr['preview'],array("width"=>"150px")).html::br();
		}
		
		return
			$this->render_label().
			$icon.
			html::input(array("type"=>"hidden","value"=>$oldVal,"name"=>$this->attr['name']."_old")).
			html::input($attr).
			$this->render_error();
	}
}















?>