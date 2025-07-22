<?php
echo 'index';

$connect = mysqli_connect("localhost", "webuser", "qhdks8", "anal_db"); 

if ($connect->connect_errno) {
	echo '[연결실패] : ' . $connect->connect_error . '';
}
else {
	echo '[연결성공]<br>';
}

