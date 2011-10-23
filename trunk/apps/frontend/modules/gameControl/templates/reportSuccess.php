<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_game->name, 'game/show?id='.$_game->id)
))
?>

<h2>Результаты игры <?php echo $_game->name ?></h2>
<?php if ($_game->status < GAME::GAME_ARCHIVED): ?>
<p>
  <span class="safeAction"><?php echo link_to('Обновить', 'gameControl/report?id='.$_game->id) ?></span>
</p>
<?php endif ?>

<h3>Итоги</h3>
<?php include_partial('results', array('_game' => $_game)) ?>

<h3>Телеметрия</h3>
<?php include_partial('report', array('_game' => $_game)) ?>
