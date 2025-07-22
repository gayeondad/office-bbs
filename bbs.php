<?php
/**
 * 게시판 목록
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/autoload.php";

if (empty($_GET['g'])) $_GET['g'] = 'l';

// $row = array('boardSeq' => 1, 'title' => 'Learn Spring Boot', 'content' => 'Learn Java language with Spring-framework', 'writerId' => 'developer', 'dtReg' => date("Y-m-d H:i:s"));
// $bbs->setRow($row);
// $bbs->writeRow();



if (!empty($_POST['a'])) {
	$obj = new \cls\model\board();
	$bbs = new \cls\controller\bulletin(new \cls\model\dbRunQuery($obj->getTableName(), $obj->getUniqKeys(), $obj->getRow(), false));

	// echo 'success..';

	if (!empty($_POST['a'])) {
		$bbs->setRow($_POST);
		$msg = "";
		$location_g = 'l';
		switch ($_POST['a']) {
			case 'i':
				$msg = ($bbs->writeRow()) ? '21' : '20;';
				break;

			case 'u':
				$msg = ($bbs->modifyRow()) ? '31' : '30';
				break;
			
			case 'd':
				$msg = ($bbs->removeRow()) ? '41' : '40';
				break;

			default:
				$msg = $_POST['a'];
				break;
		}

		echo $msg;
		exit;
	}
	else {
		// Exception
	}
}
else {
	// template loading
	require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/twigConfig.php";

	if ($_GET['g'] == 'w') {
		// write form
		$data = array('boardName' => 'bbs');
		// 템플릿 렌더링 및 출력
		echo $twig->render('write.html', ['data' => array($data)]);
	}
	elseif ($_GET['g'] == 'l') {
		// list form
		if (empty($_GET['currentPage'])) $_GET['currentPage'] = 1;
		if (empty($_GET['rowPerPage'])) $_GET['rowPerPage'] = 10;
		if (empty($_GET['dtDiv'])) $_GET['dtDiv'] = 'seq';	// 기본 정렬 : dtReg
		if (empty($_GET['sortDiv'])) $_GET['sortDiv'] = 'DESC';
		$sortArr = array();
		$sortArr[] = array($_GET['dtDiv'] => $_GET['sortDiv']);

		$obj = new \cls\model\boardList($_GET, $sortArr);

		$list = new \cls\controller\bulletinList(new \cls\model\dbListQuery($obj->getQueryStr(), $obj->getCountQueryStr(), $obj->getBindingArr(), false));

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
		echo $twig->render('list.html', ['rows' => $rows, 'pages' => array($pages)]);
	}
	else {
		// view and edit from
		$obj = new \cls\model\board();
		$bbs = new \cls\controller\bulletin(new \cls\model\dbRunQuery($obj->getTableName(), $obj->getUniqKeys(), $obj->getRow(), true));
		$bbs->setRow($_GET);
		$bbs->fetchRow();
		$data = $bbs->getRow();

		$template = ($_GET['g'] == 'e') ? 'edit.html' : 'view.html';
		// 템플릿 렌더링 및 출력
		echo $twig->render($template, ['data' => $data]);
	}
}
