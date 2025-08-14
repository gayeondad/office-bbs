<?php
/**
 * 관리자 컨트롤
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/commonInclude.php";
$boolDebug = true; // 디버그 모드 설정

$dirNm = "adm";
$tblNm = "adm";
if (empty($_GET['g'])) $_GET['g'] = 'l';

$options_dprt = ['1' => '경영팀', '2' => '고객팀', '3' => 'IT팀'];

$options_pstn = ['10' => '사원', '20' => '대리', '30' => '과장', '40' => '차장', '50' => '부장', '90' => '사장'];

$options_role = ['1' => '게스트', '2' => '일반관리자', '9' => '슈퍼관리자'];


if ($_GET['g'] == 'w') {
	$logger->info('write form');
	// write form
	// 템플릿 렌더링 및 출력
	echo $twig->render("mng/{$dirNm}/write.html", ['options_dprt' => $options_dprt, 'options_pstn' => $options_pstn, 'options_role' =>$options_role]);
}
elseif ($_GET['g'] == 'l') {
	$logger->info('list form');
	$logger->warning('list form warning');

	// var_dump($_GET); // 디버그용

	$controller = new \cls\adm\controller\AdmController($boolDebug);
	$rslt = $controller->retrieveAllUsers();
	// var_dump($rslt); // 디버그용
	// var_dump($_GET); // 디버그용
	
	// 템플릿 렌더링 및 출력
	echo $twig->render("mng/{$dirNm}/list.html", ['rows' => $rslt['rows'], 'pages' => [$rslt['pages']], 'totalCnt' => $rslt['total'], '_get' => $_GET, 'options_dprt' => $options_dprt, 'options_pstn' => $options_pstn, 'options_role' => $options_role]);
}
else {
	$logger->info('view and edit form');

	// view and edit from
	$mdl = new \cls\model\adm();
	$obj = new \cls\controller\bulletin(new \cls\model\dbRunQuery($mdl->getTableName(), $mdl->getUniqKeys(), $mdl->getRow(), false));
	$obj->setRow($_GET);
	$obj->fetchRow();
	$data = $obj->getRow();

	$template = ($_GET['g'] == 'e') ? "mng/{$dirNm}/edit.html" : "mng/{$dirNm}/view.html";
	// 템플릿 렌더링 및 출력
	echo $twig->render($template, ['data' => $data, 'options_dprt' => $options_dprt, 'options_pstn' => $options_pstn, 'options_role' =>$options_role]);
}
