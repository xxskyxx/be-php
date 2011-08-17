<?php
  render_breadcombs(array(
      link_to('Игры', 'game/index'),
      link_to($game->name, 'game/show?id='.$game->id)
  ))
?>

<h2>Подача командной заявки на игру <?php echo $game->name ?></h2>

<p>
  <span class="info">Показаны только те команды, от имени которых Вы можете подать заявку</span>, и которые еще не регистрировались на игру.
</p>
<p>
  Выберите одну из команд (нажмите на ссылку):
</p>

<ul>
<?php foreach ($teamList as $team): ?>
  <li>
    <?php echo link_to($team->name, 'game/postJoin'.'?id='.$game->id.'&teamId='.$team->id.'&returl='.$retUrl, array ('method' => 'post')); ?>
  </li>
<?php endforeach; ?>
</ul>