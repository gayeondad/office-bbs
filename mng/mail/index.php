<?php
/**
 * 관리자 컨트롤
 */
// header('Content-Type: text/html; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/commonInclude.php";
/*
$mailer = new \cls\controller\mailer();
$mailer->setFromAddress("creloper@naver.com");
$mailer->setFromName("데브");
$mailer->setSubject("테스트 메일2");

$mailer->sendMail();

exit;
*/
$dirNm = "mail";
$tblNm = "mail";
if (empty($_GET['g'])) $_GET['g'] = 'l';

if ($_GET['g'] == 'w') {
	$logger->info('write form');
	// write form
	// 템플릿 렌더링 및 출력
	echo $twig->render("mng/{$dirNm}/write.html");
}
elseif ($_GET['g'] == 'l') {
	$logger->info('list form');
	$logger->warning('list form warning');
	
	$rows = array();
	$pages = array();

	// list form
	if (empty($_GET['currentPage'])) $_GET['currentPage'] = 1;
	if (empty($_GET['rowPerPage'])) $_GET['rowPerPage'] = 10;
	if (empty($_GET['dtDiv'])) $_GET['dtDiv'] = 'dtReg';	// 기본 정렬 : dtReg
	if (empty($_GET['sortDiv'])) $_GET['sortDiv'] = 'DESC';
	$sortArr = array();
	$sortArr[] = array($_GET['dtDiv'] => $_GET['sortDiv']);

	$mdl = new \cls\model\mailList($_GET, $sortArr, $tblNm);
	$list = new \cls\controller\bulletinList(new \cls\model\dbListQuery($mdl->getQueryStr(), $mdl->getCountQueryStr(), $mdl->getBindingArr(), true));

	$list->setCurrentPage($_GET['currentPage']);
	$list->setRowPerPage($_GET['rowPerPage']);

	if (!$list->listQry()) {
		echo '조회할 목록이 없습니다.';
	}
	else {
		$rows = $list->getRows();
		$pages = $list->getPages();
	}

	// 템플릿 렌더링 및 출력
	echo $twig->render("mng/{$dirNm}/list.html", ['rows' => $rows, 'pages' => array($pages)]);
}
else {
	$logger->info('view and edit form');

	// view and edit from
	$mdl = new \cls\model\mail();
	$obj = new \cls\controller\bulletin(new \cls\model\dbRunQuery($mdl->getTableName(), $mdl->getUniqKeys(), $mdl->getRow(), false));
	$obj->setRow($_GET);
	$obj->fetchRow();
	$data = $obj->getRow();

	$template = ($_GET['g'] == 'e') ? "mng/{$dirNm}/edit.html" : "mng/{$dirNm}/view.html";
	// 템플릿 렌더링 및 출력
	echo $twig->render($template, ['data' => $data, 'options_dprt' => $options_dprt, 'options_pstn' => $options_pstn, 'options_role' =>$options_role]);
}
