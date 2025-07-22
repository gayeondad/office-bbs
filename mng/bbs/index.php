<?php
/**
 * 관리자 컨트롤
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/commonInclude.php";

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
} elseif ($_GET['g'] == 'e') {
	$logger->info('edit form');
	// edit form
	// 게시글 ID가 있는 경우 해당 게시글을 조회
	if (isset($_GET['postId']) && is_numeric($_GET['postId'])) {
		$bbsService = new \cls\bbs\service\BbsServiceImpl(true);
		$post = $bbsService->readPostById((int)$_GET['postId']);
		if ($post === null) {
			throw new \Exception("Post not found.");
		}
		echo $twig->render("mng/{$dirNm}/edit.html", ['post' => $post, 'boardSeq' => $_GET['boardSeq']]);
	} else {
		throw new \InvalidArgumentException("Invalid post ID.");
	}
} elseif ($_GET['g'] == 'l') {
	$logger->info('list form');
	$logger->warning('list form warning');

	$rows = array();
	$pages = array();

	$controller = new \cls\bbs\controller\BbsController(true);
	$rslt = $controller->retrieveAllPosts();
	// var_dump($rslt);

	// // list form
	if (empty($_GET['currentPage'])) $_GET['currentPage'] = 1;

	$getKeyValue = mkParam($_GET);
	
	// 템플릿 렌더링 및 출력
	echo $twig->render("mng/{$dirNm}/list.html", ['rows' => $rslt['rows'], 'pages' => array($pages), 'getKeyValue' => $getKeyValue, 'currentPage' => $_GET['currentPage']]);
} else {
	$logger->info('view and edit form');

	$controller = new \cls\bbs\controller\BbsController(true);
	$rslt = $controller->retrievePostById((int)$_GET['seq']);
	if ($rslt === null) {
		throw new \Exception("Post not found.");
	}
	$data = $rslt['row'];
	if (empty($data)) {
		throw new \Exception("No data found for the given post ID.");
	}

	$template = ($_GET['g'] == 'e') ? "mng/{$dirNm}/edit.html" : "mng/{$dirNm}/view.html";
	// 템플릿 렌더링 및 출력
	echo $twig->render($template, ['data' => $data, 'boardSeq' => 1]);
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