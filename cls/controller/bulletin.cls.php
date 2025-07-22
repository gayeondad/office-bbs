<?php
/**
 * bulletin
 */
namespace cls\controller;
// use Exception;

class bulletin {
	// use cls\util;
	// use cls\dbcon;

	private \cls\model\runQuery $runQuery;

	public function __construct(\cls\model\runQuery $runQuery)
	{
		$this->runQuery = $runQuery;
	}

	function __destruct()
	{

	}

	public function writeRow()
	{
		return $this->runQuery->insQry();
	}

	public function modifyRow()
	{
		return $this->runQuery->updQry();
	}

	public function removeRow()
	{
		return $this->runQuery->delQry();
	}

	public function fetchRow($arr=array())
	{
		return $this->runQuery->selQry($arr);
	}

	public function setRow($arr=array())
	{
		$this->runQuery->setRow($arr);
	}

	public function getRow()
	{
		return $this->runQuery->getRow();
	}

	public function chkDbl($arr=array())
	{
		return $this->runQuery->chkDbl($arr);
	}

	
}
