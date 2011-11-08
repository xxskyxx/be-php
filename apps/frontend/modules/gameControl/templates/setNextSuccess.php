<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_teamState->Game->name, 'game/show?id='.$_teamState->game_id),
    link_to('Управление', 'gameControl/pilot?id='.$_teamState->game_id)
))
?>
<h2>Назначение следующего задания команде <?php echo $_teamState->Team->name ?></h2>

<div class="info">Если задания нет в списке, значит команда уже знает его.</div>
<div class="indent">Выберите одно из заданий (нажмите на ссылку):</div>
<ul>
  <li><span class="warnAction"><?php echo link_to('Отменить&nbsp;назначение (будет использован ИИ, если он включен для этой команды)', 'gameControl/setNext?teamState='.$_teamState->id.'&taskId=0&returl='.$_retUrl, array ('method' => 'post')); ?></span></li>
  <?php foreach ($_availableTasks as $task): ?>
  <li>
    <?php
    $htmlLink = link_to($task->name, 'gameControl/setNext?teamState='.$_teamState->id.'&taskId='.$task->id.'&returl='.$_retUrl, array ('method' => 'post'));
    echo ($task->locked)
        ? decorate_span('danger', $htmlLink.',&nbsp;заблокировано')
        : decorate_span('info', $htmlLink);
    ?>
  </li>
  <?php endforeach; ?>
</ul>