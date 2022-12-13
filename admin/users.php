<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] != "Admin"): ?>
	<?php header('location: ' . BASE_URL . '/index.php'); ?>
	<?php $_SESSION['message'] = "Нет разрешений на просмотр страницы"; ?>
	<?php 
	echo '<script type="text/javascript">';
	echo ' alert("Нет разрешений на просмотр страницы")'; 
	echo '</script>';
	?>
<?php endif ?>
<?php 
	// Получение всех администраторов из БД
	$admins = getAdminUsers();
	$roles = ['Admin', 'Author'];				
?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
	<title>Администрирование | Управление пользователями</title>
</head>
<body>
	<!-- Навигация для администраторов -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>
	<div class="container content">
		<!-- Левое меню -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>
		<!-- Форма создания и редактирования  -->
		<div class="action">
			<h1 class="page-title">Создать/редактировать администратора</h1>

			<form method="post" action="<?php echo BASE_URL . 'admin/users.php'; ?>" >

				<!-- Обработка ошибок -->
				<?php include(ROOT_PATH . '/includes/errors.php') ?>

				<!-- Проверка на ID при редактировании -->
				<?php if ($isEditingUser === true): ?>
					<input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">
				<?php endif ?>

				<input type="text" name="username" value="<?php echo $username; ?>" placeholder="Имя пользователя">
				<input type="email" name="email" value="<?php echo $email ?>" placeholder="Email">
				<input type="password" name="password" placeholder="Пароль">
				<input type="password" name="passwordConfirmation" placeholder="Подтверждение пароля">
				<select name="role">
					<option value="" selected disabled>Назначить роль</option>
					<?php foreach ($roles as $key => $role): ?>
						<option value="<?php echo $role; ?>"><?php echo $role; ?></option>
					<?php endforeach ?>
				</select>

				<!-- При редактировании пользователя кнопка "обновить" -->
				<?php if ($isEditingUser === true): ?> 
					<button type="submit" class="btn" name="update_admin">Обновить</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_admin">Сохранить пользователя</button>
				<?php endif ?>
			</form>
		</div>
		<!-- // Форма создания и редактирования -->

		<!-- Записи из БД-->
		<div class="table-div">
			<!-- Уведомление -->
			<?php include(ROOT_PATH . '/admin/includes/messages.php') ?>

			<?php if (empty($admins)): ?>
				<h1>В БД нет администраторов.</h1>
			<?php else: ?>
				<table class="table">
					<thead>
						<th>N</th>
						<th>Администратор</th>
						<th>Роль</th>
						<th colspan="2">Действие</th>
					</thead>
					<tbody>
					<?php foreach ($admins as $key => $admin): ?>
						<tr>
							<td><?php echo $key + 1; ?></td>
							<td>
								<?php echo $admin['username']; ?>, &nbsp;
								<?php echo $admin['email']; ?>	
							</td>
							<td><?php echo $admin['role']; ?></td>
							<td>
								<a class="fa fa-pencil btn edit"
									href="users.php?edit-admin=<?php echo $admin['id'] ?>">
								</a>
							</td>
							<td>
								<a class="fa fa-trash btn delete" 
								    href="users.php?delete-admin=<?php echo $admin['id'] ?>">
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