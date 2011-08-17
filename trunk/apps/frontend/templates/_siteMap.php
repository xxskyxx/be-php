<?php if ($sf_user->isAuthenticated()): ?>
<div class="siteMap">
  <?php
  echo decorate_div('siteMapItem', link_to('Главная', 'home/index'));
  echo decorate_div('siteMapItem', link_to('Команды', 'team/index'));
  echo decorate_div('siteMapItem', link_to('Игры', 'game/index'));
  ?>
</div>
<?php endif; ?>
