<?php
/**
 * bbs
 */
namespace cls\bbs\repository;

use Exception;
use cls\configuration\Database;
use cls\bbs\dto\BbsListDto;
use cls\bbs\domain\Bbs;
use cls\helper\PagingHelper;

class BbsRepositoryImpl implements BbsRepository {

	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
		if (!$this->db) {
			throw new Exception("Database connection failed.");
		}
	}

	public function findAllPosts($params=[])
	{
		$returnValue = ['rows' => [], 'total' => 0];
		$condition = [];
		$binding_value = [];

		// 기본 조건
		$condition[] = "1=1";

		// 날짜 조건
		if (!empty($params['searchDateType'])) {
			switch($params['searchDateType']) {
				case 'dtMdf':
					$findDate = "dmodify_date"; 	// 수정일
					break;
				default:
					$findDate = "dcreate_date"; 	// 등록일
					break;
			}
			if (strtotime($params['startDate']) > strtotime($params['endDate'])) {
				$tmpSDate = $params['startDate'];
				$params['startDate'] = $params['endDate'];
				$params['endDate'] = $tmpSDate;
			}
			$condition[] = $findDate . " BETWEEN ? AND ?";
			$binding_value[] = $params['startDate'] . " 00:00:00";
			$binding_value[] = $params['endDate'] . " 23:59:59";
		}

		// 게시판 종류
		if (!empty($params['iboard_seq'])) {
			$condition[] = "iboard_seq=?";
			$binding_value[] = $params['iboard_seq'];
		}

		// 검색어 조건 (title, content, writeId)
		if (!empty($params['searchKeyword'])) {
			if ($params['searchType'] == 'writer') {
				$condition[] = "cwriter_id IN (SELECT iadmin_seq FROM admin WHERE cname=?)";
				$binding_value[] = '%' . $params['searchKeyword'] . '%';
			} else {
				// $condition[] = $params['searchType'] . " LIKE ?";
				// $binding_value[] = '%' . $params['searchKeyword'] . '%';
				$condition[] = "MATCH (" . $params['searchType'] . ") AGAINST (? IN BOOLEAN MODE)";
				$binding_value[] = '+"' . $params['searchKeyword'] . '"';
			}
		}

		$whereStr = implode(' AND ', $condition);

		$query = "SELECT *, (SELECT cname FROM admin WHERE cid = B0_1.cwriter_id) AS writerName
		FROM board B0_1 WHERE {$whereStr} ORDER BY " . $params['sort'] . " " . $params['order'];

		$offset = ($params['currentPage'] - 1) * $params['itemsPerPage']; // 페이지 번호에 따라 오프셋 계산
    if ($offset < 0) {  // 오프셋이 음수일 경우 0으로 설정
      $offset = 0;
    }
		$rs = $this->db->SelectLimit($query, $params['itemsPerPage'], $offset, $binding_value);
		if ($rs === false) {
			throw new Exception("Database query failed: " . $this->db->ErrorMsg());
		}
		if ($rs->EOF) {
			return $returnValue; // 빈 결과 반환
		}

		if ($rs->fields) {
			while ($data = $rs->FetchRow()) {
				$returnValue['rows'][] = new BbsListDto($data);
			}
			$rs->close();
		}

		// count
		$totalItems = 0;
		$query = "SELECT COUNT(*) AS cntTotal FROM board B0_1 WHERE {$whereStr}";
		$cntRs = $this->db->Execute($query, $binding_value);
		if ($cntRs->fields) {
			$totalItems = intval($cntRs->fields['cntTotal']);
			$cntRs->close();
		}
		$returnValue['total'] = $totalItems;

		return $returnValue;
	}

	public function findPostById($int=0)
	{
		$returnValue = null;

		if ($int <= 0) {
			return null; // 유효하지 않은 ID
		}
		$query = "SELECT * FROM board B0_1 WHERE ipost_seq = ?";
		$rs = $this->db->Execute($query, [$int]);
		if ($rs === false) {
			throw new Exception("Database query failed: " . $this->db->ErrorMsg());
		}
		if ($rs->EOF) {
			return null; // 게시글이 존재하지 않음
		}
		if ($rs->fields) {
			$returnValue = new Bbs($rs->fetchRow());
			$rs->close();
		}
		return $returnValue;
	}

	public function savePost()
	{

	}

	public function destroyPostById($int=0)
	{

	}
}
