<?php if (isset($_SESSION['user']['username'])) { ?>

	<?php $comments = getCommentsByPostSlug($post['slug']); ?>

	<div class="comment-wrapper">
		<h2>Комментарии</h2>
		<form name="comment" method="post" action="<?php echo BASE_URL . 'single_post.php?post-slug=' . $post['slug']; ?>" >
			<input type="hidden" name="post_id" value="<?php echo $post['slug']; ?>">
			<input type="hidden" name="user_id" value="<?php echo $_SESSION['user']['id']; ?>">
			<textarea name="comment_body" class="text-input comment-input" style="width:300px; height:150px;"></textarea>
			<button type="submit" name="comment_post" class="btn comment-btn">
				<i class="fa fa-comment"></i> Отправить комментарий
			</button>
		</form>
		<hr>
		<!-- Если ошибка -->
		<?php include(ROOT_PATH . '/includes/errors.php') ?>
		<!-- Отображение всех комментов -->
		<?php foreach ($comments as $comment): ?>
			<div class="comment">
				<div class="comment-header">
					<span class="username"><?php echo getUserNicknameById($comment['user_id']); ?></span>
					<span class="time"><?php echo date("d.m.Y H:i:s ", strtotime($comment["created_at"])); ?></span>
				</div>
				<div class="comment-body">
					<?php echo $comment['comm']; ?>
				</div>
				<!--если пользователь администратор или автор комментария, кнопка удаления-->
				<?php if ($_SESSION['user']['role'] == "Admin" || $_SESSION['user']['id'] == $comment['user_id']): ?>
					<div class="comment-footer">
						<a class="delete-comment-btn" href="<?php echo BASE_URL . 'single_post.php?post-slug=' . $post['slug'] . '&del-comm-id=' . $comment['id']; ?>">
							<i class="fa fa-trash"></i> Удалить
						</a>
					</div>
				<?php endif ?>
			</div>
		<?php endforeach ?>
	</div>
	
<?php } ?>