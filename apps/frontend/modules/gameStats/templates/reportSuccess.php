<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($game->name, 'game/show?id='.$game->id)
))
?>

<h2>Результаты игры <?php echo $game->name ?></h2>
<p>
  <span class="safeAction"><?php echo link_to('Обновить (без пересчета)', 'gameStats/report?id='.$game->id) ?></span>
</p>

<h3>Итоги</h3>
<?php include_partial('Results', array('game' => $game)) ?>

<h3>Телеметрия</h3>
<?php include_partial('Report', array('game' => $game)) ?>
