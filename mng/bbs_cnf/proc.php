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
	$mdl = new \cls\model\boardConfig();
	$obj = new \cls\controller\bulletin(new \cls\model\dbRunQuery($mdl->getTableName(), $mdl->getUniqKeys(), $mdl->getRow(), false));
	$obj->setRow($_POST);
	
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
			$obj->setRow(array("dtMdf" => date("Y-m-d H:i:s")));
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
