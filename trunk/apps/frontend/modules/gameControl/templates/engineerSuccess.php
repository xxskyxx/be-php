<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_game->name, 'game/show?id='.$_game->id),
    'Управление'
));

$retUrlRaw = Utils::encodeSafeUrl(url_for('gameControl/engineer?id='.$_game->id));
include_partial('header', array(
    '_game' => $_game,
    '_isManager' => $_isManager,
    '_retUrlRaw' => $retUrlRaw,
    '_activeTab' => 'engineer'));
?>

<div class="tabSheet">

  <h3>Состояние текущих заданий</h3>
  <div class="complexList">
    <?php $odd = true ?>
    <?php foreach ($_teamStates as $teamState): ?>
    <div class="<?php echo $odd ? 'oddLine' : 'evenLine' ?>">
      <div class="cell">
        <?php echo $teamState->Team->name.': ' ?>
      </div>
      <div class="cell">
        <div>
          <?php $currentTaskState = $teamState->getCurrentTaskState() ?>
          <?php
          if ($currentTaskState)
          {
            $task = DCTools::recordById($_tasks->getRawValue(), $currentTaskState->task_id);
            echo $task->name.' - '.$currentTaskState->describeStatus().'('.Timing::timeToStr($currentTaskState->task_last_update).')';
          }
          else
          {
            echo decorate_span('warn', 'Нет&nbsp;задания');
          }
          ?>
        </div>
        <div>
          <?php
          if ($currentTaskState)
          {
            echo decorate_span('indent', Timing::timeToStr($currentTaskState->given_at)).', ';
            echo decorate_span('indent', Timing::timeToStr($currentTaskState->started_at)).', ';
            echo decorate_span('info', Timing::timeToStr($currentTaskState->accepted_at)).', ';
            echo decorate_span('warn', Timing::intervalToStr($currentTaskState->task_idle_time)).', ';
            echo decorate_span('indent', Timing::timeToStr($currentTaskState->done_at)).', ';
            echo decorate_span('indent', Timing::intervalToStr($currentTaskState->getTaskSpentTimeCurrent()));
          }
          echo '&nbsp;';  
          ?>
        </div>
        <div>
          <?php
          if ($currentTaskState)
          {
            foreach ($currentTaskState->usedTips as $usedTip)
            {
              $tip = DCTools::recordById($_usedTips->getRawValue(), $usedTip->id)->Tip;
              echo $tip->name.'('.Timing::timeToStr($usedTip->used_since).') ';
            }
          }
          echo '&nbsp;';
          ?>
        </div>
        <div>
          <?php
          if ($currentTaskState)
          {
            foreach ($currentTaskState->postedAnswers as $postedAnswer)
            {
              echo $postedAnswer->value.'('.$postedAnswer->WebUser->login.'@'.Timing::timeToStr($postedAnswer->post_time).') ';
            }
          }
          echo '&nbsp;';
          ?>
        </div>
      </div>
    </div>
    <?php $odd = ( ! $odd); ?>
    <?php endforeach ?>
  </div>

  <h3>Состояние команд</h3>
  <div class="complexList">
    <?php $odd = true ?>
    <?php foreach ($_teamStates as $teamState): ?>
    <div class="<?php echo $odd ? 'oddLine' : 'evenLine' ?>">
      <div class="cell">
        <?php echo $teamState->Team->name.': ' ?>
      </div>
      <div class="cell">
        <?php echo $teamState->describeStatus().'&nbsp;('.Timing::timeToStr($teamState->team_last_update).')' ?>
      </div>
    </div>
    <?php $odd = ( ! $odd); ?>
    <?php endforeach ?>
  </div>
  
  <h3>Состояние игры</h3>
  <div class="complexList">
    <div class="oddLine">
      <div class="cell">
        <?php echo $_game->describeStatus().'&nbsp;('.Timing::timeToStr($_game->game_last_update).')' ?>
      </div>
      <div class="cell">
        <?php
        echo decorate_span('indent', $_game->start_datetime).', ';
        echo decorate_span('info', Timing::timeToStr($_game->started_at)).', ';
        echo decorate_span('indent', $_game->stop_datetime).', ';
        echo decorate_span('indent', Timing::timeToStr($_game->finished_at));
        ?>
      </div>
    </div>
  </div>
  
  
  <h3>Справка</h3>

  <div class="comment">

    <h4>Состояние текущих заданий</h4>
    <p>
      Формат строки:
    </p>
    <div class="complexList">
      <div class="oddLine">
        <div class="cell">Название_Команды: </div>
        <div class="cell">
          <div>Название_задания - состояние_задания(На_момент)</div>
          <div>Выдано_в, Стартовало_в, <span class="info">Прочтено_в</span>, <span class="warn">Простой</span>, Завершено_в, Потрачено</div>
          <div>Подсказка1(Выдана_в) ... ПодсказкаN(Выдана_в)</div>
          <div>Ответ1(От_кого@Когда) ... ОтветN(От_кого@Когда)</div>
        </div>
      </div>
    </div>

    <h4>Состояние команд</h4>
    <p>
      Формат строки:
    </p>
    <div class="complexList">
      <div class="oddLine">
        <div class="cell">Название_Команды: </div>
        <div class="cell">Состояние(На_момент)</div>
      </div>
    </div>

    <h4>Состояние игры</h4>
    <p>
      Формат строки:
    </p>
    <div class="complexList">
      <div class="oddLine">
        <div class="cell">Состояние(На_момент)</div>
        <div class="cell">
          <div>
            Плановый_старт_в, <span class="info">Стартовала_в</span>, Остановка_в, Финишировала_в 
          </div>
        </div>
      </div>
    </div>
    
  </div>

</div>
