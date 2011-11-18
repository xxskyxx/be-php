<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_game->name, 'game/show?id='.$_game->id),
    'Управление'
));

$retUrlRaw = Utils::encodeSafeUrl(url_for('gameControl/sturman?id='.$_game->id));
include_partial('header', array(
    '_game' => $_game,
    '_isManager' => $_isManager,
    '_retUrlRaw' => $retUrlRaw,
    '_activeTab' => 'sturman'));
?>

<div class="tabSheet">

  <h3>Карта игровой ситуации</h3>
  <table cellspacing="0">
    <thead>
      <tr>
        <th style="font-weight: bold">Команда</th>
        <?php foreach ($_tasks as $task): ?>
        <th>
          <?php
          if ($task->locked)
          {
            $class = 'danger';
          }
          elseif ($task->getNotDoneTaskStates()->count() == 0)
          {
            $class = 'info';
          }
          elseif ($task->isFilled())
          {
            $class = 'warn';
          }
          else
          {
            $class = 'indent';
          }
          echo decorate_div($class, $task->name);
          ?>
        </th>
        <?php endforeach ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($_teamStates as $teamState): ?>
      <tr>
        <td style="font-weight: bold">
          <?php echo $teamState->Team->name; ?>
        </td>
        <?php foreach ($_tasks->getRawValue() as $task): ?>
        <td style="text-align: center">
          <?php
          $knownTaskState = $teamState->findKnownTaskState($task);
          if ($knownTaskState)
          {
            echo decorate_div(
                    $knownTaskState->getHighlightClass(),
                    $knownTaskState->describeStatus()
                );
          }
          else
          {
            $priority = $teamState->getPriorityOfTask($task);
            echo ($priority !== false)
              ? decorate_number($priority)
              : '&nbsp;-&nbsp;';
          }
          ?>
        </td>
        <?php endforeach ?>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>

  <h3>Следующие задания</h3>
  <div class="complexList">
    <?php $odd = true ?>
    <?php foreach ($_teamStates as $teamState): ?>
    <div class="<?php echo $odd ? 'oddLine' : 'evenLine' ?>">
      <div class="cell">
        <?php echo link_to($teamState->Team->name, 'teamState/show?id='.$teamState->id).': ' ?>
      </div>
      <div class="cell">
        <div>
          <?php
          if ($teamState->task_id > 0)
          {
            $task = DCTools::recordById($_tasks->getRawValue(), $teamState->task_id);
            echo link_to($task->name, 'task/show?id='.$task->id, array('target' => 'new'));
          }
          else
          {
            echo $teamState->ai_enabled
                ? decorate_span('info', 'Автоматически')
                : decorate_span('warn', 'Не&nbsp;задано');
          }
          echo ' '.decorate_span('warnAction', link_to('Задать', 'gameControl/setNext?teamState='.$teamState->id.'&returl='.$retUrlRaw));
          ?>
        </div>
        <div class="comment">
          <?php
          $currentTaskState = $teamState->getLastKnownTaskState();
          if ($currentTaskState)
          {
            $task = DCTools::recordById($_tasks->getRawValue(), $currentTaskState->task_id);
            echo 'Сейчас: ';
            echo $task->name.' - '.$currentTaskState->describeStatus();
          }
          else
          {
            echo decorate_span('warn', 'Нет текущего задания');
          }
          ?>
        </div>
        <div class="comment">
          <?php
          $lastKnownTaskState = $teamState->getLastDoneTaskState();
          if ($lastKnownTaskState)
          {
            $task = DCTools::recordById($_tasks->getRawValue(), $lastKnownTaskState->task_id);
            echo 'Последнее завершенное: ';
            echo decorate_span($lastKnownTaskState->getHighlightClass(), $task->name.' - '.$lastKnownTaskState->describeStatus());
          }
          else
          {
            'Команда только что стартовала';
          }
          ?>
        </div>
      </div>
    </div>
    <?php $odd = ( ! $odd); ?>
    <?php endforeach ?>
  </div>

  <h3>Справка</h3>

  <div class="comment">

    <h4>Карта игровой ситуации</h4>
    <p>
      В первой строке таблицы указаны задания: занятые, <span class="info">свободные</span>, <span class="warn">заполненные</span> и <span class="danger">заблокированные</span>.
    </p>
    
    <h4>Следующие задания</h4>
    <p>
      Ссылка с названием команды - переход к настройкам команды.
    </p>
    <p>
      Ссылка с названием задания - переход к просмотру задания.
    </p>
    <p>
      Формат строки:
    </p>
    <div class="complexList">
      <div class="oddLine">
        <div class="cell">Название_Команды: </div>
        <div class="cell">
          <div>Назначенное_следующее_задание</div>
          <div>Сейчас: Текущее_задание - Состояние_задания</div>
          <div>Последнее завершенное: Последнее_завершенное_задание - Состояние_задания</div>
        </div>
      </div>
    </div>

  </div>

</div>
