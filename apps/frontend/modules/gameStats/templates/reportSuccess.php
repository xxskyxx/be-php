<h2>Результаты игры <?php echo $game->name ?></h2>
<div>
  <span class="indentAction"><?php echo link_to('Перейти к управлению игрой '.$game->name, 'gameStats/status?id='.$game->id) ?></span>
</div>
<div>
  <span class="safeAction"><?php echo link_to('Обновить (без пересчета)', 'gameStats/report?id='.$game->id) ?></span>
</div>

<h3>Итоги</h3>
<?php include_partial('Results', array('game' => $game)) ?>

<h3>Телеметрия</h3>
<?php include_partial('Report', array('game' => $game)) ?>
