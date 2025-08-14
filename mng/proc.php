<?php
/**
 * 관리자 로그인 처리
 */

require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/logConfig.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/cls/common/auth.cls.php";
$boolDebug = false; // 디버그 모드 설정

$response = ['success' => false, 'content' => '', 'message' => ''];

$controller = new \cls\adm\controller\AdmController($boolDebug);
$rslt = $controller->retrieveUserById($_POST['id']);
if ($rslt === null) {
	$response['message'] = '등록되지 않은 관리자입니다.';
	echo json_encode($response, JSON_UNESCAPED_UNICODE);
	exit;
}
$data = $rslt->toArray(); // Assuming Adm has a toArray method to convert to array

if (empty($data)) {
	$response['message'] = '등록되지 않은 관리자입니다.';
	echo json_encode($response, JSON_UNESCAPED_UNICODE);
	exit;
}
$auth = new \cls\common\auth();
if ($auth->verifyPwKey($_POST['pw'], $data['cpassword'])) {
	// 로그인 성공
	// 세션 설정
	session_start();
	$_SESSION['admcd'] = $data['iadmin_seq'];
	$_SESSION['admnm'] = $data['cname'];
	$_SESSION['roleSeq'] = $data['irole_seq'];

	// 출결 등록

	// 아이디 저장 쿠키 설정
	$savedId = isset($_POST['savedId']) ? $_POST['savedId'] : '';
	if ($savedId) {
		setcookie('savedId', $_POST['id'], time() + (86400 * 30), '/'); // 유효기간 : 30일
	} else {
		setcookie('savedId', '', time() - 3600, '/');
	}
	
	$response['success'] = true;
	$response['message'] = '로그인에 성공하였습니다.';
} else {
	$response['message'] = '비밀번호가 일치하지 않습니다.';
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);


// // 출결 등록
// $obj_atdnc = new \cls\model\attendance();
// $atdnc = new \cls\controller\bulletin(new \cls\model\dbRunQuery($obj_atdnc->getTableName(), $obj_atdnc->getUniqKeys(), $obj_atdnc->getRow(), false));

// if (!$atdnc->chkDbl(array('admSeq' => $data['seq'], 'aDate' => date("Y-m-d")))) {
// 	$atdnc->setRow(array('admSeq' => $data['seq'], 'aDate' => date("Y-m-d"), 'inTime' => date("H:i:s")));
// 	if ($atdnc->writeRow()) {
// 		// 출근처리 완료
// 	}
// }
