<?php
/**
 * 관리자 로그인 처리
 */
if (empty($_SESSION['admcd'])) {
	session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/autoload.php";

$msg = "";

if (!empty($_POST['id'])) {
	
	
	$obj = new \cls\model\adm();
	$adm = new \cls\controller\bulletin(new \cls\model\dbRunQuery($obj->getTableName(), $obj->getUniqKeys(), $obj->getRow(), false));
	$adm->setRow($_POST);
	$adm->fetchRow(array('id'));

	$data = $adm->getRow();
	if (empty($data)) {
		$msg = '{"code": "exception", "msg": "등록되지 않은 관리자입니다."}';
	}
	else {
		$auth = new \cls\common\auth();
		if ($auth->verifyPwKey($_POST['pw'], $data['pw'])) {
			// 로그인 성공
			// 세션 설정
			$_SESSION['admcd'] = $data['seq'];
			$_SESSION['admnm'] = $data['nm'];
			$_SESSION['roleSeq'] = $data['roleSeq'];

			
			// 출결 등록
			$obj_atdnc = new \cls\model\attendance();
			$atdnc = new \cls\controller\bulletin(new \cls\model\dbRunQuery($obj_atdnc->getTableName(), $obj_atdnc->getUniqKeys(), $obj_atdnc->getRow(), false));
			
			if (!$atdnc->chkDbl(array('admSeq' => $data['seq'], 'aDate' => date("Y-m-d")))) {
				$atdnc->setRow(array('admSeq' => $data['seq'], 'aDate' => date("Y-m-d"), 'inTime' => date("H:i:s")));
				if ($atdnc->writeRow()) {
					// 출근처리 완료
				}
			}

			// 아이디 저장 쿠키 설정
			$savedId = isset($_POST['savedId']) ? $_POST['savedId'] : '';
			if ($savedId) {
				setcookie('savedId', $_POST['id'], time() + (86400 * 30), '/');		// 유효기간 : 30일
			}
			else {
				setcookie('savedId', '', time() - 3600, '/');
			}
			$msg = '{"code": "success", "msg": "로그인에 성공하였습니다."}';
		}
		else {
			$msg = '{"code": "failure", "msg": "비번이 일치하지 않습니다."}';
		}
	}
}
else {
	$msg = '{"code": "exception", "msg": "잘못된 호출 입니다."}';
}
echo $msg;
