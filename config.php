<?php 
	session_start();

	$conn = mysqli_connect("localhost", "USER", "PASS", "DB");

	if (!$conn) {
		die("Ошибка подключения к БД: " . mysqli_connect_error());
	}
	
	define ('ROOT_PATH', realpath(dirname(__FILE__)));
	define('BASE_URL', 'https://BASEURL/');
?>