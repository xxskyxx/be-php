<?php
  render_breadcombs(array(
      link_to('Команды', 'team/index'),
      link_to($team->name, 'team/show?id='.$team->id)
  ))
?>

<h2>Регистрация игрока в команду <?php echo $team->name ?></h2>

<p>
  <span class="info">Если человека нет в списке</span>, значит он подал заявку или входит в состав команды.
</p>
<?php if ($team->teamPlayers->count() == 0): ?>
<p>
  <span class="warn">Выбранный игрок будет назначен капитаном</span>, так как в команде еще нет игроков.
</p>
<?php endif; ?>
<p>
  Выберите одно из имен:
</p>
<ul>
  <?php foreach ($webUsers as $webUser): ?>
  <li>
    <?php echo link_to($webUser->login, 'team/setPlayer'.'?id='.$team->id.'&userId='.$webUser->id.'&returl='.$retUrl, array ('method' => 'post')); ?>
  </li>
  <?php endforeach; ?>  
</ul>
