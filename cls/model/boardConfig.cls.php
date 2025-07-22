<?php
/**
 * board
 */
namespace cls\model;

class boardConfig {
	private $tableName = 'board_mngt';
	private $uniqKeys = array('seq');
	private $row = array(
		'seq' => NULL,			// 
		'nm' => '',			// 게시판명
		'ctgr' => '',			// 게시판 분류
		'replyDepth' => 0,		// 답글 깊이
		'cmntYn' => 'N',			// 댓글 사용여부
		'useYn' => 'Y',			// 유효 여부
		'dtReg' => '0000-00-00 00:00:00',
		'dtMdf' => '0000-00-00 00:00:00'
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
