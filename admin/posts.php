<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/post_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<!-- Посты из БД -->
<?php $posts = getAllPosts(); ?>
	<title>Администрирование | Управление статьями</title>
</head>
<body>
	<!-- навбар -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>

	<div class="container content">
		<!-- сайд меню -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

		<!-- Записи из БД-->
		<div class="table-div"  style="width: 80%;">
			<!-- Уведомление -->
			<?php include(ROOT_PATH . '/admin/includes/messages.php') ?>

			<?php if (empty($posts)): ?>
				<h1 style="text-align: center; margin-top: 20px;">В БД нет записей.</h1>
			<?php else: ?>
				<table class="table">
						<thead>
						<th>N</th>
						<th>Автор</th>
						<th>Название</th>
						<th>Просмотры</th>
						<!-- публикация только администраторам -->
						<?php if ($_SESSION['user']['role'] == "Admin"): ?>
							<th><small>Опубликовать</small></th>
						<?php endif ?>
						<th><small>Редактировать</small></th>
						<th><small>Удалить</small></th>
					</thead>
					<tbody>
					<?php foreach ($posts as $key => $post): ?>
						<tr>
							<td><?php echo $key + 1; ?></td>
							<td><?php echo $post['author']; ?></td>
							<td>
								<a 	target="_blank"
								href="<?php echo BASE_URL . 'single_post.php?post-slug=' . $post['slug'] ?>">
									<?php echo $post['title']; ?>	
								</a>
							</td>
							<td><?php echo $post['views']; ?></td>
							
							<!-- проверка на роль адм, публикация/отмена публикации -->
							<?php if ($_SESSION['user']['role'] == "Admin" ): ?>
								<td>
								<?php if ($post['published'] == true): ?>
									<a class="fa fa-check btn unpublish"
										href="posts.php?unpublish=<?php echo $post['id'] ?>">
									</a>
								<?php else: ?>
									<a class="fa fa-times btn publish"
										href="posts.php?publish=<?php echo $post['id'] ?>">
									</a>
								<?php endif ?>
								</td>
							<?php endif ?>

							<td>
								<a class="fa fa-pencil btn edit"
									href="create_post.php?edit-post=<?php echo $post['id'] ?>">
								</a>
							</td>
							<td>
								<a  class="fa fa-trash btn delete" 
									href="create_post.php?delete-post=<?php echo $post['id'] ?>">
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