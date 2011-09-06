<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($game->name, 'game/show?id='.$game->id)
))
?>

<h2>Результаты игры <?php echo $game->name ?></h2>
<?php if ($game->status < GAME::GAME_ARCHIVED): ?>
<p>
  <span class="safeAction"><?php echo link_to('Обновить (без пересчета)', 'gameStats/report?id='.$game->id) ?></span>
</p>
<?php endif ?>

<h3>Итоги</h3>
<?php include_partial('results', array('game' => $game)) ?>

<h3>Телеметрия</h3>
<?php include_partial('report', array('game' => $game)) ?>
