<?php
  render_breadcombs(array(
      link_to('Игры', 'game/index')
  ))
?>

<h2>Подача заявки на создание игры</h2>

<p>
  <span class="info">Показаны только те команды, от имени которых Вы можете подать заявку.</span>
</p>
<p>
  Выберите команду, которая организует игру (нажмите на ссылку):
</p>

<ul>
<?php foreach ($_teams as $team): ?>
  <li><?php echo link_to($team->name, 'gameCreateRequest/new?teamId='.$team->id); ?></li>
<?php endforeach; ?>
</ul>