<?php
/* Входные данные:
 * - $game - игра
 * - $backLinkEncoded - ссылка для обратных переходов из диалогов/действий кодированная
 * - $sessionIsManager - текущий пользователь - руководитель игры
 */
?>

<h3>Состояние заданий</h3>
<table cellspacing="0">
  <thead>
    <tr>
      <th rowspan="2">Команда</th>
      <th class="bottomWeakBorder">Задание, состояние&nbsp;(в&nbsp;...)</th>
      <th class="bottomWeakBorder"><?php echo ($sessionIsManager) ? 'Управление' : '&nbsp;' ?></th>
    </tr>
    <tr>
      <th colspan="2" style="text-align:left">Выдано, стартовало, прочтено, <span class="info">выполняется</span>, <span class="warn">простой</span>, окончание</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($game->teamStates as $teamState): ?>
    <?php   if ($currentTaskStatus = $teamState->getCurrentTaskState()): ?>
    <tr>
      <td rowspan="2" style="vertical-align:top">
        <?php echo link_to($teamState->Team->name, 'teamState/task?id='.$teamState->id, array('target' => 'new')) ?>
      </td>
      <td class="bottomWeakBorder" style="vertical-align:middle">
        <?php echo link_to($currentTaskStatus->Task->name, 'task/Show?id='.$currentTaskStatus->task_id, array('target' => 'new')) ?>,
        <?php echo $currentTaskStatus->describeStatus().'&nbsp;('.Timing::timeToStr($currentTaskStatus->task_last_update).')' ?>
      </td>
      <td class="bottomWeakBorder" style="vertical-align:middle">
        <?php if ($sessionIsManager): ?>
        <?php   if (($currentTaskStatus->status == TaskState::TASK_GIVEN)
                   && ($currentTaskStatus->Task->manual_start > 0)): ?>
        <span class="danger">Ручной старт!</span>
        <?php   endif; ?>

        <?php   if ($currentTaskStatus->status == TaskState::TASK_GIVEN): ?>
        <span class="warnAction"><?php echo Utils::buttonTo('Cтарт', 'taskState/start?id='.$currentTaskStatus->id.'&returl='.$backLinkEncoded, 'post', 'Дать старт заданию '.$currentTaskStatus->Task->name.' команды '.$teamState->Team->name.' ?'); ?></span>

        <?php   elseif ($currentTaskStatus->status == TaskState::TASK_STARTED): ?>
        <span class="safeAction"><?php echo Utils::buttonTo('Рестарт', 'taskState/restart?id='.$currentTaskStatus->id.'&returl='.$backLinkEncoded, 'post', 'Отменить старт задания '.$currentTaskStatus->Task->name.' команды '.$teamState->Team->name.' ?'); ?></span>
        <span class="dangerAction"><?php echo Utils::buttonTo('Прочесть', 'taskState/forceAccept?id='.$currentTaskStatus->id.'&returl='.$backLinkEncoded, 'post', 'Подтвердить просмотр задания '.$currentTaskStatus->Task->name.' командой '.$teamState->Team->name.' ?'); ?></span>
        <?php   endif; ?>

        <?php   if ($currentTaskStatus->status < TaskState::TASK_DONE): ?>
        <?php     if ($currentTaskStatus->status >= TaskState::TASK_ACCEPTED): ?>
        <span class="dangerAction"><?php echo Utils::buttonTo('Пропустить', 'taskState/skip?id='.$currentTaskStatus->id.'&returl='.$backLinkEncoded, 'post', 'Прропустить задание '.$currentTaskStatus->Task->name.' команды '.$teamState->Team->name.' ?'); ?></span>
        <?php     endif; ?>
        <span class="dangerAction"><?php echo Utils::buttonTo('Прекратить', 'teamState/abandonTask?id='.$teamState->id.'&returl='.$backLinkEncoded, 'post', 'Прекратить задание '.$currentTaskStatus->Task->name.' команды '.$teamState->Team->name.' ?'); ?></span>
        <?php   endif; ?>
        <?php else: ?>
&nbsp;
        <?php endif; ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <?php echo Timing::timeToStr($currentTaskStatus->given_at) ?>,
        <?php echo Timing::timeToStr($currentTaskStatus->started_at) ?>,
        <?php echo Timing::timeToStr($currentTaskStatus->accepted_at) ?>,
        <span class="info"><?php echo Timing::intervalToStr($currentTaskStatus->getTaskSpentTimeCurrent()) ?></span>,
        <span class="warn"><?php echo Timing::intervalToStr($currentTaskStatus->task_idle_time) ?></span>,
        <?php echo Timing::timeToStr($currentTaskStatus->getTaskStopTime()) ?>
      </td>
    </tr>
    <?php   else: ?>
    <tr>
      <td>
        <?php echo link_to($teamState->Team->name, 'teamState/task?id='.$teamState->id, array('target' => 'new')) ?>
      </td>
      <td colspan="2">
        <?php if ($teamState->task_id <= 0): ?>
        <span class="warn">Нет текущего задания</span>
        <?php else: ?>
        <span class="info">Нет текущего задания</span>, cледующее <?php echo link_to($teamState->Task->name, 'task/show?id='.$teamState->task_id, array('target' => 'new')) ?>
        <?php endif; ?>
      </td>
    </tr>
    <?php   endif; ?>
    <?php endforeach; ?>
  </tbody>
</table>

<h3>Состояние команд</h3>
<table cellspacing="0">
  <thead>
    <tr>
      <th>Команда</th>
      <th>Состояние&nbsp;(в&nbsp;...)</th>
      <th style="text-align:left">Старт, стартовала, <span class="info">играет</span>, финиширует</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($game->teamStates as $teamState): ?>
    <tr>
      <td>
        <?php echo link_to($teamState->Team->name, 'teamState/edit?id='.$teamState->id, array('target' => 'new')) ?>
      </td>
      <td>
        <?php echo $teamState->describeStatus().'&nbsp;('.Timing::timeToStr($teamState->team_last_update).')' ?>
      </td>
      <td>
        <?php echo Timing::timeToStr($teamState->getActualStartDateTime()); ?>,
        <?php echo Timing::timeToStr($teamState->started_at) ?>,
        <span class="info"><?php echo Timing::intervalToStr($teamState->getGameSpentTimeCurrent()) ?></span>,
        <?php echo Timing::timeToStr($teamState->getTeamStopTime()) ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h3>Состояние игры</h3>
<table cellspacing="0">
  <thead>
    <th>Состояние&nbsp;(в&nbsp;...)</th>
    <th>Старт&nbsp;в</th>
    <th>Стартовала</th>
    <th>Остановится</th>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $game->describeStatus().'&nbsp;('.Timing::timeToStr($game->game_last_update).')' ?></td>
      <td><?php echo Timing::timeToStr(Timing::strToDate($game->start_datetime)) ?></td>
      <td><?php echo Timing::timeToStr($game->started_at) ?></td>
      <td><?php echo ($game->started_at > 0) ? Timing::dateToStr($game->getGameStopTime()) : Timing::NO_TIME ?></td>
    </tr>
  </tbody>
</table>

<div class="comment">
  <h3>Справка</h3>
  <h4>Состояние заданий</h4>
  <ul>
    <li><span class="warnAction">Старт</span> - принудительно дать старт заданию.</li>
    <li>
      <div>
        <span class="safeAction">Рестарт</span> - отменить старт задания (не отменяет назначения задания).
      </div>
      <div>
        <span class="warn">Если команда прочитала задание, его старт не может быть отменен!</span>
      </div>
    </li>
    <li><span class="dangerAction">Прочесть</span> - вручную зафиксировать факт прочтения задания командой.</li>
    <li>
      <div>
        <span class="dangerAction">Пропустить</span> - пропустить задание от лица команды (команда его больше не получит).
      </div>
      <div>
        <span class="warn">Руководитель игры (в отличие от капитана) может пропустить задание в любом случае!</span>
      </div>
    </li>
    <li>
      <span class="dangerAction">Прекратить</span> - прекратить задание, действие зависит от состояния задания:
      <ul>
        <li>
          <div>
            команда не прочитала задание: оно будет снято без записи в итоги команды;
          </div>
          <div>
            <span class="warn">команда в будущем может снова получить это задание!</span>
          </div>
        </li>
        <li>
          <div>
            команда прочитала задание: оно останется в итогах команды с результатом "отменено";
          </div>
          <div>
            <span class="warn">команда это задание больше не получит, затраченное на него время не войдет в игровое время.</span>
          </div>
        </li>
      </ul>
    </li>
  </ul>  
</div>
  