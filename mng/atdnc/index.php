<?php
/**
 * 관리자 컨트롤
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/commonInclude.php";

$dirNm = "atdnc";
$tblNm = "attendance";
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

$codeTbl = new \cls\controller\codeTbl(new \cls\model\mkCode(false));
$dprtAdm = $codeTbl->mkDprtAdm();





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

	

	// 부서-사원 배열
	$codeTbl = new \cls\controller\codeTbl(new \cls\model\mkCode(false));
	$dprtAdm = $codeTbl->mkDprtAdm();

	// var_dump($dprtAdm);

	$dprt = array();	// 부서 배열

	if (!empty($dprtAdm)) {
		foreach ($dprtAdm as $key => $value) {
			$dprt[] = array('nm' => $key, 'span' => count($value) * 2, 'adm' => $value);	// span : rowspan 2열 때문에 * 2 배 적용
		}
	}

	// 해당월의 마지막 일
	$year = (!empty($_GET['year'])) ? $_GET['year'] : date('Y');
	$month = (!empty($_GET['month'])) ? $_GET['month'] : date('m');
	$date = new DateTime("{$year}-{$month}-01");
	$date->modify('last day of this month');
	$lastDateNum = $date->format('d');

	// var_dump($dprt);

	// list form
	if (empty($_GET['currentPage'])) $_GET['currentPage'] = 1;
	if (empty($_GET['rowPerPage'])) $_GET['rowPerPage'] = 1000;		// limit 가 의미없는 조회
	if (empty($_GET['dtDiv'])) $_GET['dtDiv'] = 'admSeq';	// 기본 정렬 : dtReg
	if (empty($_GET['sortDiv'])) $_GET['sortDiv'] = 'ASC';
	$sortArr = array();
	$sortArr[] = array($_GET['dtDiv'] => $_GET['sortDiv'], 'aDate' => 'ASC');

	$mdl = new \cls\model\attendanceList($_GET, $sortArr, $tblNm);
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

	var_dump($rows);

	// 템플릿 렌더링 및 출력
	echo $twig->render("mng/{$dirNm}/list.html", ['dprt' => $dprt, 'rows' => $rows, 'year' => $year, 'month' => $month, 'lastDay' => intval($lastDateNum)]);
}
else {
	$logger->info('view and edit form');

	// view and edit from
	$mdl = new \cls\model\attendance();
	$obj = new \cls\controller\bulletin(new \cls\model\dbRunQuery($mdl->getTableName(), $mdl->getUniqKeys(), $mdl->getRow(), false));
	$obj->setRow($_GET);
	$obj->fetchRow();
	$data = $obj->getRow();

	$template = ($_GET['g'] == 'e') ? "mng/{$dirNm}/edit.html" : "mng/{$dirNm}/view.html";
	// 템플릿 렌더링 및 출력
	echo $twig->render($template);
}
