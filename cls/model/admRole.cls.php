<?php
/**
 * board
 */
namespace cls\model;

class admRole {
	private $tableName = 'adm_role';
	private $uniqKeys = array('seq');
	private $row = array(
		'seq' => NULL,						// 시퀀스
		'nm' => '',							// 역할명
		'right_user' => 'N',					// 회원접근 권한 (Y:모든회원, N:본인회원)
		'right_settle' => 'N',					// 결제접근 권한 (Y:모든회원, N:본인회원)
		'right_access' => 'N',					// 접속접근 권한 (Y:어디서나, N:사무실에서만))
		'dtReg' => '0000-00-00 00:00:00',	// 등록일시
		'dtMdf' => '0000-00-00 00:00:00'	// 수정일시
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
