<?php 
// Пользовательские переменные
$admin_id = 0;
$isEditingUser = false;
$username = "";
$role = "";
$email = "";
// Переменная для ошибок
$errors = [];

// Переменные тем
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";

// пост
$post_id = 0;

/* - - - - - - - - - - 
-  Действия с пользователями-администраторами
- - - - - - - - - - -*/
// Нажатие на создание администратора
if (isset($_POST['create_admin'])) {
	createAdmin($_POST);
}
// Редактирование администратора
if (isset($_GET['edit-admin'])) {
	$isEditingUser = true;
	$admin_id = $_GET['edit-admin'];
	editAdmin($admin_id);
}
// Обновление администратора
if (isset($_POST['update_admin'])) {
	updateAdmin($_POST);
}
// Удаление администратора
if (isset($_GET['delete-admin'])) {
	$admin_id = $_GET['delete-admin'];
	deleteAdmin($admin_id);
}

/* - - - - - - - - - - 
-  Действия с темами
- - - - - - - - - - -*/
// Если тема создается
if (isset($_POST['create_topic'])) { createTopic($_POST); }
// Если редактируется
if (isset($_GET['edit-topic'])) {
	$isEditingTopic = true;
	$topic_id = $_GET['edit-topic'];
	editTopic($topic_id);
}
// Если обновляется
if (isset($_POST['update_topic'])) {
	updateTopic($_POST);
}
// Если нажимаем на удаление
if (isset($_GET['delete-topic'])) {
	$topic_id = $_GET['delete-topic'];
	deleteTopic($topic_id);
}


/* - - - - - - - - - - - -
-  Функции администраторов
- - - - - - - - - - - - -*/
/* * * * * * * * * * * * * * * * * * * * * * *
* - Получение данных с формы
* - Создание администратора
* - Возврат администраторов
* * * * * * * * * * * * * * * * * * * * * * */
function createAdmin($request_values){
	global $conn, $errors, $role, $username, $email;
	mysqli_set_charset($conn, 'utf8');
	$username = esc($request_values['username']);
	$email = esc($request_values['email']);
	$password = esc($request_values['password']);
	$passwordConfirmation = esc($request_values['passwordConfirmation']);

	if(isset($request_values['role'])){
		$role = esc($request_values['role']);
	}
	// Валидация ввода
	if (empty($username)) { array_push($errors, "Введите имя пользователя"); }
	if (empty($email)) { array_push($errors, "Введите email"); }
	if (empty($role)) { array_push($errors, "Нужна роль");}
	if (empty($password)) { array_push($errors, "Введите пароль"); }
	if ($password != $passwordConfirmation) { array_push($errors, "Пароли не совпадают"); }
	// запрет на двойную регистрацию
	// проверка на уникальность почты и имени
	$user_check_query = "SELECT * FROM users WHERE username='$username' 
							OR email='$email' LIMIT 1";
	$result = mysqli_query($conn, $user_check_query);
	$user = mysqli_fetch_assoc($result);
	if ($user) { // if user exists
		if ($user['username'] === $username) {
		  array_push($errors, "Имя уже есть");
		}

		if ($user['email'] === $email) {
		  array_push($errors, "Email уже есть");
		}
	}
	// Регистрация при отсутствии ошибок
	if (count($errors) == 0) {
		$password = md5($password);//хэширование
		$query = "INSERT INTO users (username, email, role, password, created_at, updated_at) 
				  VALUES('$username', '$email', '$role', '$password', now(), now())";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Администратор создан успешно";
		header('location: users.php');
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * *
* - параметр - id администратора
* - Получение информации об администраторе из БД
* - поля администратора
* * * * * * * * * * * * * * * * * * * * * */
function editAdmin($admin_id)
{
	global $conn, $username, $role, $isEditingUser, $admin_id, $email;
	mysqli_set_charset($conn, 'utf8');

	$sql = "SELECT * FROM users WHERE id=$admin_id LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$admin = mysqli_fetch_assoc($result);

	// значения формы для редактирования
	$username = $admin['username'];
	$email = $admin['email'];
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Запрос из формы и обновление в БД
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function updateAdmin($request_values){
	global $conn, $errors, $role, $username, $isEditingUser, $admin_id, $email;
	mysqli_set_charset($conn, 'utf8');
	// Получение ID того кого обновляем
	$admin_id = $request_values['admin_id'];
	// Редактируем ли пользователя
	$isEditingUser = false;


	$username = esc($request_values['username']);
	$email = esc($request_values['email']);
	$password = esc($request_values['password']);
	$passwordConfirmation = esc($request_values['passwordConfirmation']);
	if(isset($request_values['role'])){
		$role = $request_values['role'];
	}
	// Обновление если нет ошибок
	if (count($errors) == 0) {
		//хэш
		$password = md5($password);

		$query = "UPDATE users SET username='$username', email='$email', role='$role', password='$password' WHERE id=$admin_id";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Администратор обновлен успешно";
		header('location: users.php');
		exit(0);
	}
}
// Удаление администратора 
function deleteAdmin($admin_id) {
	global $conn;
	mysqli_set_charset($conn, 'utf8');
	$sql = "DELETE FROM users WHERE id=$admin_id";
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = "Администратор успешно удален";
		header("location: users.php");
		exit(0);
	}
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Администраторы и их роли
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function getAdminUsers(){
	global $conn, $roles;
	mysqli_set_charset($conn, 'utf8');
	$sql = "SELECT * FROM users WHERE role IS NOT NULL";
	$result = mysqli_query($conn, $sql);
	$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

	return $users;
}
/* * * * * * * * * * * * * * * * * * * * *
* - Защита от SQL инъекции
* * * * * * * * * * * * * * * * * * * * * */
function esc(String $value){
	// связь с БД
	global $conn;
	mysqli_set_charset($conn, 'utf8');
	// вырезание пробелов
	$val = trim($value); 
	$val = mysqli_real_escape_string($conn, $value);
	return $val;
}
// преобразование строки
// в строку с тире
function makeSlug(String $string){
	$string = strtolower($string);
	$slug = preg_replace('/[^A-Za-zА-Яа-я0-9-]+/', '-', $string);
	return $slug;
}

/* - - - - - - - - - - 
-  Функции для работы с темами
- - - - - - - - - - -*/
// Получение всех тем из БД
function getAllTopics() {
	global $conn;
	mysqli_set_charset($conn, 'utf8');
	$sql = "SELECT * FROM topics";
	$result = mysqli_query($conn, $sql);
	$topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $topics;
}
function createTopic($request_values){
	global $conn, $errors, $topic_name;
	mysqli_set_charset($conn, 'utf8');
	$topic_name = esc($request_values['topic_name']);
	// Создание имени топика для URL
	$topic_slug = uniqid();
	// Валидация формы
	if (empty($topic_name)) { 
		array_push($errors, "Нужно имя темы"); 
	}
	// Лимит на одну тему
	$topic_check_query = "SELECT * FROM topics WHERE slug='$topic_slug' LIMIT 1";
	$result = mysqli_query($conn, $topic_check_query);
	if (mysqli_num_rows($result) > 0) { // Если тема есть
		array_push($errors, "Такая тема уже есть");
	}
	// тему в БД если нет ошибок
	if (count($errors) == 0) {
		$query = "INSERT INTO topics (name, slug) 
				  VALUES('$topic_name', '$topic_slug')";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Тема создана успешно";
		header('location: topics.php');
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * *
* - Параметр - ID темы
* - Получение темы из БД
* - Поля темы в форму для редактирования
* * * * * * * * * * * * * * * * * * * * * */
function editTopic($topic_id) {
	global $conn, $topic_name, $isEditingTopic, $topic_id;
	mysqli_set_charset($conn, 'utf8');
	$sql = "SELECT * FROM topics WHERE id=$topic_id LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$topic = mysqli_fetch_assoc($result);
	// Установка значений на форму
	$topic_name = $topic['name'];
}
function updateTopic($request_values) {
	global $conn, $errors, $topic_name, $topic_id;
	mysqli_set_charset($conn, 'utf8');
	$topic_name = esc($request_values['topic_name']);
	$topic_id = esc($request_values['topic_id']);
	// Создание имени топика для URL
	$topic_slug = uniqid();
	// Валидация
	if (empty($topic_name)) { 
		array_push($errors, "Нужно имя темы"); 
	}
	// Обновление темы если нет ошибок
	if (count($errors) == 0) {
		$query = "UPDATE topics SET name='$topic_name', slug='$topic_slug' WHERE id=$topic_id";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Тема успешно обновлена";
		header('location: topics.php');
		exit(0);
	}
}
// Удаление темы
function deleteTopic($topic_id) {
	global $conn;
	mysqli_set_charset($conn, 'utf8');
	$sql = "DELETE FROM topics WHERE id=$topic_id";
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = "Тема успешно удалена";
		header("location: topics.php");
		exit(0);
	}
}

// считаем пользователей
function countAllUsers()
{
	global $conn;
	$sql = "SELECT COUNT(*) AS total FROM users";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	$values = mysqli_fetch_assoc($result);
	$num_rows = $values['total'];
	return $num_rows;
}

// считаем посты
function countAllPosts()
{
	global $conn;
	$sql = "SELECT COUNT(*) AS total FROM posts";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	$values = mysqli_fetch_assoc($result);
	$num_rows = $values['total'];
	return $num_rows;
}

// считаем комментарии
function countAllComments()
{
	global $conn;
	$sql = "SELECT COUNT(*) AS total FROM comments";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	$values = mysqli_fetch_assoc($result);
	$num_rows = $values['total'];
	return $num_rows;
}

// название картинки в БД
function getNameForImage($post_id)
{
	global $conn, $post_id;
	$sql = "SELECT image FROM posts WHERE id=$post_id";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	$values = mysqli_fetch_assoc($result);
	$image_name = $values['image'];
	return $image_name;
}
?>