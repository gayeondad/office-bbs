<?php
/**
 * 
 */
namespace cls\model;
use Exception;

trait dbCon
{
	use \cls\common\util;
	
	public $db;

	public function opnDb($bool=false)
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
			echo "DB 연결 에러: " . $this->mkErrMsg($e);
		}
	}

	public function clsDb()
	{
		$this->db->close();
	}

	public function runQry($queryStr='', $keyArr=array())
	{
		$bool = false;
		$errMsg = "";
		$errYn = false;
		try {
			// $this->db->BeginTrans();

			$stmt = $this->db->Prepare($queryStr);
			if ($this->db->Execute($stmt, $keyArr)) {
				// $this->db->CompleteTrans();		// commit
				$bool = true;
			}
			else {
				// $this->db->FailTrans();			// rollback
				$errYn = true;
				$errMsg = "[ SQL Error: " . $this->db->ErrorNo() . " ] " . $this->db->ErrorMsg();
				// $this->loggingDbErr($queryStr, json_encode($keyArr, JSON_UNESCAPED_UNICODE));
			}
		}
		catch (Exception $e) {
			$errMsg = "[실행 에러] " . $this->mkErrMsg($e);
			echo $errMsg;
		}
		finally {
			if ($errYn) {
				$this->loggingDbErr($queryStr, json_encode($keyArr, JSON_UNESCAPED_UNICODE), $errMsg);	// logging error
			}

			return $bool;
		}
	}


	public function selQryOfRow($queryStr='', $keyArr=array())
	{
		$rs = $this->db->Execute($queryStr, $keyArr);
		if ($rs->fields) {
			return $rs->fetchrow();
		}
		return false;
	}

	public function selQryOfRows($queryStr='', $keyArr=array())
	{
		$rows = array();
		$rs = $this->db->Execute($queryStr, $keyArr);
		if ($rs->fields) {
			while ($data = $rs->fetchrow()) {
				$rows = $data;
			}
		}
		return $rows;
	}

	public function insQryOfRow($tblnm='', $arr=array(), $unqkeys=array(), $type='')
	{
		if (empty($arr)) return false;

		$bool = false;
		$columnNm = array();
		$columnMap = array();
		$columnVal = array();
		$columnDup = array();

		foreach ($arr as $key => $value) {
			$columnNm[] = $key;
			$columnMap[] = '?';
			$columnVal[] = $value;
			if (!in_array($key, $unqkeys)) {
				$columnDup[] = $key . "=VALUES(" . $key . ")";
			}
		}
		$strNm = implode(",", $columnNm);
		$strMap = implode(",", $columnMap);
		$strDup = implode(",", $columnDup);

		$ignore = ($type == 'ignore') ? " IGNORE " : "";
		$query = "INSERT {$ignore} INTO {$tblnm} ({$strNm}) VALUES ({$strMap})";
		if ($type == 'duplicate') {
			$query .= " ON DUPLICATE KEY UPDATE {$strDup}";
		}
		
		try {
			// $this->db->BeginTrans();

			if ($this->db->Execute($query, $columnVal)) {
				// $this->db->CompleteTrans();		// commit
				$bool = true;
			}
			else {
				// $this->db->FailTrans();			// rollback
				$this->loggingDbErr($query, json_encode($columnVal, JSON_UNESCAPED_UNICODE));
			}
		}
		catch (Exception $e) {
			echo $tblnm . ' 저장 예외 발생: ' . $e->getMessage() . "\n";
		}
		finally {
			return $bool;
		}
	}

	public function loggingDbErr($queryStr='', $argStr='', $errStr='')
	{
		if (empty($queryStr))  return false;

		$bool = false;	// 반환 변수 설정
		$keyArr = array($_SERVER["REQUEST_URI"], $queryStr, $argStr, $errStr);
		$qryRunLogDbErr = "INSERT INTO dbErrLog (vPath, tQuery, vArg, vMsg) VALUES (?,?,?,?)";
		$stmt = $this->db->Prepare($qryRunLogDbErr);
		try {
			if ($this->db->Execute($stmt, $keyArr)) {
				$bool = true;
			}
		}
		catch (Exception $e) {
			echo "DB 실행 에러: " . $this->mkErrMsg($e);
		}
		finally {
			return $bool;
		}
	}

}
