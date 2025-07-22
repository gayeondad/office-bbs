<?php
/**
 * logout page
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/commonInclude.php";
// var_dump($_SESSION);
if (!empty($_SESSION['admcd'])) {

	// 출결 등록
	$obj_atdnc = new \cls\model\attendance();
	$atdnc = new \cls\controller\bulletin(new \cls\model\dbRunQuery($obj_atdnc->getTableName(), $obj_atdnc->getUniqKeys(), $obj_atdnc->getRow(), true));
	$atdnc->setRow(array('admSeq' => $_SESSION['admcd'], 'aDate' => date("Y-m-d")));
	
	if ($atdnc->fetchRow()) {
		$atdnc->setRow(array('outTime' => date("H:i:s")));
		echo date("Y-m-d H:i:s");
		echo date("H:i:s");
		if ($atdnc->modifyRow()) {
			// 퇴근처리 완료
		}
	}

	$timezone = date_default_timezone_get();
	// echo "현재 시간대: " . $timezone;

	unset($_SESSION['admcd']);
	unset($_SESSION['admnm']);
	unset($_SESSION['roleSeq']);

	session_destroy();
	// exit;
	// header('Location: login.php'); // 로그인 페이지로 이동
	echo "<script>alert('logout');parent.location.href='/mng/index.php';</script>";
	exit;
}
