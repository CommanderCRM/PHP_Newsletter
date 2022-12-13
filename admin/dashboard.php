<?php  include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<?php if (isset($_SESSION['user']) && (!in_array($_SESSION['user']['role'], ["Admin", "Author"]))): ?>
	<?php header('location: ' . BASE_URL . '/index.php'); ?>
	<?php $_SESSION['message'] = "Нет разрешений на просмотр страницы"; ?>
	<?php 
	echo '<script type="text/javascript">';
	echo 'alert("Нет разрешений на просмотр страницы")'; 
	echo '</script>';
	?>
<?php endif ?>
<?php if (!isset($_SESSION['user'])): ?>
	<?php header('location: ' . BASE_URL . '/index.php'); ?>
	<?php $_SESSION['message'] = "Нет разрешений на просмотр страницы"; ?>
	<?php 
	echo '<script type="text/javascript">';
	echo 'alert("Нет разрешений на просмотр страницы")'; 
	echo '</script>';
	?>
<?php endif ?>
	<title>Администрирование | Главная страница</title>
</head>
<body>
	<div class="header">
		<div class="logo">
			<a href="<?php echo BASE_URL .'admin/dashboard.php' ?>">
				<h1>Лента Кривошеина - Администрирование</h1>
			</a>
		</div>
		<?php if (isset($_SESSION['user'])): ?>
			<div class="user-info">
				<span><?php echo $_SESSION['user']['username'] ?></span> &nbsp; &nbsp; 
				<a href="<?php echo BASE_URL .'/index.php' ?>" class="mainpage-btn">На главную</a>
				<span>&nbsp;</span>
				<a href="<?php echo BASE_URL . '/logout.php'; ?>" class="logout-btn">Завершить сессию</a>
			</div>
		<?php endif ?>
	</div>
	<div class="container dashboard">
		<h1>Добро пожаловать</h1>
		<div class="stats">
			<a href="users.php" class="first">
				<?php $num_users = countAllUsers() ?>
				<span><?php echo $num_users ?></span> <br>
				<span>Количество пользователей</span>
			</a>
			<a href="posts.php">
				<?php $num_posts = countAllPosts() ?>
				<span><?php echo $num_posts ?></span> <br>
				<span>Опубликовано новостей</span>
			</a>
			<a>
				<?php $num_comments = countAllComments() ?>
				<span><?php echo $num_comments ?></span> <br>
				<span>Опубликовано комментариев</span>
			</a>
		</div>
		<br><br><br>
		<div class="buttons">
			<a href="users.php">Добавить пользователя</a>
			<a href="posts.php">Добавить новость</a>
		</div>
	</div>
</body>
</html>