<div class="menu">
  <ul>
    <?php if ($sf_user->isAuthenticated()): ?>
    <li><?php echo link_to('Команды', 'team/index') ?></li>
    <li><?php echo link_to('Игры', 'game/index') ?></li>
    <li><?php echo link_to('Пользователи', 'webUser/index') ?></li>
    <li><?php echo link_to('Модерирование', 'moderation/index') ?></li>
    <li><?php echo link_to('Выход', 'auth/logout') ?></li>
    <?php else: ?>
    <li><?php echo link_to('Вход', 'auth/login') ?></li>
    <li><?php echo link_to('Регистрация', 'auth/register') ?></li>
    <?php endif; ?>
  </ul>
</div>