<?php if ($sf_user->isAuthenticated()): ?>
<div class="loginDisplay">
  Добро пожаловать, <?php echo link_to($sf_user->getAttribute('login'), 'webUser/show?id=' . $sf_user->getAttribute('id')); ?>! <?php echo link_to('Выйти', 'auth/logout'); ?>
</div>
<?php else: ?>
<div class="loginDisplay">
  <?php echo link_to('Регистрация', 'auth/register'); ?> <?php echo link_to('Вход', 'auth/login'); ?>
</div>
<?php endif; ?>
