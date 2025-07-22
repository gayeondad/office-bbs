<?php
/**
 * board
 */
namespace cls\model;

class board {
	private $tableName = 'board';
	private $uniqKeys = array('seq');
	private $row = array(
		'seq' => NULL,				// 
		'boardSeq' => 0,			// 게시판 코드
		'title' => '',				// 제목
		'content' => '',			// 내용
		'writerId' => '',			// 작성자 아이디
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
