<h2>Назначение следующего задания команде <?php echo $teamState->Team->name ?></h2>

<div class="info">
  Если задания нет в списке, значит команда уже знает его.
</div>
<div class="indent">
  Выберите одно из заданий (нажмите на ссылку):
</div>
<div>
  <span class="warnAction"><?php echo link_to('Отменить&nbsp;назначение (будет использован ИИ, если он включен для этой команды)', 'gameStats/setNext?teamState='.$teamState->id.'&taskId=0&returl='.$retUrl, array ('method' => 'post')); ?></span>
</div>
<?php foreach ($tasks as $task): ?>
<div>
  <span class="safeAction"><?php echo link_to($task->name, 'gameStats/setNext?teamState='.$teamState->id.'&taskId='.$task->id.'&returl='.$retUrl, array ('method' => 'post')); ?></span>
</div>
<?php endforeach; ?>
