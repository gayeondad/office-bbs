<?php
/**
 * annualLeaveList
 */
namespace cls\model;

class annualLeaveList extends mylist {

	/**
	 * 오버라이딩
	 * @return [type] [description]
	 */
	public function mkCondition()
	{
		foreach ($this->_get as $key => $value) {
			if (in_array($key, array('note'))) {
				$this->conditionQry[] = $key . " LIKE ?";
				$this->bindingArr[] = "%" . $value . "%";
			}
			elseif (in_array($key, array('alType', 'sDt', 'eDt', 'admSeq'))) {
				$this->conditionQry[] = $key . "=?";
				$this->bindingArr[] = $value;
			}
			// elseif ($key == 'dtDiv') {
			// 	$this->sortKeys[] = array($value => $this->_get['sortDiv']);	// 정렬 키 항목 (디퐅트 조건 ?)
			// }
		}
	}

}
