<?php
/**
 * board
 */
namespace cls\model;

class annualLeave {
	private $tableName = 'annual_leave';
	private $uniqKeys = array('seq');
	private $row = array(
		'seq' => NULL,			// 연차 코드
		'alType' => '',			// 연차타입 ('연차','오전반차','오후반차','경조','기타')
		'alCnt' => '',			// 연차일수
		'sDt' => '',			// 연차 시작일
		'eDt' => '',			// 연차 종료일
		'admSeq' => '',			// 관리자 코드
		'note' => '',			// 비고
		'dtReg' => '0000-00-00 00:00:00'	// 등록일시
		// 'dtMdf' => '0000-00-00 00:00:00'	// 수정일시
	);

	public function __construct()
	{
		
	}

	function __destruct()
	{

	}

	public function getTableName()
	{
		return $this->tableName;
	}

	public function getUniqKeys()
	{
		return $this->uniqKeys;
	}

	public function getRow()
	{
		return $this->row;
	}

}
