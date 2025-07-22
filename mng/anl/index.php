<?php
/**
 * 관리자 컨트롤
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/commonInclude.php";

$dirNm = "anl";
$tblNm = "annual_leave";
if (empty($_GET['g'])) $_GET['g'] = 'l';

$options_leave = array(
	array('value' => '1', 'label' => '연차'),
	array('value' => '2', 'label' => '오전반차'),
	array('value' => '3', 'label' => '오후반차'),
	array('value' => '4', 'label' => '경조'),
	array('value' => '5', 'label' => '기타')
);

// $options_pstn = array(
// 	array('value' => '10', 'label' => '사원'),
// 	array('value' => '20', 'label' => '대리'),
// 	array('value' => '30', 'label' => '과장')
// );

// $codeTbl = new \cls\controller\codeTbl(new \cls\model\mkCode(false));
// $options_role = $codeTbl->mkAdmRole();



if ($_GET['g'] == 'w') {
	$logger->info('write form');
	// write form
	// 템플릿 렌더링 및 출력
	echo $twig->render("mng/{$dirNm}/write.html", ['admSeq' => $_GET['admSeq'], 'options_leave' => $options_leave]);
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

	$mdl = new \cls\model\annualLeaveList($_GET, $sortArr, $tblNm);
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
	$mdl = new \cls\model\annualLeave();
	$obj = new \cls\controller\bulletin(new \cls\model\dbRunQuery($mdl->getTableName(), $mdl->getUniqKeys(), $mdl->getRow(), false));
	$obj->setRow($_GET);
	$obj->fetchRow();
	$data = $obj->getRow();

	$template = ($_GET['g'] == 'e') ? "mng/{$dirNm}/edit.html" : "mng/{$dirNm}/view.html";
	// 템플릿 렌더링 및 출력
	echo $twig->render($template, ['data' => $data, 'options_dprt' => $options_dprt, 'options_pstn' => $options_pstn, 'options_role' =>$options_role]);
}
