<?php
  render_breadcombs(array(
      link_to('Команды', 'team/index'),
      link_to($team->name, 'team/show?id='.$team->id)
  ))
?>

<h2>Регистрация игрока в команду <?php echo $team->name ?></h2>

<div>
  <span class="info">Если пользователя нет в списке</span>, значит он подал заявку или входит в состав команды.
</div>
<?php if ($team->teamPlayers->count() == 0): ?>
<div>
  <span class="warn">Пользователь будет назначен капитаном</span>, так как в команде еще нет игроков.
</div>
<?php endif; ?>
<div class="spaceAround">
  Выберите одного из пользователей (нажмите на ссылку с именем):
</div>
<ul>
  <?php foreach ($webUsers as $webUser): ?>
  <li>
    <?php echo link_to($webUser->login, 'team/setPlayer'.'?id='.$team->id.'&userId='.$webUser->id.'&returl='.$retUrl, array ('method' => 'post')); ?>
  </li>
  <?php endforeach; ?>  
</ul>
