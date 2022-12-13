<?php  include('config.php'); ?>
<!-- Регистрация и вход -->
<?php  include('includes/registration_login.php'); ?>

<?php include('includes/head_section.php'); ?>

<title>Лента Кривошеина | Регистрация </title>
</head>
<body>
<div class="container">
	<!-- Навигация -->
		<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
	<!-- // Навигация -->

	<div style="width: 40%; margin: 20px auto;">
		<form method="post" action="register.php" >
			<h2>Зарегистрируйтесь в ленте Кривошеина</h2>
			<?php include(ROOT_PATH . '/includes/errors.php') ?>
			<input  type="text" name="username" value="<?php echo $username; ?>"  placeholder="Имя пользователя">
			<input type="email" name="email" value="<?php echo $email ?>" placeholder="Email">
			<input type="password" name="password_1" placeholder="Пароль">
			<input type="password" name="password_2" placeholder="Подтверждение пароля">
			<button type="submit" class="btn" name="reg_user">Зарегистрироваться</button>
			<p>
				Уже зарегистрированы? <a href="login.php">Войти</a>
			</p>
		</form>
	</div>
</div>
<!-- // Контейнер -->
<!-- Низ -->
	<?php include( ROOT_PATH . '/includes/footer.php'); ?>
<!-- // Низ -->