<?php
/**
 * mail
 */
namespace cls\model;

class mail {
	private $tableName = 'mail';
	private $uniqKeys = array('seq');
	private $row = array(
		'seq' => NULL,						// 
		'fromEmail' => '',					// 발신자 이메일주소
		'fromName' => '',					// 발신자명
		'toEmail' => '',					// 수신자 이메일주소
		'toName' => '',						// 수신자명
		'subject' => '',					// 제목
		'content' => NULL,					// 내용(body)
		'msgType' => 'html',				// content-type
		'dtReg' => '0000-00-00 00:00:00',
		'dtMdf' => '0000-00-00 00:00:00',
		'statusSend' => ''					// 발송상태
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
