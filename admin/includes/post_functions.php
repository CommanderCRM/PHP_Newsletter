<?php 
// переменные для статей
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$post_topic = "";
$user_id = "";

/* - - - - - - - - - - 
-  Действия со статьями
- - - - - - - - - - -*/
// создание
if (isset($_POST['create_post'])) { createPost($_POST); }
// редактирование
if (isset($_GET['edit-post'])) {
	$isEditingPost = true;
	$post_id = $_GET['edit-post'];
	editPost($post_id);
}
// обновление
if (isset($_POST['update_post'])) {
	updatePost($_POST);
}
// удаление
if (isset($_GET['delete-post'])) {
	$post_id = $_GET['delete-post'];
	deletePost($post_id);
}
// публикация кнопкой
if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
	$message = "";
	if (isset($_GET['publish'])) {
		$message = "Успешная публикация";
		$post_id = $_GET['publish'];
	} else if (isset($_GET['unpublish'])) {
		$message = "Успешная отмена публикации";
		$post_id = $_GET['unpublish'];
	}
	togglePublishPostGET($post_id, $message);
}


/* - - - - - - - - - - 
-  функции для статей
- - - - - - - - - - -*/
// записи из БД
function getAllPosts()
{
	global $conn;
	
	// администратор видит все посты
	// автор - только свои
	if ($_SESSION['user']['role'] == "Admin") {
		$sql = "SELECT * FROM posts";
	} elseif ($_SESSION['user']['role'] == "Author") {
		$user_id = $_SESSION['user']['id'];
		$sql = "SELECT * FROM posts WHERE user_id=$user_id";
	}
    mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_posts = array();
	foreach ($posts as $post) {
		$post['author'] = getPostAuthorById($post['user_id']);
		array_push($final_posts, $post);
	}
	return $final_posts;
}
// автор поста
function getPostAuthorById($user_id)
{
	global $conn;
	$sql = "SELECT username FROM users WHERE id=$user_id";
    mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	if ($result) {
		// имя автора
		return mysqli_fetch_assoc($result)['username'];
	} else {
		return null;
	}
}

/* - - - - - - - - - - 
-  функции статей
- - - - - - - - - - -*/
function createPost($request_values)
	{
		global $conn, $errors, $title, $featured_image, $topic_id, $body, $published;
		$title = esc($request_values['title']);
		$body = htmlentities(($request_values['body']));
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		if (isset($request_values['publish'])) {
			$published = esc($request_values['publish']);
		}
		// адрес статьи
		$post_slug = uniqid();
		// валидация
		if (empty($title)) { array_push($errors, "Введите название статьи"); }
		if (empty($body)) { array_push($errors, "Нужно что-то ввести в тело статьи"); }
		if (empty($topic_id)) { array_push($errors, "Нужна тема статьи"); }
		// файл картинки
		$featured_image = $_FILES['featured_image']['name'];
		if (empty($featured_image)) { array_push($errors, "Нужна картинка"); }
		// путь к картинке
		$target = ROOT_PATH . "/static/images/" . basename($featured_image);
		if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
			array_push($errors, "Не получилось загрузить картинку");
		}
		// чтобы не сохранялись два раза
		$post_check_query = "SELECT * FROM posts WHERE slug='$post_slug' LIMIT 1";
		mysqli_set_charset($conn, 'utf8');
		$result = mysqli_query($conn, $post_check_query);
		$user_id = $_SESSION['user']['id'];

		if (!$result || mysqli_num_rows($result) > 0) { // если есть такая статья
			array_push($errors, "Статья с таким названием уже есть.");
		}

		// нет ошибок - сохраняем
		if (count($errors) == 0) {
			$query = "INSERT INTO posts (user_id, title, slug, image, body, published, created_at, updated_at) VALUES($user_id, '$title', '$post_slug', '$featured_image', '$body', $published, now(), now())";
			if(mysqli_query($conn, $query)){ // успешное сохранение
				$inserted_post_id = mysqli_insert_id($conn);
				echo $query;
				// статья/тема статьи в БД
				$sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
				mysqli_set_charset($conn, 'utf8');
				mysqli_query($conn, $sql);

				$_SESSION['message'] = "Успешное создание статьи";
				header('location: posts.php');
				exit(0);
			}
		}
	}

	/* * * * * * * * * * * * * * * * * * * * *
	* - id статьи - параметр
	* - получение из БД
	* - поля идут в форму для редактирования
	* * * * * * * * * * * * * * * * * * * * * */
	function editPost($post_id)
	{
		global $conn, $title, $post_slug, $image, $body, $published, $isEditingPost, $post_id;
		$sql = "SELECT * FROM posts WHERE id=$post_id LIMIT 1";
		mysqli_set_charset($conn, 'utf8');
		$result = mysqli_query($conn, $sql);
		$post = mysqli_fetch_assoc($result);
		// установка значений формы
		$title = $post['title'];
		$body = $post['body'];
		$published = $post['published'];
	}

	function updatePost($request_values)
	{
		global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;

		$title = esc($request_values['title']);
		$body = esc($request_values['body']);
		$post_id = esc($request_values['post_id']);
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		// название в адресную строку
		$post_slug = uniqid();

		if (empty($title)) { array_push($errors, "Нужно название статьи"); }
		if (empty($body)) { array_push($errors, "Нужно заполнить тело статьи"); }

		// файлы картинки
		$featured_image = getNameForImage($post_id);
		$new_featured_image = $_FILES['featured_image']['name'];

		// если залили новую
		if (!empty($new_featured_image)) {
			$target = ROOT_PATH . "/static/images/" . basename($new_featured_image);
			if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
				array_push($errors, "Не получилось загрузить картинку");
			}
		} 

		// статья публикуется если нет ошибок
		if (count($errors) == 0) {
			// есть новая загруженная картинка или нет
			if (!empty($new_featured_image)) {
				$query = "UPDATE posts SET title='$title', slug='$post_slug', image='$new_featured_image', body='$body', published=$published, updated_at=now() WHERE id=$post_id";
			} else {
				$query = "UPDATE posts SET title='$title', slug='$post_slug', body='$body', published=$published, updated_at=now() WHERE id=$post_id";
			}

			// тема/статья
			if(mysqli_query($conn, $query)){ // успешное сохранение
				if (isset($topic_id)) {

					// удаление картинки если она не используется
					$img_check_query = "SELECT * FROM posts WHERE image = '$featured_image'";
					mysqli_set_charset($conn, 'utf8');
					$img_check = mysqli_query($conn, $img_check_query);
					if (mysqli_num_rows($img_check) == 0) {
						$target = ROOT_PATH . "/static/images/" . basename($featured_image);
						unlink($target);
					}

					// тема/статья
					$sql = "UPDATE post_topic SET topic_id=$topic_id WHERE post_id=$post_id";
					mysqli_set_charset($conn, 'utf8');
					mysqli_query($conn, $sql);
					$_SESSION['message'] = "Успешное обновление";
					header('location: posts.php');
					exit(0);
				}
			}
			$_SESSION['message'] = "Успешное обновление";
			header('location: posts.php');
			exit(0);
		}
	}
	// удаление статьи
	function deletePost($post_id)
	{
		global $conn;
		$featured_image = getNameForImage($post_id);
		$sql = "DELETE FROM posts WHERE id=$post_id";
		$image_check_sql = "SELECT * FROM posts WHERE image='$featured_image'";
		mysqli_set_charset($conn, 'utf8');
		$img_result = mysqli_query($conn, $image_check_sql);

		// если картинка используется только в 1 посте, то удаляем ее
		if (mysqli_num_rows($img_result) == 1){
				$target = ROOT_PATH . "/static/images/" . basename($featured_image);
				unlink($target);
			}
			
		if (mysqli_query($conn, $sql)) {
			$_SESSION['message'] = "Успешное удаление";
			header("location: posts.php");
			exit(0);
		}
	}

	// публикация статьи с главной
	function togglePublishPostGET($post_id, $message)
	{
		global $conn;
		$sql = "UPDATE posts SET published = NOT published WHERE id=$post_id";
		mysqli_set_charset($conn, 'utf8');
		if (mysqli_query($conn, $sql)) {
			$_SESSION['message'] = $message;
			header("location: posts.php");
			exit(0);
		}
	}

	function getTopicIDByPostID($post_id)
	{
		global $conn;
		$sql = "SELECT topic_id FROM post_topic WHERE post_id=$post_id LIMIT 1";
		mysqli_set_charset($conn, 'utf8');
		$result = mysqli_query($conn, $sql);
		if (!$result || mysqli_num_rows($result) == 0)
		{
			return "Без темы";
		}
		else {
			$topic = mysqli_fetch_assoc($result);
			return $topic['topic_id'];
		}
	}

	function getTopicNameByTopicID($topic_id)
	{
		global $conn;
		$sql = "SELECT name FROM topics WHERE id=$topic_id LIMIT 1";
		mysqli_set_charset($conn, 'utf8');
		$result = mysqli_query($conn, $sql);
		if (!$result || mysqli_num_rows($result) == 0)
		{
			return "Без темы";
		}
		else {
			$topic = mysqli_fetch_assoc($result);
			return $topic['name'];
		}
	}
?>