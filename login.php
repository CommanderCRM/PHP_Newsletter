<?php  include('config.php'); ?>
<?php  include('includes/registration_login.php'); ?>
<?php  include('includes/head_section.php'); ?>
	<title>Лента Кривошеина | Вход </title>
</head>
<body>
<div class="container">
	<!-- Навигация -->
	<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
	<!-- // Навигация -->

	<div style="width: 40%; margin: 20px auto;">
		<form method="post" action="login.php" >
			<h2>Вход</h2>
			<?php include(ROOT_PATH . '/includes/errors.php') ?>
			<input type="text" name="username" value="<?php echo $username; ?>" value="" placeholder="Имя пользователя">
			<input type="password" name="password" placeholder="Пароль">
			<button type="submit" class="btn" name="login_btn">Вход</button>
			<p>
				Еще не зарегистрированы? <a href="register.php">Регистрация</a>
			</p>
		</form>
	</div>
</div>
<!-- // Контейнер -->

<!-- Низ -->
	<?php include( ROOT_PATH . '/includes/footer.php'); ?>
<!-- // Низ -->