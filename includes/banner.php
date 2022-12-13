<?php if (isset($_SESSION['user']['username'])) { ?>
	<div class="logged_in_info">
		<span>Здравствуйте, <?php echo $_SESSION['user']['username'] ?></span>
		|
		<?php if (in_array($_SESSION['user']['role'], ["Admin", "Author"])): ?>
			<a href="admin/dashboard.php">Администрирование</a>
			|
		<?php endif ?>
		<span><a href="logout.php">Выйти</a></span>
	</div>
<?php }else{ ?>
	<div class="banner">
		<div class="welcome_msg">
			<h1>Цитата месяца</h1>
			<p> 
			Мы намерены следовать за этими авторами, <br> 
			когда их свидетельства совпадают, <br> 
			но, если они между собою расходятся, <br>
			будем передавать приводимые ими сведения под их именами. <br>
				<span>~ Публий Корнелий Тацит</span>
			</p>
			<a href="register.php" class="btn">Регистрация</a>
		</div>
		<div class="login_div">
			<form action="<?php echo BASE_URL . 'index.php'; ?>" method="post" >
				<h2>Вход</h2>
				<div style="width: 60%; margin: 0px auto;">
					<?php include(ROOT_PATH . '/includes/errors.php') ?>
				</div>
				<input type="text" name="username" placeholder="Имя пользователя">
				<input type="password" name="password"  placeholder="Пароль"> 
				<button class="btn" type="submit" name="login_btn">Войти</button>
			</form>
		</div>
	</div>
<?php } ?>