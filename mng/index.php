<?php
/**
 * 관리자 페이지
 */

require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/commonInclude.php";



// 관리자 대시보드 화면
$data = array("session" => $_SESSION);
// 템플릿 렌더링 및 출력
echo $twig->render('admin_dash.html', ['data' => $data]);

