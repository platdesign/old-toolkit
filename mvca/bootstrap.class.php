<?PHP

@require_once dirname(__FILE__)."/../php/php.class.php";
@require_once dirname(__FILE__)."/../html/html.php";
@require_once dirname(__FILE__)."/../html/html_template.php";
@require_once dirname(__FILE__)."/../phpOnCouch/load.php";
@require_once dirname(__FILE__)."/../session/session.class.php";
@require_once dirname(__FILE__)."/../password/password.class.php";
@require_once dirname(__FILE__)."/../pdform/pdform.php";
@require_once dirname(__FILE__)."/../url/url.class.php";
@require_once dirname(__FILE__)."/../array/array.php";



abstract class bootstrap {
	
	var $root,$input,$appsDir="./apps/";
	public static $mysql;
	
	public function __construct() {
		session::start();
		$this->requireLib();
		$this->detectRequestInfo();
	}

	public function setListener($var,$name="root") {
		if($this->request->type != 'ajax') {
			$this->input = $var;
		} else {
			$this->input = $_POST;
		}
		$this->setRoot($name);
	}
	
	public function setMysql($host,$user,$secret,$db) {
		try {
			$this->mysql = new PDO("mysql:host=$host;dbname=$db;charset=UTF-8", $user, $secret);  
			
			$statement = $this->mysql->prepare("SET character_set_results = 'utf8', 
					character_set_client = 'utf8', 
					character_set_connection = 'utf8', 
					character_set_database = 'utf8', 
					character_set_server = 'utf8'");
					
			$statement->execute();
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function setCouchDB($dns,$db) {
		$this->couch = new couchClient($dns,$db);
	}
	
	public function setAppsDir($dir) {
		$this->appsDir = "./".trim($dir,"./")."/";
	}

	public function setStdCtrl($ctrlname) {
		$this->root[0] = $ctrlname;
		$this->stdCtrl = $ctrlname;
	}

	public function setTemplate($classname,$method) {
		$this->template = array($classname,$method);
	}


	private function requireLib() {
		$dir = dirname(__FILE__)."/lib/";
		$handle = opendir($dir);
		while ($file = readdir ($handle)) {
		    if(substr($file, 0, 1) != ".") {
		        if(is_file($dir.$file)) {
					require_once $dir.$file;
		        }
		    }
		}
		closedir($handle);
	}

	private function detectRequestInfo() {
		if( php::isAjaxRequest() ) {
			$this->request->type = "ajax";
		} elseif( $_SERVER['HTTP_USER_AGENT'] ) {
			$this->request->type = "browser";
			$this->request->info = $_SERVER['HTTP_USER_AGENT'];
		} else {
			$this->request->type = "unknown";
		}
		$this->request->ip 	= php::getClientIp();
	}
	
	private function setRoot($name) {
		$this->root = @explode("/", trim(str_replace("//","/",$this->input[$name]),"/") );
		if(!$this->root[1]) {
			$this->root[1] = "main";
		}
	}


	public function requireRootFile($file) {
		$dir = $this->getRootDir();
		
		if( file_exists( $dir.$file ) ) {
			require_once $dir.$file;
			$this->requiredFiles[] = $dir.$file;
			return true;
		}
	}

	public function getRootDir() {
		return $this->appsDir.$this->root[0]."/";
	}

	
}
?>