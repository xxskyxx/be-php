<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_teamState->Game->name, 'game/show?id='.$_teamState->game_id),
    link_to('Управление', 'gameControl/pilot?id='.$_teamState->game_id)
))
?>
<h2>Назначение следующего задания команде <?php echo $_teamState->Team->name ?></h2>

<?php if ($_teamState->getCurrentTaskState()): ?>
<div class="warn">
  Для обоснованного выбора следующего задания рекомендуется дождаться завершения командой текущего задания,
  так как указанная ниже информация о заданиях может существенно измениться.
</div>
<?php endif ?>

<p>
  <?php if ($_teamState->ai_enabled): ?>
  <span class="warnAction"><?php echo link_to('Использовать автовыбор', 'gameControl/setNext?teamState='.$_teamState->id.'&taskId=0&returl='.$_retUrl, array ('method' => 'post')); ?></span>
  <?php else: ?>
  <?php   if ($_teamState->task_id > 0): ?>
  <span class="warnAction"><?php echo link_to('Отменить выбор назначенного задания', 'gameControl/setNext?teamState='.$_teamState->id.'&taskId=0&returl='.$_retUrl, array ('method' => 'post')); ?></span>
  <?php   endif ?>
  <?php endif ?>
</p>

<div class="indent">Для выбора задания нажмите на ссылку с его названием.</div>
<div class="info">В скобках возле задания указан его текущий приоритет.</div>

<?php if ($_tasksInSequenceManual->count() > 0): ?>
<h3>Задания, доступные для выбора вручную:</h3>
<ul>
  <?php foreach ($_tasksInSequenceManual as $task): ?>
  <li>
    <?php
    echo decorate_span('info', link_to($task->name, 'gameControl/setNext?teamState='.$_teamState->id.'&taskId='.$task->id.'&returl='.$_retUrl, array ('method' => 'post')));
    $priority = $_teamState->getPriorityOfTask($task->getRawValue());
    echo ' ('.(is_bool($priority) ? 'не определен' : decorate_number($priority)).')';
    ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif ?>

<?php if ($_tasksInSequence->count() > 0): ?>
<h3>Задания, доступные согласно логике переходов:</h3>
<ul>
  <?php foreach ($_tasksInSequence as $task): ?>
  <li>
    <?php
    echo decorate_span('info', link_to($task->name, 'gameControl/setNext?teamState='.$_teamState->id.'&taskId='.$task->id.'&returl='.$_retUrl, array ('method' => 'post')));
    $priority = $_teamState->getPriorityOfTask($task->getRawValue());
    echo ' ('.(is_bool($priority) ? 'не определен' : decorate_number($priority)).')';
    ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif ?>

<?php if ($_tasksNonSequence->count() > 0): ?>
<h3>Задания, <span class="warn">нарушающие логику переходов:</span></h3>
<ul>
  <?php foreach ($_tasksNonSequence as $task): ?>
  <li>
    <?php
    echo decorate_span('warn', link_to($task->name, 'gameControl/setNext?teamState='.$_teamState->id.'&taskId='.$task->id.'&returl='.$_retUrl, array ('method' => 'post', 'confirm' => 'Вы уверены, что хотите выдать команде '.$_teamState->Team->name.' задание '.$task->name.', которое недоступно по логике переходов ?')));
    $priority = $_teamState->getPriorityOfTask($task->getRawValue());
    echo ' ('.(is_bool($priority) ? 'не определен' : decorate_number($priority)).')';
    ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif ?>

<?php if ($_tasksLocked->count() > 0): ?>
<h3><span class="danger">Заблокированные</span> задания:</h3>
<ul>
  <?php foreach ($_tasksLocked as $task): ?>
  <li>
    <?php
    echo decorate_span('danger', link_to($task->name, 'gameControl/setNext?teamState='.$_teamState->id.'&taskId='.$task->id.'&returl='.$_retUrl, array ('method' => 'post', 'confirm' => 'Вы уверены, что хотите выдать команде '.$_teamState->Team->name.' заблокированное задание '.$task->name.' ?')));
    $priority = $_teamState->getPriorityOfTask($task->getRawValue());
    echo ' ('.(is_bool($priority) ? 'не определен' : decorate_number($priority)).')';
    ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif ?>