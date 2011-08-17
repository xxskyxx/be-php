<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($teamState->Game->name, 'game/show?id='.$teamState->game_id),
    link_to('Состояние', 'gameStats/status?id='.$teamState->game_id)
))
?>
<h2>Назначение следующего задания команде <?php echo $teamState->Team->name ?></h2>

<div class="info">Если задания нет в списке, значит команда уже знает его.</div>
<div class="indent">Выберите одно из заданий (нажмите на ссылку):</div>
<ul>
  <li><span class="warnAction"><?php echo link_to('Отменить&nbsp;назначение (будет использован ИИ, если он включен для этой команды)', 'gameStats/setNext?teamState='.$teamState->id.'&taskId=0&returl='.$retUrl, array ('method' => 'post')); ?></span></li>
  <?php foreach ($tasks as $task): ?>
  <li><?php echo link_to($task->name, 'gameStats/setNext?teamState='.$teamState->id.'&taskId='.$task->id.'&returl='.$retUrl, array ('method' => 'post')); ?></li>
  <?php endforeach; ?>
</ul>