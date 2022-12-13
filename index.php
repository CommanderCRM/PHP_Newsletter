<?php require_once('config.php') ?>
<?php require_once(ROOT_PATH.'/includes/head_section.php') ?>
<?php require_once(ROOT_PATH.'/includes/public_functions.php') ?>
<?php include('includes/registration_login.php') ?>
	<title>Лента Кривошеина | Домашняя страница </title>
</head>
<body>
	<!-- Контейнер всей страницы -->
	<div class="container">
		<!-- Навигация -->
        <?php include(ROOT_PATH.'/includes/navbar.php') ?>
		<!-- // Навигация -->
		<!-- Баннер -->
		<?php include(ROOT_PATH.'/includes/banner.php') ?>
		<!-- // Баннер -->
		<!-- Страница -->
		<?php $posts = getPublishedPosts(); ?>
		<div class="content">
			<h2 class="content-title">Последние статьи</h2>
			<hr>
			<?php foreach ($posts as $post): ?>
				<div class="post" style="margin-left: 0px;">
					<img src="<?php echo BASE_URL . '/static/images/' . $post['image']; ?>" class="post_image" alt="">
					<?php if (isset($post['topic']['name'])): ?>
						<a 
							href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $post['topic']['id'] ?>"
							class="btn category">
							<?php echo $post['topic']['name'] ?>
						</a>
					<?php endif ?>
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
						
		<!-- Низ -->
		<?php include(ROOT_PATH.'/includes/footer.php') ?>