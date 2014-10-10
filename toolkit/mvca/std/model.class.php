<?PHP
class model {
	public function __construct($mysql,$couchdb) {
		$this->mysql = $mysql;
		$this->couch = $couchdb;
	}
}
?>