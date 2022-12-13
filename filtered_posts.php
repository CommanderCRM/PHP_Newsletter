<?php include('config.php'); ?>
<?php include('includes/public_functions.php'); ?>
<?php include('includes/head_section.php'); ?>
<?php include('includes/registration_login.php') ?>
<?php 
	// Посты на определенную тему
	if (isset($_GET['topic'])) {
		$topic_id = $_GET['topic'];
		$posts = getPublishedPostsByTopic($topic_id);
	}
?>
	<title>Лента Кривошеина | Домашняя страница</title>
</head>
<body>
<div class="container">
<!-- Навигация -->
	<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
<!-- // Навигация -->
<!-- Баннер -->
	<?php include(ROOT_PATH.'/includes/banner.php') ?>
<!-- // Баннер -->
<!-- Содержимое -->
<div class="content">
	<h2 class="content-title">
		Статьи на тему <u><?php echo getTopicNameById($topic_id); ?></u>
	</h2>
	<hr>
	<?php foreach ($posts as $post): ?>
		<div class="post" style="margin-left: 0px;">
			<img src="<?php echo BASE_URL . '/static/images/' . $post['image']; ?>" class="post_image" alt="">
			<a href="single_post.php?post-slug=<?php echo $post['slug']; ?>">
				<div class="post_info">
					<h3><?php echo $post['title'] ?></h3>
					<div class="info">
					<span><?php echo date("d.m.Y", strtotime($post["created_at"])); ?></span>
					<span><?php echo $post['author'] ?></span>
						<span class="read_more">Читать дальше...</span>
					</div>
				</div>
			</a>
		</div>
	<?php endforeach ?>
</div>
<!-- // Содержимое -->
</div>
<!-- // Контейнер -->

<!-- Низ -->
	<?php include( ROOT_PATH . '/includes/footer.php'); ?>
<!-- // Низ -->