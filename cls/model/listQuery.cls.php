<?php
/**
 * Interface
 */
namespace cls\model;

interface listQuery {
	public function setCurrentPage($currentPage=1);
	public function setRowPerPage($rowPerPage=10);
	public function getRows();
	public function getPages();
	public function listQry();
}
