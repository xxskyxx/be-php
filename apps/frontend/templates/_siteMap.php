<div class="siteMap">
  <?php if ($sf_user->isAuthenticated()): ?>
  <ul>
    <li><?php echo link_to('Главная', 'home/index') ?></li>
    <li><?php echo link_to('Команды', 'team/index') ?></li>
    <li><?php echo link_to('Игры', 'game/index') ?></li>
    <?php
    include ('customization/menuItemsCommon.php');
    include ('customization/menuItemsAuth.php');
    ?>
  </ul>
  <?php else: ?>
  <ul>
    <li><?php echo link_to('Главная', 'home/index') ?></li>
    <?php
    include ('customization/menuItemsNonAuth.php');
    include ('customization/menuItemsCommon.php');
    ?>
  </ul>
  <?php endif; ?>
</div>
