<?php
/**
 * session start
 */
session_start();

if (empty($_SESSION['admcd'])) {
	// 로그인 화면
	// var_dump($_COOKIE);
	$cookie = (!empty($_COOKIE['savedId'])) ? $_COOKIE['savedId'] : '';
	
	// 템플릿 렌더링 및 출력
	echo $twig->render('admin_login.html', ['cookie' => $cookie]);
	exit;
}
