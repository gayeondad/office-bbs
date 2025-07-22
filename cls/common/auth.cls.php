<?php
/**
 * 
 */
namespace cls\common;

class auth
{
	private $salt;

	public function __construct()
	{
		$this->salt = 'secret_salt';	// 랜덤 값
	}

	function __destruct()
	{

	}

	public function generatePwKey($pwd='')
	{
		// $combinedData = $pwd . $this->salt;
		return password_hash($pwd, PASSWORD_DEFAULT);
	}

	public function verifyPwKey($pwd='', $hashKey='')
	{
		// $generatedKey = $this->generateAuthKey($pwd);
		return password_verify($pwd, $hashKey);
	}

}
