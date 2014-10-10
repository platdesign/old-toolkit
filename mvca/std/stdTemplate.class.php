<?PHP
class stdTemplate {
	function auto($view,$obj) {
		if($view->template) {
			if( method_exists( $this, $view->template )) {
				$this->{$view->template}($view,$obj);
			}
		} elseif($obj->template[1]){
			if( method_exists( $this, $obj->template[1]) ) {
				$this->{$obj->template[1]}($view,$obj);
			}
		} else {
			switch($obj->request->type) {
				case "browser":
				case "unknown":
					$this->html($view);
				break;
				case "ajax":
					$this->json($view);
				break;
			}
		}
	}
	
	function json($view,$obj) {
		php::sendHeader("JSON");
		echo json_encode($view->data);
	}
	
	function api($view,$obj) {
		if($obj->input['jsonp']) {
			$this->jsonp($view,$obj);
		} else {
			$this->json($view,$obj);
		}
	}
	
	function jsonp($view,$obj) {
		php::sendHeader("JS");
		echo $obj->input['jsonp']."(".json_encode($view->data).");";
	}
	
	function html($view) {
		php::sendHeader("HTML");
		echo 
			html::doctype().
			html::openTag("html").
				html::head($view->head).
				html::body($view->body).
			html::closeTag("html");
	}
}


?>