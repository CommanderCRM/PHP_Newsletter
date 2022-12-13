<?php if (isset($_SESSION['user']) && (in_array($_SESSION['user']['role'], ["Admin", "Author"]))): ?>
    <?php header('location: ' . BASE_URL . '/admin/dashboard.php'); ?>
<?php endif ?>
<?php if (isset($_SESSION['user']) && (!in_array($_SESSION['user']['role'], ["Admin", "Author"]))): ?>
    <?php header('location: ' . BASE_URL . '/index.php'); ?>
    <?php $_SESSION['message'] = "Нет разрешений на просмотр страницы"; ?>
    <?php 
	echo '<script type="text/javascript">';
	echo ' alert("Нет разрешений на просмотр страницы")'; 
	echo '</script>';
	?>
<?php endif ?>