<?php
/**
 * boardList
 */
namespace cls\model;

class mylist {
	protected $_get;						// $_GET
	protected $tableName;					// 테이블명
	protected $queryStr = "";				// 목록 조회 쿼리
	protected $countQueryStr = "";			// 목록 카운트 쿼리
	protected $conditionQry = array();
	protected $bindingArr = array();		// 목록 조회 바인딩 변수 배열
	protected $sortKeys;					// ex) array('dtReg' => 'ASC', 'title' => 'DESC')

	public function __construct($getArr=array(), $sortArr=array(), $tableName='')
	{
		$this->tableName = $tableName;
		$this->_get = $getArr;
		$this->sortKeys = $sortArr;
		$this->mkCondition();
		$this->mkQry();
	}

	function __destruct()
	{

	}

	/**
	 * 쿼리 조건식 생성 (자식 클래스에서 오버라이딩 처리할 것)
	 * @return [type] [description]
	 */
	public function mkCondition()
	{
		// $sql = array();
		foreach ($this->_get as $key => $value) {
			if (in_array($key, array('nm'))) {
				$this->conditionQry[] = $key . " LIKE ?";
				$this->bindingArr[] = "%" . $value . "%";
			}
			elseif (in_array($key, array('id'))) {
				$this->conditionQry[] = $key . "=?";
				$this->bindingArr[] = $value;
			}
			// elseif ($key == 'dtDiv') {
			// 	$this->sortKeys[] = array($value => $this->_get['sortDiv']);	// 정렬 키 항목 (디퐅트 조건 ?)
			// }
		}
	}

	public function mkQry()
	{
		if (!empty($this->conditionQry)) {
			$whereStr = implode(" AND ", $this->conditionQry);
		} else {
			$whereStr = "1=1";
		}

		$this->countQueryStr = "SELECT COUNT(*) AS cnt FROM {$this->tableName} WHERE " . $whereStr;

		$sort = array();
		foreach ($this->sortKeys as $key => $value) {
			foreach ($value as $k => $v) {
				$sort[] = $k . " " . $v;
			}
		}
		if (empty($sort)) {
			$sort = array('dtReg' => 'DESC');	// 정렬 키 항목 (디퐅트 조건 ?)
		}

		$this->queryStr = "SELECT * FROM {$this->tableName} WHERE " . $whereStr . " ORDER BY " . implode(", ", $sort);
	}

	public function getQueryStr()
	{
		return $this->queryStr;
	}

	public function getCountQueryStr()
	{
		return $this->countQueryStr;
	}

	public function getBindingArr()
	{
		return $this->bindingArr;
	}

	public function setSortKeys($arr=array())
	{
		$this->sortKeys = $arr;
	}
	
}
