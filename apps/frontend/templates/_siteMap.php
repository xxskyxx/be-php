<?php if ($sf_user->isAuthenticated()): ?>
<div class="siteMap">
  <div class="middleVertical">
    <img src="/images/favicon.png" alt="[BE]" onClick="document.location='/home/index'" />
  </div>
  <div class="middleVertical">
    <?php   
      echo link_to('Главная', 'home/index');
      echo ' | ';
      echo link_to('Команды', 'team/index');
      echo ' | ';
      echo link_to('Игры', 'game/index');
    ?>
  </div>
</div>
<?php endif; ?>
