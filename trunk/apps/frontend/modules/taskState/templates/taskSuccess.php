<div>
  <span class="safeAction"><?php echo link_to('Обновить', 'taskState/task?id='.$taskState->id); ?></span>
</div>

<?php $teamState = $taskState->TeamState; ?>

<h2><?php echo $teamState->Game->name ?></h2>
<h3><?php echo $teamState->Team->name ?></h3>

<?php if ($taskState->status == TaskState::TASK_GIVEN): ?>
<p>
  <div>Вашей команде назначено задание, но его старт пока не разрешен.</div>
</p>
<p>
  Обновляйте страницу время от времени.
</p>
<p>
  Как только Вашему заданию будет дан старт, вы его увидите.
</p>
<p>
  <div class="info">Задание стартует только тогда, когда вы его в первый раз увидите.</div>
</p>
<p>
  <div class="info">Время ожидания не влияет на доступное игровое время.</div>
</p>

<?php elseif ($taskState->status == TaskState::TASK_STARTED): ?>
<p>
  Ваше задание стартовало.
</p>
<p>
  <div class="danger">Вы сейчас должны видеть свое задание, а не это сообщение. Обратитесь к организаторам.</div>
</p>

<?php elseif ($taskState->status == TaskState::TASK_ACCEPTED): ?>
<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
$sessionIsPlayer = $taskState->TeamState->Team->isPlayer($sessionWebUser);
$sessionIsLeader = $taskState->TeamState->Team->isLeader($sessionWebUser);
$sessionIsGameManager = $taskState->TeamState->Game->canBeManaged($sessionWebUser);

include_partial('TaskAnswers', array('taskState' => $taskState));
if ($sessionIsPlayer || $sessionIsGameManager)
{
  include_partial('taskState/TaskAnswerPostedForm', array('form' => new SimpleAnswerForm, 'id' => $taskState->id, 'retUrl' => Utils::encodeSafeUrl(url_for('taskState/task?id='.$taskState->id))));
}
include_partial('TaskDefine', array('taskState' => $taskState));
if ($sessionIsLeader || $sessionIsGameManager)
{
  include_partial('TaskLeaderTools', array('taskState' => $taskState));
}
include_partial('TaskStats', array('taskState' => $taskState));
?>

<?php elseif ($taskState->status == TaskState::TASK_CHEAT_FOUND): ?>
<?php include_partial('TaskAnswers', array('taskState' => $taskState)) ?>
<p>
  <div class="danger">Задание дисквалифицировано. Вы больше не можете вводить ответы.</div>
</p>
<?php
include_partial('TaskDefine', array('taskState' => $taskState));
include_partial('TaskStats', array('taskState' => $taskState));
?>

<?php elseif ($taskState->status >= TaskState::TASK_DONE): ?>
<p>
  <span class="info">Задание завершено.</span> <span class="safeAction"><?php echo link_to('Перейти к следующему заданию', 'teamState/task?id='.$teamState->id); ?></span>
</p>
<?php include_partial('TaskDefine', array('taskState' => $taskState)) ?>

<?php else: ?>
<div class="danger">С вашим заданием творится что-то непонятное. Обратитесь к организаторам.</div>

<?php endif; ?>