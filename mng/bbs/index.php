<?php
/**
 * 관리자 컨트롤
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/commonInclude.php";
$boolDebug = true; // 디버그 모드 설정

$dirNm = "bbs";
$tblNm = "board";
if (empty($_GET['g'])) $_GET['g'] = 'l';
if (empty($_GET['boardSeq'])) $_GET['boardSeq'] = 1;

$logger->info("관리자 컨트롤 시작", ['dirNm' => $dirNm, 'tblNm' => $tblNm, 'g' => $_GET['g'], 'boardSeq' => $_GET['boardSeq']]);

if ($_GET['g'] == 'w') {
	$logger->info('write form');
	// write form
	
	// 템플릿 렌더링 및 출력
	echo $twig->render("mng/{$dirNm}/write.html", ['boardSeq' => $_GET['boardSeq']]);
} elseif ($_GET['g'] == 'v' || $_GET['g'] == 'e') {
	$logger->info('view and edit form');

	if (empty($_GET['ipost_seq']) || !is_numeric($_GET['ipost_seq'])) {
		throw new \InvalidArgumentException("Invalid post ID.");
	}

	$controller = new \cls\bbs\controller\BbsController($boolDebug);
	$rslt = $controller->retrievePostById((int)$_GET['ipost_seq']);
	if ($rslt === null) {
		throw new \Exception("Post not found.");
	}
	$data = $rslt->toArray(); // Assuming Bbs has a toArray method to convert to array	

	if (empty($data)) {
		throw new \Exception("No data found for the given post ID.");
	}

	$template = ($_GET['g'] == 'e') ? "mng/{$dirNm}/edit.html" : "mng/{$dirNm}/view.html";
	
	// 템플릿 렌더링 및 출력
	echo $twig->render($template, ['data' => $data, 'boardSeq' => $_GET['boardSeq']]);
} else {
	$logger->info('list form');
	$logger->warning('list form warning', $_GET);

	$controller = new \cls\bbs\controller\BbsController($boolDebug);
	$rslt = $controller->retrieveAllPosts();

	// if (empty($_GET['currentPage'])) $_GET['currentPage'] = 1;

	// $getKeyValue = mkParam($_GET);
	var_dump($_GET); // 디버그용	
	// 템플릿 렌더링 및 출력
	echo $twig->render("mng/{$dirNm}/list.html", ['rows' => $rslt['rows'], 'pages' => [$rslt['pages']], 'totalCnt' => $rslt['total'], '_get' => $_GET]);
}


function mkParam($getParam=array())
{
	$getKeyValue = array();
	foreach ($getParam as $key => $value) {
		if (!in_array($key, array("currentPage", "g"))) continue;
		if (is_array($value)) {
			foreach ($value as $v) {
				$getKeyValue[] = urlencode($key . '[]') . '=' . urlencode($v);
			}
		}
		else {
			$getKeyValue[] = urlencode($key) . '=' . urlencode($value);
		}
	}
	if (!empty($getKeyValue)) {
		return implode("&", $getKeyValue);
	}
	return '';
}