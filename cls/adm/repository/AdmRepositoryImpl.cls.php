<?php
/**
 * admin
 */
namespace cls\adm\repository;

use Exception;
use cls\configuration\Database;
use cls\adm\domain\Adm;
use cls\helper\PagingHelper;

class AdmRepositoryImpl implements AdmRepository {

	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
		if (!$this->db) {
			throw new Exception("Database connection failed.");
		}
	}

	public function findAllUsers($params=[])
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

    $eqCondition = ['irole_seq', 'buseable'];
    foreach ($eqCondition as $field) {
      if (!empty($params[$field])) {
        $condition[] = "{$field}=?";
        $binding_value[] = $params[$field];
      }
    }

    $inCondition = ['idepart_seq', 'cposition'];
    foreach ($inCondition as $field) {
      if (!empty($params[$field]) && is_array($params[$field])) {
        $placeholders = implode(',', array_fill(0, count($params[$field]), '?'));
        $condition[] = "{$field} IN ({$placeholders})";
        $binding_value = array_merge($binding_value, $params[$field]);
      }
    }

    // $likeCondition = ['cid', 'cname'];
    // foreach ($likeCondition as $field) {
    //   if (!empty($params[$field])) {
    //     $condition[] = "{$field} LIKE ?";
    //     $binding_value[] = '%' . $params[$field] . '%';
    //   }
    // }

		// 검색어 조건 (cid, cname)
		if (!empty($params['searchKeyword'])) {
      $condition[] = $params['searchType'] . " LIKE ?";
      $binding_value[] = '%' . $params['searchKeyword'] . '%';
		}

		$whereStr = implode(' AND ', $condition);

		$query = "SELECT * FROM admin A0_1 WHERE {$whereStr} ORDER BY " . $params['sort'] . " " . $params['order'];

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
				$returnValue['rows'][] = new Adm($data);
			}
			$rs->close();
		}

		// count
		$totalItems = 0;
		$query = "SELECT COUNT(*) AS cntTotal FROM admin A0_1 WHERE {$whereStr}";
		$cntRs = $this->db->Execute($query, $binding_value);
		if ($cntRs->fields) {
			$totalItems = intval($cntRs->fields['cntTotal']);
			$cntRs->close();
		}
		$returnValue['total'] = $totalItems;

		return $returnValue;
	}

	public function findUserById($str='')
	{
		$returnValue = null;

		if (empty($str)) {
			return null; // 유효하지 않은 ID
		}
		$query = "SELECT * FROM admin A0_1 WHERE cid = ?";
		$rs = $this->db->Execute($query, [$str]);
		if ($rs === false) {
			throw new Exception("Database query failed: " . $this->db->ErrorMsg());
		}
		if ($rs->EOF) {
			return null; // 게시글이 존재하지 않음
		}
		if ($rs->fields) {
			$returnValue = new Adm($rs->fetchRow());
			$rs->close();
		}
		return $returnValue;
	}

  public function findUserByCd($int=0)
	{
		$returnValue = null;

		if ($int <= 0) {
			return null; // 유효하지 않은 ID
		}
		$query = "SELECT * FROM admin A0_1 WHERE iadmin_seq = ?";
		$rs = $this->db->Execute($query, [$int]);
		if ($rs === false) {
			throw new Exception("Database query failed: " . $this->db->ErrorMsg());
		}
		if ($rs->EOF) {
			return null; // 게시글이 존재하지 않음
		}
		if ($rs->fields) {
			$returnValue = new Adm($rs->fetchRow());
			$rs->close();
		}
		return $returnValue;
	}

	public function saveUser(\cls\adm\domain\Adm $Adm)
	{
    if (empty($Adm)) {
      throw new Exception("Input data is required for saving user.");
    }

    $rsltBool = false;

    // $Adm = new Adm($input);
    if (!empty($Adm->getIadmin_seq())) {
      // Update existing user
      $query = "UPDATE admin SET cid=?, cpassword=?, cname=?, ctel=?, cfax=?, cphone=?, cpost=?, caddr=?, caddr_detail=?, idepart_seq=?, cposition=?, irole_seq=?, buseable=?, dmodify_date=? WHERE iadmin_seq=?";
      $params = [
        $Adm->getCid(),
        $Adm->getCpassword(),
        $Adm->getCname(),
        $Adm->getCtel(),
        $Adm->getCfax(),
        $Adm->getCphone(),
        $Adm->getCpost(),
        $Adm->getCaddr(),
        $Adm->getCaddr_detail(),
        $Adm->getIdepart_seq(),
        $Adm->getCposition(),
        $Adm->getIrole_seq(),
        $Adm->getBuseable(),
        $Adm->getDmodify_date(),
        $Adm->getIadmin_seq()
      ];
    } else {
      // Insert new user
      $query = "INSERT INTO admin (cid, cpassword, cname, ctel, cfax, cphone, cpost, caddr, caddr_detail, idepart_seq, cposition, irole_seq, buseable, dcreate_date, dmodify_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $params = [
        $Adm->getCid(),
        $Adm->getCpassword(),
        $Adm->getCname(),
        $Adm->getCtel(),
        $Adm->getCfax(),
        $Adm->getCphone(),
        $Adm->getCpost(),
        $Adm->getCaddr(),
        $Adm->getCaddr_detail(),
        $Adm->getIdepart_seq(),
        $Adm->getCposition(),
        $Adm->getIrole_seq(),
        $Adm->getBuseable(),
        $Adm->getDcreate_date(),
        $Adm->getDmodify_date()
      ];
    }
    // Execute the query
    // Use try-catch to handle potential exceptions during database operations
    try {
      $this->db->Execute($query, $params);
      $rsltBool = true; // 성공적으로 저장됨
    } catch (Exception $e) {
      throw new Exception("Failed to save user: " . $e->getMessage());
    } finally {
      return $rsltBool; // 반환값은 성공 여부
    }
	}

	public function deleteUserById($int=0)
	{

	}
}
