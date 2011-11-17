<div>
  <span class="safeAction"><?php echo link_to('Обновить', 'taskState/task?id='.$taskState->id); ?></span>
</div>

<?php $teamState = $taskState->TeamState; ?>

<h2><?php echo $teamState->Game->name ?></h2>
<h3><?php echo $teamState->Team->name ?></h3>

<?php if ($taskState->status == TaskState::TASK_GIVEN): ?>
<p>
  Вашей команде назначено задание, но его старт пока не разрешен.
</p>
<p>
  Обновляйте страницу время от времени.
</p>
<p>
  Как только Вашему заданию будет дан старт, вы его увидите.
</p>
<div class="info">
  <p>
    Задание стартует только тогда, когда вы его в первый раз увидите.
  </p>
</div>
<div class="info">
  <p>
    Время ожидания не влияет на доступное игровое время.
  </p>
</div>

<?php elseif ($taskState->status == TaskState::TASK_STARTED): ?>
<p>
  Заданию разрешен старт.
</p>
<div class="danger">
  <p>
    Если Вы игрок: обратитесь к организаторам, так как Вы здесь должны видеть свое задание.
  </p>
</div>
<div class="warn">
  <p>
    Если Вы руководитель игры: для подтверждения прочтения этого задания командой нужно использовать ссылку "Прочесть" на странице управления игрой.
  </p>
</div>

<?php elseif ($taskState->status == TaskState::TASK_ACCEPTED): ?>
<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
$sessionIsPlayer = $taskState->TeamState->Team->isPlayer($sessionWebUser);
$sessionIsLeader = $taskState->TeamState->Team->isLeader($sessionWebUser);
$sessionIsGameManager = $taskState->TeamState->Game->canBeManaged($sessionWebUser);

include_partial('taskAnswers', array('taskState' => $taskState));
if ($sessionIsPlayer || $sessionIsGameManager)
{
  include_partial('taskState/taskAnswerPostedForm', array('form' => new SimpleAnswerForm, 'id' => $taskState->id, 'retUrl' => Utils::encodeSafeUrl(url_for('taskState/task?id='.$taskState->id))));
}
include_partial('taskDefine', array('taskState' => $taskState));
if ($sessionIsLeader || $sessionIsGameManager)
{
  include_partial('taskLeaderTools', array('taskState' => $taskState));
}
include_partial('taskStats', array('taskState' => $taskState));
?>

<?php elseif ($taskState->status == TaskState::TASK_CHEAT_FOUND): ?>
<?php include_partial('taskAnswers', array('taskState' => $taskState)) ?>
<div class="danger">
  <p>
    Задание дисквалифицировано. Вы больше не можете вводить ответы.
  </p>
</div>
<?php
include_partial('taskDefine', array('taskState' => $taskState));
include_partial('taskStats', array('taskState' => $taskState));
?>

<?php elseif ($taskState->status >= TaskState::TASK_DONE): ?>
<p>
  <span class="info">Задание завершено.</span> <span class="safeAction"><?php echo link_to('Перейти к следующему заданию', 'teamState/task?id='.$teamState->id); ?></span>
</p>
<?php include_partial('taskDefine', array('taskState' => $taskState)) ?>

<?php else: ?>
<div class="danger">С вашим заданием творится что-то непонятное. Обратитесь к организаторам.</div>

<?php endif; ?>
