<?php
/**
 * Database
 */
namespace cls\configuration;
use Exception;

class Database {
	protected static $instance = null;
	protected $db;

	public function __construct($bool=false)
	{
		$this->connectDb($bool);
	}

	function __destruct()
	{
		if ($this->db) {
			$this->db->close();
			$this->db = null;
		}
	}

	private function connectDb($bool=false)
	{
		try {
			include('adodb5/adodb.inc.php');
			$this->db = adoNewConnection('mysqli');
			$this->db->connect("localhost", "webuser", "qhdks8", "anal_db");
			$this->db->Execute("SET session wait_timeout=20");
			$this->db->SetFetchMode(ADODB_FETCH_ASSOC);
			$this->db->debug = $bool;
		}
		catch (Exception $e) {
			echo "DB 연결 에러: " . $e->getMessage();
		}
	}

	public static function getInstance($bool=false)
	{
		if (self::$instance == null) {
			self::$instance = new Database($bool);
		}
		return self::$instance;
	}

	public function getDb()
	{
		return $this->db;
	}
}
