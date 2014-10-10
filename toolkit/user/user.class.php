<?PHP

require_once dirname(__FILE__)."/../session/session.class.php";
require_once dirname(__FILE__)."/../password/password.class.php";

class user {
	static protected $mysql;
	
	public static function setMysql($mysql) {
		static::$mysql = $mysql;
	}
	
	public static function create($mysql,$vals=array()) {
		if( count($vals) > 0) {
			
		}
	}
	
	public static function get() {
		return session::get("user");
	}
	
	public static function login($vals=array()) {
		if( user::isOnline() ) {
			user::logout();
		}
		
		if( $user = user::exists($vals['email']) ) {
			if( password::validate($vals['secret'],$user->secret) ) {
				session::set("user",$user);
				
				$db = static::$mysql;
				$statement = $db->prepare("UPDATE `user` SET `lastLoginTS` = :time, `loginCount` = `loginCount` + 1, `lastLoginIp` = :ip WHERE `email` = :email");
				$statement->execute(array(
					":email" => $user->email,
					":ip"	 => php::getClientIp(),
					":time"	 =>	time(),
				));
				
				return true;
			}
		}
	}
	
	public static function logout() {
		session::del("user");
	}
	
	
	public static function register($vals=array()) {
		if( user::isOffline() ) {
			
			if( isset(
				$vals['firstname'],
				$vals['lastname'],
				$vals['email'],
				$vals['secret']
			)) {
				$db = static::$mysql;

				$secret = password::create($vals['secret']);

				$statement = $db->prepare("INSERT INTO `user` (
					`firstname`,
					`lastname`,
					`email`,
					`secret`,
					`createTS`,
					`loginCount`,
					`createIp`,
					`activated`
				) VALUES (
					:firstname,
					:lastname,
					:email,
					:secret,
					:createTS,
					:loginCount,
					:createIp,
					:activated
				)");


				return $statement->execute(array(
					':firstname' 	=> $vals['firstname'],
					':lastname' 	=> $vals['lastname'],
					':email' 		=> strtolower($vals['email']),
					':secret' 		=> $secret,
					':createTS' 	=> time(),
					':loginCount'	=> 0,
					':createIp' 	=> php::getClientIp(),
					':activated' 	=> 1,
				));
			}
			
			
			
		}
	}
	
	public static function exists($email) {
		$db = static::$mysql;
		
		$statement = $db->prepare("SELECT * FROM `user` WHERE `email` = :email");
		
		$statement->execute(array(':email' => strtolower($email)));
		$row = $statement->fetchObject();
		
		if($row) {
			return $row;
		}
	}
	
	public static function isOnline() {
		if( session::get("user") ) {
			if( session::get("user")->uid ) {
				return true;
			}
		}
	}
	
	public static function isOffline() {
		if( !user::isOnline() ) {
			return TRUE;
		}
	}
	
	
}
?>