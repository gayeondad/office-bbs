<?php
/**
 * 기본형 쿼리
 */
namespace cls\model;

class dbListQuery implements listQuery {
	use dbCon;
	use \cls\common\util;

	private $queryStr = "";
	private $countQueryStr = "";
	private $bindingArr = array();

	private $rows = array();		// 화면 노출 게시물 배열
	// private $countTotal = 0;		// 전체 게새물 수
	private $pages = array();		// 화면 노출 페이지 구성 배열

	private $rowPerPage = 10;		// 페이지당 게시물 수
	private $currentPage = 1;		// 현재 페이지 번호


	public function __construct($queryStr, $countQueryStr, $bindingArr, $bool=false)
	{
		$this->opnDb($bool);

		$this->queryStr = $queryStr;
		$this->countQueryStr = $countQueryStr;
		$this->bindingArr = $bindingArr;
	}

	function __destruct()
	{

	}

	public function setCurrentPage($currentPage='1')
	{
		$this->currentPage = intval($currentPage);
	}

	public function setRowPerPage($rowPerPage='10')
	{
		$this->rowPerPage = intval($rowPerPage);
	}

	public function getRows()
	{
		return $this->rows;
	}

	public function getPages()
	{
		return $this->pages;
	}

	/**
	 * 게시물 목록 추출
	 * @return [type] [description]
	 */
	public function listQry()
	{
		$offset = ($this->currentPage - 1) * $this->rowPerPage;
		if (!empty($this->bindingArr)) {
			$rs = $this->db->SelectLimit($this->queryStr, $this->rowPerPage, ($this->currentPage - 1) * $this->rowPerPage, $this->bindingArr);
		} else {
			$rs = $this->db->SelectLimit($this->queryStr, $this->rowPerPage, ($this->currentPage - 1) * $this->rowPerPage);
		}

		if (!empty($rs)) {
			if ($rs->fields) {
				while ($data = $rs->fetchrow()) {
					$data['num'] = 1 + $offset++;
					$this->rows[] = $data;
				}
				$rs->close();
				// for count
				if (!empty($this->bindingArr)) {
					$cntRs = $this->db->execute($this->countQueryStr, $this->bindingArr);
				} else {
					$cntRs = $this->db->execute($this->countQueryStr);
				}

				if (!empty($cntRs)) {
					if ($cntRs->fields) {
						$this->pages = $this->paging($this->currentPage, $this->rowPerPage, $cntRs->fields['cnt']);
					}
				}

				return true;
			}
		}
		return false;
	}

}