<div class="header">
	<div class="logo">
		<a href="<?php echo BASE_URL .'admin/dashboard.php' ?>">
			<h1>Лента Кривошеина - Администрирование</h1>
		</a>
	</div>
	<div class="user-info">
		<span><?php echo $_SESSION['user']['username'] ?></span>
		&nbsp; &nbsp;
		<a href="<?php echo BASE_URL .'/index.php' ?>" class="mainpage-btn">На главную</a>
		<span>&nbsp;</span>
		<a href="<?php echo BASE_URL .'logout.php' ?>" class="logout-btn">Завершить сессию</a>
	</div>
</div>