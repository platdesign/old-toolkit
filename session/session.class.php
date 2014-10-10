<?PHP
class session {
	
	function del($key) {
		unset($_SESSION[$key]);
	}
	
	function set($key,$val) {
		$_SESSION[$key] = $val;
	}
	
	function get($key) {
		if(isset($_SESSION[$key])) {
			return($_SESSION[$key]);
		}
	}
	
	function start($id=null) {
		if($id) {
			session_id($id);
		}
		session_start();
		return session_id();
	}
	
	function stop() {
		session_destroy();
		unset($_SESSION);
	}
	
}
?>