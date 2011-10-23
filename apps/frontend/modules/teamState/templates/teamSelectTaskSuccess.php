<?php
include_partial('header', array('teamState' => $_teamState));
$retUrlRaw = Utils::encodeSafeUrl(url_for('teamState/task?id='.$_teamState->id));
?>

<p>
  <div class="warn">Ваша команда может выбрать себе следующее задание:</div>
</p>

<ul>
<?php foreach ($_availableTasksManual as $task): ?>
  <li>
  <?php if ($_isLeader): ?>
    <span class="safeAction"><?php echo link_to($task->public_name, 'gameControl/setNext?teamState='.$_teamState->id.'&taskId='.$task->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Вы уверены, что хотите приступить к выполнению задания "'.$task->public_name.'"?')) ?></span>
  <?php else: ?>
    <?php echo $task->public_name ?>
  <?php endif ?>
  </li>
<?php endforeach ?>
</ul>

<?php if ( ! $_isLeader): ?>
<p>
  <div class="info">Выбрать следующее задание может только капитан команды.</div>
</p>
<?php endif ?>

<p>
  <div class="info">Задание стартует только тогда, когда Вы его в первый раз увидите.</div>
</p>

<p>
  <div class="info">Время ожидания не влияет на доступное игровое время.</div>
</p>