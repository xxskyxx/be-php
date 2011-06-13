<?php
/* Входные данные:
 * - $game - игра
 * - $backLinkEncoded - ссылка для обратных переходов из диалогов/действий кодированная
 * - $sessionIsManager - текущий пользователь - руководитель игры
 */
?>

<h3>Текущие задания</h3>
<table cellspacing="0">
  <thead>
    <tr>
      <th>Команда</th>
      <th>Задание</th>
      <th>Подсказки</th>
      <th>Ответы</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($game->teamStates as $teamState): ?>
    <tr>
      <td>
        <?php echo link_to($teamState->Team->name, 'teamState/task?id='.$teamState->id, array('target' => 'new', 'confirm' => 'Просмотр задания равноценен его прочтению командой и запускает таймер задания. Вы точно хотите посмотреть задание?')) ?>
      </td>

      <?php if (($teamState->status == TeamState::TEAM_HAS_TASK)
                && ($currentTaskState = $teamState->getCurrentTaskState())): ?>
      <td>
        <span class="indentAction"><span class="<?php echo $currentTaskState->getHighlightClass() ?>"><?php echo link_to($currentTaskState->Task->name, 'task/show?id='.$currentTaskState->task_id, array('target' => 'new')); ?></span></span>
        <?php if ($currentTaskState->status == TaskState::TASK_GIVEN): ?>
        <?php   if ($currentTaskState->Task->manual_start): ?>
        <span class="info">Вручную</span>
        <?php   elseif ($currentTaskState->Task->isOverloadWarning()): ?>
        <span class="warn">Перегружено</span>
        <?php   endif; ?>
        <?php   if (!$currentTaskState->canBeStarted() && $sessionIsManager): ?>
        <span class="warnAction"><?php echo Utils::buttonTo('Старт', 'taskState/start?id='.$currentTaskState->id.'&returl='.$backLinkEncoded, 'post', 'Дать старт заданию '.$currentTaskState->Task->name.' команды '.$teamState->Team->name.' ?'); ?></span>
        <?php   endif; ?>
        <?php endif; ?>
      </td>
      <td>
        <?php include_partial('taskState/UsedTips', array('taskState' => $currentTaskState, 'withLink' => 'true')); ?>
      </td>
      <td>
        <?php if ($sessionIsManager && ($currentTaskState->status == TaskState::TASK_ACCEPTED)): ?>
        <div style="display:inline-block">
          <?php echo include_partial('taskState/TaskAnswerPostedForm', array('form' => new SimpleAnswerForm, 'id' => $currentTaskState->id, 'retUrl' => $backLinkEncoded)); ?>
        </div>
        <?php endif ?>
        <div style="display:inline-block">
          <?php include_partial('taskState/TaskAnswers', array('taskState' => $currentTaskState, 'compact' => true, 'describe' => true)); ?>
        </div>
      </td>
      <?php else: ?>
      <td colspan="3">
Нет задания
      </td>
      <?php endif; ?>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h3>Последовательность заданий</h3>
<table cellspacing="0">
  <thead>
    <th>Команда</th>
    <th>Сейчас</th>
    <th>Следующее</th>
  </thead>
  <tbody>
    <?php foreach ($game->teamStates as $teamState): ?>
    <tr>
      <td>
        <?php echo link_to($teamState->Team->name, 'teamState/edit?id='.$teamState->id, array('target' => 'new')) ?>
        <?php if ($teamState->ai_enabled): ?>
        <span class="info">ИИ</span>
        <?php endif; ?>        
      </td>
      
      <?php if ($teamState->status == TeamState::TEAM_WAIT_GAME): ?>
      <td>
        <span class="indent">Ждет начала игры</span>
      </td>

      <?php elseif ($teamState->status == TeamState::TEAM_WAIT_START): ?>
      <td>
        <span class="indent">Cтартует в <?php echo Timing::timeToStr($teamState->getActualStartDateTime())?></span>
      </td>

      <?php elseif ($teamState->status == TeamState::TEAM_WAIT_TASK): ?>
      <td>
        <?php if ($lastDoneTaskState = $teamState->getLastDoneTaskState()): ?>
        <span class="<?php echo $lastDoneTaskState->getHighlightClass() ?>">Завершила <?php echo link_to($lastDoneTaskState->Task->name, 'task/show?id='.$lastDoneTaskState->task_id) ?></span>
        <?php endif; ?>
      </td>

      <?php elseif ($teamState->status == TeamState::TEAM_HAS_TASK): ?>
      <?php   if ($currentTaskState = $teamState->getCurrentTaskState()): ?>
      <td>
        <div>
          <span class="<?php echo $currentTaskState->getHighlightClass() ?>">
            <?php
            echo link_to($currentTaskState->Task->name, 'task/show?id='.$currentTaskState->task_id, array('target' => 'new'));
            if ($currentTaskState->status == TaskState::TASK_ACCEPTED)
            {
              echo '&nbsp;идет до '.Timing::timeToStr($currentTaskState->getTaskStopTime());
            }
            else
            {
              echo '&nbsp;'.$currentTaskState->describeStatus();  
            }
            ?>
          </span>
        </div>
      </td>
      <?php   else: ?>
      <td>
        <span class="danger">У команды есть задание, но оно не найдено!</span>
      </td>
      <?php   endif; ?>
      
      <?php elseif ($teamState->status == TeamState::TEAM_FINISHED): ?>
      <td>
        <span class="info">Финишировала</span>
      </td>

      <?php else: ?>
      <td>
        <span class="danger">Неизвестное состояние!</span>
      </td>
      <?php endif; ?>        
        
      <td>
        <?php if ($sessionIsManager): ?>
        <span class="warnAction"><?php echo link_to('Задать', 'gameStats/setNext?teamState='.$teamState->id.'&returl='.$backLinkEncoded) ?></span>
        <?php endif; ?>
        <?php if ($teamState->task_id > 0): ?>
        <span class="indent"><?php echo link_to($teamState->Task->name, 'gameStats/setNext?teamState='.$teamState->id.'&returl='.$backLinkEncoded) ?></span>
        <?php else: ?>
        <?php   if ($teamState->ai_enabled): ?>
        <span class="info">Автопилот</span>
        <?php   else: ?>
        <span class="warn">Не&nbsp;задано</span>
        <?php   endif; ?>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="comment">
  <h3>Справка</h3>
  <h4>Текущие задания</h4>
  <ul>
    <li>Колонка "Команда" - ссылки ведут на страницы текущих заданий команд (на них организатор игры имеет полномочия аналогичные капитану соответствующей команды).</li>
    <li>
      <p>
        Колонка "Задание" - ссылки ведут в редактор задания.
      </p>
      <p>
        Кнопка <span class="warnAction">Старт</span> появляется в тех случаях, когда задание не может быть начато сразу (перегружено или требует ручного старта) и команда встала в очередь ожидания этого задания. Кнопка принудительно разрешает старт задания в подобной ситуации.
      </p>
    </li>
    <li>Колонка "Подсказки" - ссылки ведут в редакторы соответсвующей подсказки.</li>
    <li>Колонка "Ответы" - показывает ожидаемые ответы (названия), <span class="info">правильные</span>, <span class="warn">не проверенные</span> и <span class="danger">неверные</span>.</li>
  </ul>
  <p>
    Подробная информация о состоянии заданий отображается на вкладке <?php echo link_to('Бортмеханик', 'gameStats/status?id='.$game->id.'&seat=engineer') ?>.
  </p>  
  
  <h4>Последовательность заданий</h4>
  <p>
    ИИ будет выбирать следующее задание для команды только если оно еще не назначено, и только если у команды нет текущего задания.
  </p>
</div>