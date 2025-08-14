<?php
/**
 * 
 */
namespace cls\common;

class auth
{
	private $options = [
		'cost' => 12, // 해싱 비용, 4~31 사이의 정수이며, 기본값은 10입니다.
		// 'salt' => null // salt 값은 생성 시 자동으로 생성됨
	];

	public function __construct()
	{
		// salt 값을 생성
		// $this->options['salt'] = bin2hex(random_bytes(16)); // 16바이트의 랜덤한 salt 생성 > BCRYPT 알고리즘은 솔트(salt)를 자동으로 생성하고 해시값에 포함합니다.
	}

	function __destruct()
	{

	}

	public function generatePwKey($pwd='')
	{
		if (empty($pwd)) {
			throw new \InvalidArgumentException("Password cannot be empty.");
		}
		return password_hash($pwd, PASSWORD_BCRYPT, $this->options);
	}

	public function verifyPwKey($pwd='', $hashKey='')
	{
		if (empty($pwd) || empty($hashKey)) {
			throw new \InvalidArgumentException("Password or hash key cannot be empty.");
		}
		return password_verify($pwd, $hashKey);
	}

}
