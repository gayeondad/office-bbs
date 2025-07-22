<?php
/**
 * attendanceList
 */
namespace cls\model;

class attendanceList extends mylist {

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
			elseif (in_array($key, array('admSeq', 'aDate'))) {
				$this->conditionQry[] = $key . "=?";
				$this->bindingArr[] = $value;
			}
			// elseif ($key == 'dtDiv') {
			// 	$this->sortKeys[] = array($value => $this->_get['sortDiv']);	// 정렬 키 항목 (디퐅트 조건 ?)
			// }
		}
	}
	
}
