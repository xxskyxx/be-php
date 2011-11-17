<?php if ($sf_user->isAuthenticated()): ?>
<div class="siteMap">
  <ul>
    <li><?php echo link_to('Главная', 'home/index') ?></li>
    <li><?php echo link_to('Команды', 'team/index') ?></li>
    <li><?php echo link_to('Игры', 'game/index') ?></li>
    <?php
    include ('customization/menuItemsCommon.php');
    include ('customization/menuItemsAuth.php');
    ?>
  </ul>
</div>
<?php endif; ?>
