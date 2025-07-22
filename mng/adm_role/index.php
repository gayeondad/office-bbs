<?php
/**
 * 관리자 컨트롤
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/commonInclude.php";

$dirNm = "adm_role";
$tblNm = "adm_role";
if (empty($_GET['g'])) $_GET['g'] = 'l';

// $options_dprt = array(
// 	array('value' => '1', 'label' => '경영팀'),
// 	array('value' => '2', 'label' => '고객팀'),
// 	array('value' => '3', 'label' => 'IT팀')
// );

// $options_pstn = array(
// 	array('value' => '10', 'label' => '사원'),
// 	array('value' => '20', 'label' => '대리'),
// 	array('value' => '30', 'label' => '과장')
// );

// $options_role = array(
// 	array('value' => '1', 'label' => '고급관리자'),
// 	array('value' => '2', 'label' => '중급관리자'),
// 	array('value' => '3', 'label' => '초급관리자')
// );



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

	$mdl = new \cls\model\admRoleList($_GET, $sortArr, $tblNm);
	$list = new \cls\controller\bulletinList(new \cls\model\dbListQuery($mdl->getQueryStr(), $mdl->getCountQueryStr(), $mdl->getBindingArr(), false));

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
	$mdl = new \cls\model\admRole();
	$obj = new \cls\controller\bulletin(new \cls\model\dbRunQuery($mdl->getTableName(), $mdl->getUniqKeys(), $mdl->getRow(), false));
	$obj->setRow($_GET);
	$obj->fetchRow();
	$data = $obj->getRow();

	$template = ($_GET['g'] == 'e') ? "mng/{$dirNm}/edit.html" : "mng/{$dirNm}/view.html";
	// 템플릿 렌더링 및 출력
	echo $twig->render($template, ['data' => $data]);
}
