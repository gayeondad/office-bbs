<?php
/**
 * twig template configuration
 */
// template loading
$templatePath = $_SERVER['DOCUMENT_ROOT'] . "/tpl";
$loader = new \Twig\Loader\FilesystemLoader($templatePath);
$twig = new \Twig\Environment($loader, [
	// 'cache' => './cache/twig', 	// 컴파일된 템플릿이 저장될 디렉토리 (쓰기 권한 필요!)
	'auto_reload' => true,			// 템플릿 파일 변경 시 자동으로 재컴파일 (개발 시 유용)
	'debug' => true 				// 디버그 모드 활성화 (개발 시 유용)
]);

// 템플릿 렌더링 및 출력
// $template = $twig->load('list.html');
// echo $template->render($rows);
// OR
// echo $twig->render('list.html', ['rows' => $rows, 'pages' => array($pages)]);
