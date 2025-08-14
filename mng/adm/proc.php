<?php
/**
 * 관리자 CRUD 처리
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/commonInclude.php";
$boolDebug = false; // 디버그 모드 설정
$response = ['success' => false, 'content' => '', 'message' => ''];

$controller = new \cls\adm\controller\AdmController($boolDebug);

if (!empty($_POST['p'])) {
	switch ($_POST['p']) {
		case 'w':
			$rslt = $controller->createUser($_POST);
			if ($rslt) {
				$response['success'] = true;
				$response['message'] = '관리자 등록 성공';
			} else {
				$response['message'] = '관리자 등록 실패';
			}
			break;

		case 'u':
			$rslt = $controller->updateUser($_POST);
			if ($rslt) {
				$response['success'] = true;
				$response['message'] = '관리자 수정 성공';
			} else {
				$response['message'] = '관리자 수정 실패';
			}
			break;
		
		case 'd':
			$rslt = $controller->deleteUser($_POST);
			if ($rslt) {
				$response['success'] = true;
				$response['message'] = '관리자 삭제 성공';
			} else {
				$response['message'] = '관리자 삭제 실패';
			}
			break;

		default:
			$response['message'] = '미정의된 값';
			break;
	}
}
else {
	$response['message'] = '잘못된 호출 입니다.';
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
