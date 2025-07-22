<?php
/**
 * board
 */
namespace cls\model;

class mkCode {
	use dbCon;
	use \cls\common\util;
	

	public function __construct($bool=false)
	{
		$this->opnDb($bool);
	}

	function __destruct()
	{

	}

	/**
	 * 관리자 작성에서 역할명 선택 풀다운 메뉴 사용 목적
	 * @return [type] [description]
	 */
	public function mkAdmRole($seq=0)
	{
		$row = array();
		if (!empty($seq)) {
			$query = "SELECT right_user, right_settle, right_access FROM adm_role WHERE seq=?";
			$stmt = $this->db->Prepare($query);
			$rs = $this->db->Execute($stmt, array($seq));
			// $rs = $this->db->execute($query);
			if ($rs->fields) {
				$row = $rs->fetchrow();
			}
		}
		else {
			$query = "SELECT seq, nm FROM adm_role ORDER BY seq";
			$stmt = $this->db->Prepare($query);
			$rs = $this->db->Execute($stmt);
			// $rs = $this->db->execute($query);
			if ($rs->fields) {
				$rows = array();
				while ($data = $rs->fetchrow()) {
					$rows[] = array("value" => $data['seq'], "label" => $data['nm']);
				}
				$row = $rows;
			}
		}
		return $row;
	}

	/**
	 * 관리자 출결 목록에서 사용할 부서-사원 배열 반환
	 * @return [type] [description]
	 */
	public function mkDprtAdm()
	{
		$rows = array();
		$query = "SELECT (SELECT nm FROM dprt WHERE seq=A0_1.dprtSeq) AS dprtNm, nm, seq FROM adm A0_1 WHERE useYn='Y' ORDER BY dprtSeq, nm";
		$stmt = $this->db->Prepare($query);
		$rs = $this->db->Execute($stmt);
		if ($rs->fields) {
			while ($data = $rs->FetchRow()) {
				$rows[$data['dprtNm']][] = array('nm' => $data['nm'], 'admSeq' => $data['seq']);
			}
		}
		return $rows;
	}



}
