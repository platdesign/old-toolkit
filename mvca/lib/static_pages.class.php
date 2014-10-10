<?PHP
class static_pages {
	static $table = "static_pages";
	
	function __construct($mysql) {
		$this->mysql = $mysql;
		$this->createTable();
	}
	
	function getPage($page_name,$group) {
		$query = "SELECT * FROM `".self::$table."` WHERE `page_name` = '$page_name' AND `group` = '$group';";
		return $this->mysql->getSingleData($query);
	}
	
	function getAllTitlesOfGroup($group) {
		$query = "SELECT * FROM `".self::$table."` WHERE `group` = '$group' ORDER BY `order`";
		return $this->mysql->getMultiData($query);
	}


	function createTable() {
		$query = "CREATE TABLE `".self::$table."` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `page_name` varchar(128) DEFAULT NULL,
		  `title` varchar(300) DEFAULT NULL,
		  `body` longtext,
		  `last_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
		  `createTS` int(11) DEFAULT NULL,
		  `group` varchar(128) DEFAULT NULL,
		  `order` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `page_name` (`page_name`),
		  UNIQUE KEY `page_name_2` (`page_name`,`group`)
		) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
		
		$this->mysql->query($query);
	}
}
?>