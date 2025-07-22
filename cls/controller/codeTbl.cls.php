<?php
/**
 * bulletin
 */
namespace cls\controller;
// use Exception;

class codeTbl {
	// use cls\util;
	// use cls\dbcon;

	private $codeObj;

	public function __construct(\cls\model\mkCode $mkCode)
	{
		$this->codeObj = $mkCode;
	}

	function __destruct()
	{

	}

	public function mkAdmRole($seq='')
	{
		return $this->codeObj->mkAdmRole($seq);
	}

	public function mkDprtAdm()
	{
		return $this->codeObj->mkDprtAdm();
	}


	
}
