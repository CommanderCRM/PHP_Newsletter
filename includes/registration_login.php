<?php 
	// переменные
	$username = "";
	$email    = "";
	$errors = array(); 

	// Регистрация
	if (isset($_POST['reg_user'])) {
		// Получение значений из формы
		$username = esc($_POST['username']);
		$email = esc($_POST['email']);
		$password_1 = esc($_POST['password_1']);
		$password_2 = esc($_POST['password_2']);

		// Проверка на корректность
		if (empty($username)) {  array_push($errors, "Введите имя пользователя"); }
		if (empty($email)) { array_push($errors, "Введите email"); }
		if (empty($password_1)) { array_push($errors, "Введите пароль"); }
		if ($password_1 != $password_2) { array_push($errors, "Пароли не совпадают");}

		// Проверка на двойную регистрацию
		// Не должно быть повторяющихся пользователей и email
		$user_check_query = "SELECT * FROM users WHERE username='$username' 
								OR email='$email' LIMIT 1";
		mysqli_set_charset($conn, 'utf8');
		$result = mysqli_query($conn, $user_check_query);
		$user = mysqli_fetch_assoc($result);

		if ($user) { // Если есть
			if ($user['username'] === $username) {
			  array_push($errors, "Такое имя уже зарегистрировано");
			}
			if ($user['email'] === $email) {
			  array_push($errors, "Такой email уже зарегистрирован");
			}
		}
		// Нет ошибок - регистрируем
		if (count($errors) == 0) {
			$password = md5($password_1);//Шифрование md5
			$query = "INSERT INTO users (username, email, password, created_at, updated_at) 
					  VALUES('$username', '$email', '$password', now(), now())";
			mysqli_set_charset($conn, 'utf8');
			mysqli_query($conn, $query);

			// ID созданного пользователя
			$reg_user_id = mysqli_insert_id($conn); 

			// Сессия по входу пользователя
			$_SESSION['user'] = getUserById($reg_user_id);

				$_SESSION['message'] = "Успешный вход";
				// Основной сайт
				header('location: index.php');				
				exit(0);
				
		}
	}

	// Вход
	if (isset($_POST['login_btn'])) {
		$username = esc($_POST['username']);
		$password = esc($_POST['password']);

		if (empty($username)) { array_push($errors, "Нужно имя пользователя"); }
		if (empty($password)) { array_push($errors, "Нужен пароль"); }
		if (empty($errors)) {
			$password = md5($password); // Шифрование md5
			$sql = "SELECT * FROM users WHERE username='$username' and password='$password' LIMIT 1";
			mysqli_set_charset($conn, 'utf8');
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				// ID созданного пользователя
				$reg_user_id = mysqli_fetch_assoc($result)['id']; 

				// Сессия по входу пользователя
				$_SESSION['user'] = getUserById($reg_user_id); 

					$_SESSION['message'] = "Успешный вход";
					// Основной сайт
					header('location: index.php');				
					exit(0);

			} else {
				array_push($errors, 'Неверные данные');
			}
		}
	}
	// Убираем пробелы
	function esc(String $value)
	{	
		// глобальное соединение
		global $conn;
		mysqli_set_charset($conn, 'utf8');
		$val = trim($value); // Убираем пробелы
		$val = mysqli_real_escape_string($conn, $value);

		return $val;
	}
	// Данные о пользователе по ID
	function getUserById($id)
	{
		global $conn;
		$sql = "SELECT * FROM users WHERE id=$id LIMIT 1";
		mysqli_set_charset($conn, 'utf8');
		$result = mysqli_query($conn, $sql);
		$user = mysqli_fetch_assoc($result);

		// массив: 
		// ['id'=>1 'username' => 'CRM', 'email'=>'a@a.com', 'password'=> 'test']
		return $user; 
	}
?>