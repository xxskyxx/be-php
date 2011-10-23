<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_game->name, 'game/show?id='.$_game->id),
    'Управление'
));
    
$retUrlRaw = Utils::encodeSafeUrl(url_for('gameControl/stuart?id='.$_game->id));
include_partial('header', array(
    '_game' => $_game,
    '_isManager' => $_isManager,
    '_retUrlRaw' => $retUrlRaw,
    '_activeTab' => 'stuart'));
?>

<div class="tabSheet">
  
  <h3>Текущие результаты команд</h3>
  <p>
    <?php include_partial('results', array('_game' => $_game)) ?>
  </p>
  <p>
    <?php echo link_to('Просмотр полной телеметрии', 'gameControl/report?id='.$_game->id, array('target' => 'new')) ?>
  </p>

</div>