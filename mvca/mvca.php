<?PHP

require_once dirname(__FILE__)."/bootstrap.class.php";
require_once dirname(__FILE__)."/std/stdTemplate.class.php";
require_once dirname(__FILE__)."/std/controller.class.php";
require_once dirname(__FILE__)."/std/model.class.php";
require_once dirname(__FILE__)."/std/view.class.php";


class mvca extends bootstrap {

	public function boot() {
		$this->requireRootFiles();
		$this->instanceController();
		$this->executeMethod();
		$this->loadCssAndJs();
	}
	
	private function requireRootFiles() {
		if( $this->request->type != "ajax" ) {
			if( !$this->requireRootFile("c.php") ) {
				if( !file_exists($this->getRootDir()."static.php") ) {
					$this->root[0] = $this->stdCtrl;
					$this->requireRootFile("c.php");
				}
			}
		} else {
			$this->requireRootFile("a.php");
			//$this->template = array("stdTemplate", "json");
		}
		
		$this->requireRootFile("m.php");
		$this->requireRootFile("v.php");
	}
	
	private function instanceController() {
		$ctrlname = $this->root[0];
		if( class_exists( $ctrlname )) {
			$this->ctrl = new $ctrlname();
		}
		if($this->ctrl) {
			$classname = $this->root[0]."_v";
			if( class_exists( $classname )) {
				$this->ctrl->view = new $classname($this->mysql);
			} else {
				$this->ctrl->view = new view($this->mysql);
			}

			$classname = $this->root[0]."_m";
			if( class_exists( $classname )) {
				$this->ctrl->model = new $classname($this->mysql,$this->couch);
			} else {
				$this->ctrl->model = new model($this->mysql,$this->couch);
			}
			
			if( method_exists($this->ctrl,"init") ) {
				$this->ctrl->init($this->input);
			}
		} else {
			
			$file = $this->getRootDir()."static.php";
			if( file_exists($file) ) {
				$this->ctrl = new controller();
				ob_start();
				include($file);
				$this->ctrl->view->body = ob_get_contents();
				ob_end_clean();
			}
		}

		
	}
	
	private function executeMethod() {
		if($this->ctrl) {
			$this->ctrl->executeMethod($this->root[1],$this->input);
		}
	}
	
	private function loadCssAndJs() {
		$ctrlname 	= $this->root[0];
		$cssfile 	= $this->getRootDir()."css.css";
		$jsfile 	= $this->getRootDir()."js.js";
		
		if(file_exists($cssfile)) {
			$this->ctrl->view->csslink($cssfile);
		}
		if(file_exists($jsfile)) {
			$this->ctrl->view->jslink($jsfile);
		}
		
	}
	
	public function output($opt="auto") {
		$opt = strtolower($opt);
		
		$this->mergeHead();
		
		$tempClass = "stdTemplate";
		if($this->template[0]) {
			$tempClass = $this->template[0];
		}
		
		
		$tempMethod = $opt;
		if($this->ctrl->view->template) {
			$tempMethod = $this->ctrl->view->template;
		}
		
		$output = new $tempClass();
		$output->$tempMethod($this->ctrl->view,$this);
	}
	
	private function mergeHead() {
		$view = $this->ctrl->view;
		
		if(is_array($view->csslinks)) {
			foreach($view->csslinks as $link) {
				if(!empty($link)){
					$view->head .= html::csslink($link);
				}
			}
		}
		
		if(is_array($view->jslinks)) {
			foreach($view->jslinks as $link) {
				if(!empty($link)) {
					$view->head .= html::scriptlink($link);
				}
			}
		}
		
		if(!empty($view->jquery)) {
			$view->head .= html::script('$(document).ready(function(){'.str_replace(array("\n","\t"),"",$view->jquery).'});');
		}
		
		if($view->css) {
			$view->head .= html::style(str_replace(array("\n","\t"),"",$view->css));
		}
	}
}







?>