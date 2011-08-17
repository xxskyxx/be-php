<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($game->name, Utils::decodeSafeUrl($retUrl))
))
?>

<h2>Регистрация команды на игру <?php echo $game->name ?></h2>

<p>
  <span class="info">Если команды нет в списке</span>, значит она уже заявилась или зарегистрирована на эту игру.
</p>
<p>
  Выберите одну из команд (нажмите на ссылку):
</p>
<ul>
  <?php foreach ($teamList as $team): ?>
  <li>
    <?php echo link_to($team->name, 'game/addTeam'.'?id='.$game->id.'&teamId='.$team->id.'&returl='.$retUrl,array ('method' => 'post')); ?>
  </li>
  <?php endforeach; ?>
</ul>