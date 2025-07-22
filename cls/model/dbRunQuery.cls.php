<?php
/**
 * 기본형 쿼리
 */
namespace cls\model;

class dbRunQuery implements runQuery {
	// use cls\common\util;
	use dbCon;

	public $tableName;
	public $uniqKeys;
	public $row;

	public function __construct($tableName='', $uniqKeys=array(), $row=array(), $bool=false)
	{
		$this->opnDb($bool);

		$this->tableName = $tableName;
		$this->uniqKeys = $uniqKeys;
		$this->row = $row;
	}

	function __destruct()
	{

	}

	public function setRow($arr=array())
	{
		foreach ($this->row as $key => $value) {
			if (array_key_exists($key, $arr)) {
				$this->row[$key] = $arr[$key];
			}
		}
	}

	public function getRow($key='')
	{
		if (empty($key)) {
			return $this->row;
		}
		if (array_key_exists($key, $this->row)) {
			return $this->row[$key];
		}
		return false;
	}

	public function insQry()
	{
		foreach ($this->row as $key => $value) {
			$columnName[] = $key;
			$columnMap[] = '?';
			$columnValue[] = $value;
		}

		$query = "INSERT INTO {$this->tableName} (" . implode(",", $columnName) . ") VALUES (" . implode(",", $columnMap) . ")";
		if ($this->runQry($query, $columnValue)) {
			return true;
		}
		return false;
	}

	public function updQry()
	{
		foreach ($this->row as $key => $value) {
			if (in_array($key, $this->uniqKeys)) {
				$conditionNameMap[] = $key . "=?";
				$conditionValue[] = $value;
			}
			else {
				$columnNameMap[] = $key . "=?";
				$columnValue[] = $value;
			}
		}
		if (empty($conditionValue)) {
			echo '수정할 유니크키 부존재';
			return false;
		}

		$query = "UPDATE {$this->tableName} SET " . implode(", ", $columnNameMap) . " WHERE " . implode(" AND ", $conditionNameMap);
		if ($this->runQry($query, array_merge($columnValue, $conditionValue))) {
			return true;
		}
		return false;
	}

	public function delQry()
	{
		foreach ($this->row as $key => $value) {
			if (in_array($key, $this->uniqKeys)) {
				$conditionNameMap[] = $key . "=?";
				$conditionValue[] = $value;
			}
		}
		if (empty($conditionValue)) {
			echo '삭제할 유니크키 부존재';
			return false;
		}

		$query = "DELETE FROM {$this->tableName} WHERE " . implode(" AND ", $conditionNameMap);
		if ($this->runQry($query, $conditionValue)) {
			return true;
		}
		return false;
	}

	public function selQry($arr=array())
	{
		$condtionKeys = (!empty($arr)) ? $arr : $this->uniqKeys;
		foreach ($condtionKeys as $columnName) {
			if (empty($this->row[$columnName])) {
				echo '존재하지 않는 칼럼명: ' . $columnName;
				continue;
			}
			$conditionNameMap[] = $columnName . "=?";
			$conditionValue[] = $this->row[$columnName];
		}
		$query = "SELECT * FROM {$this->tableName} WHERE "  . implode(" AND ", $conditionNameMap);
		$rs = $this->db->execute($query, $conditionValue);
		if ($rs->fields) {
			$this->setRow($rs->fetchrow());
			return true;
		}
		return false;
	}

	/**
	 * 중복체크 : 아이디
	 * @param  string $id [description]
	 * @return [type]     [description]
	 */
	public function chkDbl($arr=array())
	{
		if (empty($arr)) return false;
		foreach ($arr as $key => $value) {
			$conditionNameMap[] = $key . "=?";
			$conditionValue[] = $value;
		}
		$query = "SELECT 1 FROM {$this->tableName} WHERE " . implode(" AND ", $conditionNameMap) . " LIMIT 1";
		$rs = $this->db->execute($query, $conditionValue);
		if ($rs->fields) {
			return true;
		}
		return false;
	}

}
