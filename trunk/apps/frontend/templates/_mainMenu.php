<div class="menu">
  <ul>
    <li><img src="/images/favicon.png" alt="[BE]" onClick="document.location='/home/index'" /></li>
    <?php if ($sf_user->isAuthenticated()): ?>
    <li><?php echo link_to('Главная', 'home/index') ?></li>
    <li><?php echo link_to('Команды', 'team/index') ?></li>
    <li><?php echo link_to('Игры', 'game/index') ?></li>
    <li><?php echo link_to('Пользователи', 'webUser/index') ?></li>
    <li><?php echo link_to('Личная', 'webUser/show?id='.$sf_user->getAttribute('id')) ?></li>
    <li><?php echo link_to('Выход', 'auth/logout') ?></li>
    <?php else: ?>
    <li><?php echo link_to('Вход', 'auth/login') ?></li>
    <li><?php echo link_to('Регистрация', 'auth/register') ?></li>
    <?php endif; ?>
  </ul>
</div>