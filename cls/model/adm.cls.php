<?php
/**
 * board
 */
namespace cls\model;

class adm {
	private $tableName = 'adm';
	private $uniqKeys = array('seq');
	private $row = array(
		'seq' => NULL,		// 사원코드
		'id' => '',			// 아이디 (unique key)
		'pw' => '',			// 패스워드
		'nm' => '',			// 이름
		'pstn' => '',		// 직급
		'dprtSeq' => '',	// 부서코드
		'roleSeq' => '',	// 관리자 역할
		'useYn' => 'Y',		// 사용 여부(근무 여부)
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
