<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] != "Admin"): ?>
		<?php header('location: ' . BASE_URL . '/index.php'); ?>
		<?php $_SESSION['message'] = "Нет разрешений на просмотр страницы"; ?>
<?php endif ?>
<!-- Получение всех тем из БД -->
<?php $topics = getAllTopics();	?>
	<title>Администрирование | Управление темами</title>
</head>
<body>
	<!-- Навигация -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>
	<div class="container content">
		<!-- Левое меню -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

		<!-- Форма создания и редактирования -->
		<div class="action">
			<h1 class="page-title">Создать/редактировать тему</h1>
			<form method="post" action="<?php echo BASE_URL . 'admin/topics.php'; ?>" >
				<!-- Проверка на ошибки -->
				<?php include(ROOT_PATH . '/includes/errors.php') ?>
				<!-- Нужно ID для редактирования -->
				<?php if ($isEditingTopic === true): ?>
					<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
				<?php endif ?>
				<input type="text" name="topic_name" value="<?php echo $topic_name; ?>" placeholder="Тема">
				<!-- При редактировании показывать кнопку обновления -->
				<?php if ($isEditingTopic === true): ?> 
					<button type="submit" class="btn" name="update_topic">Обновить</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_topic">Сохранить тему</button>
				<?php endif ?>
			</form>
		</div>
		<!-- // Форма создания и редактирования -->

		<!-- Записи из БД -->
		<div class="table-div">
			<!-- Уведомление -->
			<?php include(ROOT_PATH . '/admin/includes/messages.php') ?>
			<?php if (empty($topics)): ?>
				<h1>В БД нет тем.</h1>
			<?php else: ?>
				<table class="table">
					<thead>
						<th>N</th>
						<th>Название темы</th>
						<th colspan="2">Действие</th>
					</thead>
					<tbody>
					<?php foreach ($topics as $key => $topic): ?>
						<tr>
							<td><?php echo $key + 1; ?></td>
							<td><?php echo $topic['name']; ?></td>
							<td>
								<a class="fa fa-pencil btn edit"
									href="topics.php?edit-topic=<?php echo $topic['id'] ?>">
								</a>
							</td>
							<td>
								<a class="fa fa-trash btn delete"								
									href="topics.php?delete-topic=<?php echo $topic['id'] ?>">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Записи из БД -->
	</div>
</body>
</html>