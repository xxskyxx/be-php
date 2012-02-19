<div>
  <span class="safeAction"><?php echo link_to('Обновить', 'taskState/task?id='.$_taskState->id); ?></span>
</div>

<?php $teamState = $_taskState->TeamState; ?>

<h2><?php echo $teamState->Game->name ?></h2>
<h3><?php echo $teamState->Team->name ?></h3>

<?php if ($_taskState->status == TaskState::TASK_GIVEN): ?>
<p>
  Вашей команде назначено задание, ожидайте его старта.
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

<?php elseif ($_taskState->status == TaskState::TASK_STARTED): ?>
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

<?php elseif ($_taskState->status == TaskState::TASK_ACCEPTED): ?>
<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
$sessionIsPlayer = $_taskState->TeamState->Team->isPlayer($sessionWebUser);
$sessionIsLeader = $_taskState->TeamState->Team->isLeader($sessionWebUser);
$sessionIsGameManager = $_taskState->TeamState->Game->canBeManaged($sessionWebUser);

include_component('taskState', 'answersForTeam', array('taskStateId' => $_taskState->id));

if ($sessionIsPlayer || $sessionIsGameManager)
{
  include_partial('taskState/taskAnswerPostedForm', array('form' => new SimpleAnswerForm, 'id' => $_taskState->id, 'retUrl' => Utils::encodeSafeUrl(url_for('taskState/task?id='.$_taskState->id))));
}
include_partial('taskDefine', array('taskState' => $_taskState));
if ($sessionIsLeader || $sessionIsGameManager)
{
  include_partial('taskLeaderTools', array('taskState' => $_taskState));
}
include_partial('taskStats', array('taskState' => $_taskState));
?>

<?php elseif ($_taskState->status == TaskState::TASK_CHEAT_FOUND): ?>
<?php include_component('taskState', 'answersForTeam', array('taskStateId' => $_taskState->id)) ?>
<div class="danger">
  <p>
    Задание дисквалифицировано. Вы больше не можете вводить ответы.
  </p>
</div>
<?php
include_partial('taskDefine', array('taskState' => $_taskState));
include_partial('taskStats', array('taskState' => $_taskState));
?>

<?php elseif ($_taskState->status >= TaskState::TASK_DONE): ?>
<p>
  <span class="info">Задание завершено.</span> <span class="safeAction"><?php echo link_to('Перейти к следующему заданию', 'teamState/task?id='.$teamState->id); ?></span>
</p>
<?php include_partial('taskDefine', array('taskState' => $_taskState)) ?>

<?php else: ?>
<div class="danger">С вашим заданием творится что-то непонятное. Обратитесь к организаторам.</div>

<?php endif; ?>
