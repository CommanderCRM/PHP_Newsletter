<?php 

// проверка формы на заполнение коммента и отправку
if (!empty($_POST['comment_body']) and isset($_POST['comment_post'])) {
	postComment($_POST);
}

// проверка на событие удаления коммента
if (isset($_GET['del-comm-id'])) {
	deleteComment($_GET['del-comm-id']);
}

/* * * * * * * * * * * * * * *
* Все опубликованные посты
* * * * * * * * * * * * * * */
function getPublishedPosts() {
	// глобальный объект $conn в функции
	global $conn;
	$sql = "SELECT * FROM posts WHERE published=true";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);

	// все посты передаются в ассоциативный массив $posts
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_posts = array();
	foreach ($posts as $post) {
		$post['topic'] = getPostTopic($post['id']); 
		$post['author'] = getPostAuthorById($post['user_id']);
		array_push($final_posts, $post);
	}
	return $final_posts;
}

/* * * * * * * * * * * * * * *
* Получение ID поста и
* возвращение темы поста
* * * * * * * * * * * * * * */
function getPostTopic($post_id){
	global $conn;
	$sql = "SELECT * FROM topics WHERE id=
			(SELECT topic_id FROM post_topic WHERE post_id=$post_id) LIMIT 1";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	$topic = mysqli_fetch_assoc($result);
	return $topic;
}

/* * * * * * * * * * * * * * * *
* Все посты на определенную тему
* * * * * * * * * * * * * * * * */
function getPublishedPostsByTopic($topic_id) {
	global $conn;
	$sql = "SELECT * FROM posts ps 
			WHERE ps.id IN 
			(SELECT pt.post_id FROM post_topic pt 
				WHERE pt.topic_id=$topic_id GROUP BY pt.post_id 
				HAVING COUNT(1) = 1)";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	// все посты передаются в ассоциативный массив $posts
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_posts = array();
	foreach ($posts as $post) {
		$post['topic'] = getPostTopic($post['id']); 
		$post['author'] = getPostAuthorById($post['user_id']);
		array_push($final_posts, $post);
	}
	return $final_posts;
}
/* * * * * * * * * * * * * * * *
* Имя темы по ID поста
* * * * * * * * * * * * * * * * */
function getTopicNameById($id)
{
	global $conn;
	$sql = "SELECT name FROM topics WHERE id=$id";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	$topic = mysqli_fetch_assoc($result);
	return $topic['name'];
}

/* * * * * * * * * * * * * * *
* Один пост
* * * * * * * * * * * * * * */
function getPost($slug){
	global $conn;
	// ссылка на пост
	$post_slug = $_GET['post-slug'];
	$sql = "SELECT * FROM posts WHERE slug='$post_slug' AND published=true";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	// ассоциативный массив
	$post = mysqli_fetch_assoc($result);
	if ($post) {
		// тема поста
		$post['topic'] = getPostTopic($post['id']);
		$post['author'] = getPostAuthorById($post['user_id']);
	}
	return $post;
}

/* * * * * * * * * * * *
*  Все темы
* * * * * * * * * * * * */
function getAllTopics()
{
	global $conn;
	$sql = "SELECT * FROM topics";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	$topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $topics;
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

// Комментарии по текущему посту
function getCommentsByPostSlug($post_slug)
{
	global $conn;
	$sql = "SELECT * FROM comments WHERE post_id='$post_slug'";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $comments;
}

// Никнейм по ID пользователя
function getUserNicknameById($user_id)
{
	global $conn;
	$sql = "SELECT username FROM users WHERE id=$user_id";
	mysqli_set_charset($conn, 'utf8');
	$result = mysqli_query($conn, $sql);
	if ($result) {
		return mysqli_fetch_assoc($result)['username'];
	} else {
		return null;
	}
}

// отправка коммента
function postComment($request_values) {
	global $conn;
	// получаем данные из формы
	$comment = $request_values['comment_body'];
	$post_id = $request_values['post_id'];
	$user_id = $request_values['user_id'];
	$comment = htmlspecialchars($comment);
	// вставляем комментарий в БД
	$sql = "INSERT INTO comments (comm, post_id, user_id, created_at) VALUES ('$comment', '$post_id', '$user_id', now())";
	mysqli_set_charset($conn, 'utf8');
	mysqli_query($conn, $sql);
	// перенаправляем на страницу поста
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

// удаление коммента
function deleteComment($id) {
	global $conn;
	$sql = "DELETE FROM comments WHERE id=$id";
	mysqli_set_charset($conn, 'utf8');
	mysqli_query($conn, $sql);
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

?>