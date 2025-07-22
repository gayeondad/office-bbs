<?php
/**
 * board
 */
namespace cls\model;

class attendance {
	private $tableName = 'attendance';
	private $uniqKeys = array('admSeq', 'aDate');
	private $row = array(
		'admSeq' => 0,					// 사원코드
		'aDate' => '0000-00-00',		// 출결일
		'inTime' => '00:00',			// 출근시간
		'outTime' => '00:00'			// 퇴근시간
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
