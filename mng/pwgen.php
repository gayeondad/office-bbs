<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/autoload.php";


$auth = new \cls\common\auth();

$str = 'qhdks8';
echo $auth->generatePwKey($str);
