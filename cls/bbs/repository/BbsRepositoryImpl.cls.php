<?php
/**
 * bbs
 */
namespace cls\bbs\repository;

use Exception;
use cls\configuration\Database;
use cls\bbs\dto\BbsListDto;

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
		$returnValue = ['rows' => [], 'cnt' => []];

		$condition = [];
		$binding_value = [];

		// 기본 조건
		$condition[] = "1=1";

		// 날짜 조건
		if (!empty($params['searchDateType'])) {
			switch($params['searchDateType']) {
				case 'mdf':
					$findDate = "dtMdf"; 	// 수정일
					break;
				default:
					$findDate = "dtReg"; 	// 등록일
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
		if (!empty($params['boardSeq'])) {
			$condition[] = "boardSeq=?";
			$binding_value[] = $params['boardSeq'];
		}

		// 검색어 조건 (title, content, writeId)
		if (!empty($params['searchKeyword'])) {
			if ($params['searchType'] == 'writeId') {
				$condition[] = "writeId IN (SELECT id FROM user WHERE name=?)";
				$binding_value[] = '%' . $params['searchKeyword'] . '%';
			} else {
				// $condition[] = $params['searchType'] . " LIKE ?";
				// $binding_value[] = '%' . $params['searchKeyword'] . '%';
				$condition[] = "MATCH (" . $params['searchType'] . ") AGAINST (? IN BOOLEAN MODE)";
				$binding_value[] = '+"' . $params['searchKeyword'] . '"';
			}
		}

		$whereStr = implode(' AND ', $condition);

		$query = "SELECT *, (SELECT nm FROM adm WHERE id = B0_1.writerId) AS writerName
		FROM board B0_1 WHERE {$whereStr} ORDER BY " . $params['sort'] . " " . $params['order'];

		// $num = ($params['currentPage'] - 1) * $params['limit'];
		$rs = $this->db->SelectLimit($query, $params['limit'], $params['offset'], $binding_value);
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
		$query = "SELECT COUNT(*) AS cntTotal FROM board B0_1 WHERE {$whereStr}";
		$cntRs = $this->db->Execute($query, $binding_value);
		if ($cntRs->fields) {
			$returnValue['cnt']['totalRows'] = intval($cntRs->fields['cntTotal']);
			$cntRs->close();
		}

		return $returnValue;
	}

	public function findPostById($int=0)
	{
		$returnValue = ['row' => []];
		
		if ($int <= 0) {
			return null; // 유효하지 않은 ID
		}
		$query = "SELECT *, (SELECT nm FROM adm WHERE id = B0_1.writerId) AS writerName FROM board B0_1 WHERE seq = ?";
		$rs = $this->db->Execute($query, [$int]);
		if ($rs === false) {
			throw new Exception("Database query failed: " . $this->db->ErrorMsg());
		}
		if ($rs->EOF) {
			return null; // 게시글이 존재하지 않음
		}
		if ($rs->fields) {
			$returnValue['row'] = new BbsListDto($rs->fetchRow());
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
