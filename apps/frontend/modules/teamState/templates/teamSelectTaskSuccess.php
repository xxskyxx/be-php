<?php
include_partial('header', array('teamState' => $_teamState));
$retUrlRaw = Utils::encodeSafeUrl(url_for('teamState/task?id='.$_teamState->id));
?>

<div class="warn">
  <p>
    Ваша команда может выбрать себе следующее задание:
  </p>
</div>

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
<div class="info">
  <p>
    Выбрать следующее задание может только капитан команды.
  </p>
</div>
<?php endif ?>

<div class="info">
  <p>
    Задание стартует только тогда, когда Вы его в первый раз увидите.
  </p>
</div>

<div class="info">
  <p>
    Время ожидания не влияет на доступное игровое время.
  </p>
</div>