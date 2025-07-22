<?php
/**
 * 관리자 CRUD 처리
 */
if (empty($_SESSION['admcd'])) {
	session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/autoload.php";
$msg = "";

if (!empty($_POST['a'])) {
	$mdl = new \cls\model\annualLeave();
	$obj = new \cls\controller\bulletin(new \cls\model\dbRunQuery($mdl->getTableName(), $mdl->getUniqKeys(), $mdl->getRow(), false));
	$obj->setRow($_POST);

	if (!empty($_POST['alType'])) {
		$SDate = (empty($_POST['sDt'])) ? date("Y-m-d") : $_POST['sDt'];
		$EDate = (empty($_POST['eDt'])) ? date("Y-m-d") : $_POST['eDt'];
		if (empty($_POST['sDt'])) {
			$obj->setRow(array('sDt' => $SDate));
		}
		if (empty($_POST['eDt'])) {
			$obj->setRow(array('eDt' => $EDate));
		}

		$origin = date_create($SDate);
		$target = date_create($EDate);
		$interval = date_diff($origin, $target);
		// var_dump($interval);
		// echo '<br />d = ' . $interval->d . '<br />';
		$alCnt = (!empty($interval->d)) ? $interval->d + 1 : 1;
		if (in_array($_POST['alType'], array('오전반차', '오후반차'))) {
			$alCnt = 0.5;
		}
		$obj->setRow(array('alCnt' => $alCnt));
	}
	
	switch ($_POST['a']) {
		case 'i':
			// 중복 체크 먼저
			// if ($obj->chkDbl(array('id' => $_POST['id']))) {
			// 	$msg = '{"code": "double", "msg" : "이미 사용중인 아이디입니다."}';
			// 	break;
			// }
			$obj->setRow(array("dtReg" => date("Y-m-d H:i:s")));
			$msg = ($obj->writeRow()) ? '{"code": "success", "msg" : "저장에 성공하였습니다."}' : '{"code": "failure", "msg" : "저장에 실패하였습니다."}';
			break;

		case 'u':
			// $obj->setRow(array("dtMdf" => date("Y-m-d H:i:s")));
			$msg = ($obj->modifyRow()) ? '{"code": "success", "msg" : "수정에 성공하였습니다."}' : '{"code": "failure", "msg" : "수정에 실패하였습니다."}';
			break;
		
		case 'd':
			$msg = ($obj->removeRow()) ? '{"code": "success", "msg" : "삭제에 성공하였습니다."}' : '{"code": "failure", "msg" : "삭제에 실패하였습니다."}';
			break;

		default:
			$msg = '{"code": "exception", "msg" : "미정의된 값 ( ' . $_POST['a'] . ' ) 입니다."}';
			break;
	}
}
else {
	$msg = '{"code": "exception", "msg" : "잘못된 호출 입니다."}';
}
echo $msg;
