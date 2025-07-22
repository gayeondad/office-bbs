<?php
/**
 * autoload
 */


// 사용자 정의 자동 로더 등록 (선택 사항)
spl_autoload_register(function ($class) {
    // MyNamespace\로 시작하는 클래스만 처리
    $prefix = 'cls\\';
    $baseDir = '/cls/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
    	// echo $prefix . ' | ' . $class . ' | ';
    	// echo "해당 네임스페이스가 아니면 무시";
        return; // 해당 네임스페이스가 아니면 무시
    }

    $relativeClass = substr($class, $len);
    $file = $_SERVER['DOCUMENT_ROOT'] . $baseDir . str_replace('\\', '/', $relativeClass) . '.cls.php';
    // echo "path: " . $file;

    if (file_exists($file)) {
        require $file;
    }
});

require_once "autoload.php";
