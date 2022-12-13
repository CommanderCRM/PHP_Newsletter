<?php  include('config.php'); ?>
<?php  include('includes/public_functions.php'); ?>
<?php  include('includes/registration_login.php') ?>
<?php 
	if (isset($_GET['post-slug'])) {
		$post = getPost($_GET['post-slug']);
	}
	$topics = getAllTopics();
?>
<?php include('includes/head_section.php'); ?>
<title> <?php echo $post['title'] ?> | Лента Кривошеина </title>
</head>
<body>
<div class="container">
	<!-- Навигация -->
		<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
	<!-- // Навигация -->
	<!-- Баннер -->
		<?php include(ROOT_PATH.'/includes/banner.php') ?>
	<!-- // Баннер -->
	<div class="content" >
		<!-- Обертка -->
		<div class="post-wrapper">
			<!-- Пост -->
			<div class="full-post-div">
			<?php if ($post['published'] == false): ?>
				<h2 class="post-title">Эта новость не опубликована</h2>
			<?php else: ?>
				<h2 class="post-title"><?php echo $post['title']; ?></h2>
				<div class="post-body-div">
					<?php echo html_entity_decode($post['body']); ?>
					<?php echo '<p> Автор: ' . $post['author'] . " " . '</p>' ?>
				</div>
			<?php endif ?>
			</div>
			<!-- // Пост -->
		</div>
			<!-- // Обертка -->
			<!-- Сайдбар -->
		<div class="post-sidebar">
			<div class="card">
				<div class="card-header">
					<h2>Темы</h2>
				</div>
				<div class="card-content">
					<?php foreach ($topics as $topic): ?>
						<a 
							href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $topic['id'] ?>">
							<?php echo $topic['name']; ?>
						</a> 
					<?php endforeach ?>
				</div>
			</div>
		</div>
			<!-- // Сайдбар -->
			<!-- Комментарии -->
			<?php include(ROOT_PATH . '/includes/comments.php') ?>
	</div>
</div>
<!-- // Содержимое -->
<?php include( ROOT_PATH . '/includes/footer.php'); ?>