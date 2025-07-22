<?php
/**
 * board
 */
namespace cls\controller;
// use Exception;

class bulletinList {
	// use cls\util;
	// use cls\dbcon;

	private \cls\model\listQuery $listQuery;

	public function __construct(\cls\model\listQuery $listQuery)
	{
		$this->listQuery = $listQuery;
	}

	function __destruct()
	{

	}

	public function setCurrentPage($currentPage=1)
	{
		$this->listQuery->setCurrentPage($currentPage);
	}

	public function setRowPerPage($rowPerPage=10)
	{
		$this->listQuery->setRowPerPage($rowPerPage);
	}

	public function getRows()
	{
		return $this->listQuery->getRows();
	}

	public function getPages()
	{
		return $this->listQuery->getPages();
	}

	public function listQry()
	{
		return $this->listQuery->listQry();
	}

	
}
