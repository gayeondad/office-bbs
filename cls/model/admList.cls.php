<?php
/**
 * admList
 */
namespace cls\model;

class admList extends mylist {

	/**
	 * 오버라이딩
	 * @return [type] [description]
	 */
	public function mkCondition()
	{
		foreach ($this->_get as $key => $value) {
			if (in_array($key, array('nm'))) {
				$this->conditionQry[] = $key . " LIKE ?";
				$this->bindingArr[] = "%" . $value . "%";
			}
			elseif (in_array($key, array('id'))) {
				$this->conditionQry[] = $key . "=?";
				$this->bindingArr[] = $value;
			}
			// elseif ($key == 'dtDiv') {
			// 	$this->sortKeys[] = array($value => $this->_get['sortDiv']);	// 정렬 키 항목 (디퐅트 조건 ?)
			// }
		}
	}

	public function mkQry()
	{
		if (!empty($this->conditionQry)) {
			$whereStr = implode(" AND ", $this->conditionQry);
		} else {
			$whereStr = "1=1";
		}

		$this->countQueryStr = "SELECT COUNT(*) AS cnt FROM {$this->tableName} WHERE " . $whereStr;

		$sort = array();
		foreach ($this->sortKeys as $key => $value) {
			foreach ($value as $k => $v) {
				$sort[] = $k . " " . $v;
			}
		}
		if (empty($sort)) {
			$sort = array('dtReg' => 'DESC');	// 정렬 키 항목 (디퐅트 조건 ?)
		}

		$this->queryStr = "SELECT *,
				(SELECT nm FROM dprt WHERE seq=A0_1.dprtSeq) AS dprtNm,
				(SELECT nm FROM adm_role WHERE seq=A0_1.roleSeq) AS roleNm
			FROM {$this->tableName} A0_1 WHERE " . $whereStr . " ORDER BY " . implode(", ", $sort);
	}
	
}
