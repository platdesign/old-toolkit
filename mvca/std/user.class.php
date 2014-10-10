<?PHP


class user {
	
	private $mysql;
	function __construct($mysql=null) {
		if($mysql) {
			$this->setMysql($mysql);
		}
	}
	
	public function setMysql($obj) {
		$this->mysql = $obj;
		$this->createTable();
	}

	public function setSecret($secret) {
		if(!empty($secret)) {
			$this->secret = password::create($secret);
			
			$query = new mysqlquery();
			$query->table("user")
				->set(array("secret"=>$this->secret))
				->where(array("uid"=>$this->uid));
			
			return $this->mysql->query( $query->update() );
		}
	}

	public function loadById($id) {
		
		$query = new mysqlquery();
		$query->table("user")
			->where(array("uid"=>$id));
		
		$data 	= $this->mysql->getSingleData( $query->select() );
		if($data) {
			foreach($data as $key => $val) {
				$this->{$key} = $val;
			}
		}
		
		return $data;
	}
	public function loadByMail($mail) {
		
		$query = new mysqlquery();
		$query->table("user")
			->where(array("mail"=>$mail));

		$data 	= $this->mysql->getSingleData( $query->select() );
		if($data) {
			foreach($data as $key => $val) {
				$this->{$key} = $val;
			}
		}
		
		return $data;
	}
	
	
	
	public function login($mail,$secret) {
		
		if(!$mail) {
			throw new Exception("user::login::no mail");
		} else {
			if(!checkstr::email($mail)) {
				throw new Exception("user::login::invalid mail");
			} else {
				if(!$secret) {
					throw new Exception("user::login::no secret");
				} else {
					if(!checkstr::secret($secret)) {
						throw new Exception("user::login::invalid secret");
					} else {
						$this->loadByMail($mail);	
						if(!$this->uid) {
							throw new Exception("user::login::user not exist");
						} else {
							if(!$this->activated) {
								throw new Exception("user::login::deactivated");
							} else {
								if( !password::validate($secret,$this->secret) ) {
									throw new Exception("user::login::secret does not match");
								} else {
									session::set("user",$this);

									$query = new mysqlquery();
									$query->table("user")
										->set(array(
											"lastLoginIp" 	=> 	php::getClientIp(),
											"lastLoginTS"	=>	time(),
											"loginCount"	=>	$this->loginCount + 1,
										))
										->where( array("uid"=>$this->uid) );

									$this->mysql->query( $query->update() );
								}
							}
						}
					}
				}
			}
		}
	}
	public function logout() {
		session::set("user",NULL);
	}
	public function register($mail,$secret,$prename,$surname) {

		if(!$prename) {
			throw new Exception("user::register::no prename");
		} else {
			if(!$surname) {
				throw new Exception("user::register::no surname");
			} else {
				if(!$mail) {
					throw new Exception("user::register::no mail");
				} else {
					if(!checkstr::email($mail,TRUE)) {
						throw new Exception("user::register::invalid mail");
					} else {
						if(!$secret) {
							throw new Exception("user::register::no secret");
						} else {
							if(!checkstr::secret($secret)) {
								throw new Exception("user::register::invalid secret");
							} else {
								if($this->loadByMail($mail)) {
									throw new Exception("user::register::user exists");
								} else {
									
									// Wirkliche Registrierung
									$query = new mysqlquery();
									$query->table("user")
										->rows(array(
											"mail",
											"secret",
											"createTS",
											"lastLoginTS",
											"activated",
											"loginCount",
											"createIp",
											"lastLoginIp",
											"prename",
											"surname",
											))
										->vals(array(
											$mail,
											password::create($secret),
											time(),
											time(),
											FALSE,
											0,
											php::getClientIp(),
											php::getClientIp(),
											$prename,
											$surname,
											));

									return $this->mysql->query( $query->insert() );
								}
							}
						}
					}
				}
			}
		}
		
	}
	public function activate() {
		$query = new mysqlquery();
		
		$query->table("user")
			->set(array("activated"=>TRUE));
			
		$this->mysql->query( $query->update() );
	}
	public function deactivate() {
		$query = new mysqlquery();
		
		$query->table("user")
			->set(array("activated"=>FALSE));
			
		$this->mysql->query( $query->update() );
	}

	private function loadFromSession() {
		if(session::get("user")) {
			foreach(session::get("user") as $key => $val) {
				$this->{$key} = $val;
			}
		}
	}

	public function isActivated() {
		return $this->activated;
	}
	public function isOnline() {
		if( session::get("user") ) {
			$this->loadFromSession();
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function edit($key,$val) {
		
		if( !checkstr::string($val) ) {
			throw new Exception("user::edit::empty val");
		} else {
			$this->{$key} = $val;

			$query = new mysqlquery();
			$query->table("user")
				->set(array(
					$key 	=> 	$val,
				))
				->where( array("uid"=>$this->uid) );

			$this->mysql->query( $query->update() );
		}
	}
	
	public function createTable() {
		$query = "CREATE TABLE IF NOT EXISTS `user` (
		  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `prename` varchar(128) DEFAULT NULL,
		  `surname` varchar(128) DEFAULT NULL,
		  `mail` varchar(128) NOT NULL DEFAULT '',
		  `secret` varchar(512) NOT NULL DEFAULT '',
		  `createTS` int(11) DEFAULT NULL,
		  `lastLoginTS` int(11) DEFAULT NULL,
		  `loginCount` int(11) DEFAULT NULL,
		  `activated` smallint(1) DEFAULT NULL,
		  `createIp` varchar(15) DEFAULT NULL,
		  `lastLoginIp` varchar(15) DEFAULT NULL,
		  PRIMARY KEY (`uid`),
		  UNIQUE KEY `mail` (`mail`)
		) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;";
		$this->mysql->query($query);
	}
}

?>