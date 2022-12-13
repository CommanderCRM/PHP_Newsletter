<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/post_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<!-- все темы -->
<?php $topics = getAllTopics();	?>

	<title>Администрирование | Создание статьи</title>
</head>
<body>
	<!-- навбар -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>

	<div class="container content">
		<!-- сайд меню -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

		<!-- Форма создания и редактирования  -->
		<div class="action create-post-div">
			<h1 class="page-title">Создать/редактировать статью</h1>
			<form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_post.php'; ?>" >
				<!-- Проверка на ошибки -->
				<?php include(ROOT_PATH . '/includes/errors.php') ?>

				<!-- при редактировании нужно обращаться к посту по ID -->
				<?php if ($isEditingPost === true): ?>
					<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
				<?php endif ?>

				<input type="text" name="title" value="<?php echo $title; ?>" placeholder="Название">
				<label style="float: left; margin: 5px auto 5px;">Картинка</label>
				<input type="file" name="featured_image" >
				<textarea name="body" id="body" cols="30" rows="10"><?php echo $body; ?></textarea>
				<?php if ($isEditingPost === true): ?>
				<p name="current_topic">Текущая тема: <?php echo getTopicNameByTopicID(getTopicIDByPostID($post_id)) ?> </p>
				<?php endif ?>
				<?php if ($isEditingPost === true): ?> 
				<select name="topic_id">
					<option value="" selected disabled>Выберите новую тему</option>
					<?php foreach ($topics as $topic): ?>
						<option value="<?php echo $topic['id']; ?>">
							<?php echo $topic['name']; ?>
						</option>
					<?php endforeach ?>
				</select>
				<?php else: ?>
				<select name="topic_id">
					<option value="" selected disabled>Выберите тему</option>
					<?php foreach ($topics as $topic): ?>
						<option value="<?php echo $topic['id']; ?>">
							<?php echo $topic['name']; ?>
						</option>
					<?php endforeach ?>
				</select>
				<?php endif ?>

				<!-- при редактировании обновление вместо сохранения -->
				<?php if ($isEditingPost === true): ?> 
					<button type="submit" class="btn" name="update_post">Обновить</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_post">Сохранить пост</button>
				<?php endif ?>

			</form>
		</div>
		<!-- // Форма создания и редактирования -->
	</div>
</body>
</html>

<script>
	CKEDITOR.replace('body');
</script>