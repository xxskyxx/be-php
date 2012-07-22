<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_game->name, 'game/show?id='.$_game->id),
    'Управление'
));
    
$retUrlRaw = Utils::encodeSafeUrl(url_for('gameControl/pilot?id='.$_game->id));
include_partial('header', array(
    '_game' => $_game,
    '_isManager' => $_isManager,
    '_retUrlRaw' => $retUrlRaw,
    '_activeTab' => 'pilot'));
?>

<div class="tabSheet">
  
  <h3>Ответы к текущим заданиям</h3>
  <div class="complexList">
    <?php $odd = true ?>
    <?php foreach ($_teamStates as $teamState): ?>
    <div class="<?php echo $odd ? 'oddLine' : 'evenLine' ?>">
      <div class="cell">
        <?php echo link_to($teamState->Team->name, 'teamState/show?id='.$teamState->id).': ' ?>
      </div>
      <div class="cell">
        <?php
        $currentTaskState = $teamState->getCurrentTaskState();
        if ($currentTaskState)
        {
          ?><span style="font-weight: bold"><?php echo $currentTaskState->Task->name.': ';?></span><?php
          if ($currentTaskState->status == TaskState::TASK_ACCEPTED)
          {
            ?><div style="display: inline-block"><?php
            echo include_partial('taskState/taskAnswerPostedForm', array('form' => new SimpleAnswerForm, 'id' => $currentTaskState->id, 'retUrl' => $retUrlRaw));
            ?></div><?php
            include_component('taskState', 'answersForGameManager', array('taskStateId' => $currentTaskState->id));
          }
        }
        else
        {
          echo decorate_span('warnBorder', 'Нет&nbsp;задания');
        }
        ?>
      </div>
    </div>  
    <?php $odd = ( ! $odd); ?>
    <?php endforeach ?>
  </div>  
  
  <h3>Текущие задания</h3>
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
          <?php if ($currentTaskState): ?>
            <?php
            $task = DCTools::recordById($_tasks->getRawValue(), $currentTaskState->task_id);
            echo link_to($task->name, 'task/show?id='.$task->id).' - ';
            $linkName = $currentTaskState->describeStatus();
            $linkUrl = 'teamState/task?id='.$teamState->id;
            echo link_to($linkName, $linkUrl, array('target' => 'new'));
            
            if ($currentTaskState->status == TaskState::TASK_GIVEN)
            {
              if ($currentTaskState->Task->isFilled())
              {
                echo ' '.decorate_span('warn', 'Заполнено');
              }
              
              if ($currentTaskState->Task->manual_start)
              {
                echo ' '.decorate_span('warn', 'Ручной старт');
              }
              else
              {
                echo ' '.decorate_span('info', 'Автостарт');
              }
            }
            
            switch ($currentTaskState->status)
            {
              case (TaskState::TASK_GIVEN):
                echo ($currentTaskState->Task->isFilled() || $currentTaskState->Task->manual_start)
                  ? ' '.decorate_span('warnAction', link_to('Старт','taskState/start?id='.$currentTaskState->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Дать старт заданию '.$task->name.' для команды '.$teamState->Team->name.' ?')))
                  : '';
                echo ' '.decorate_span('warnAction', link_to('Отменить','teamState/abandonTask?id='.$teamState->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Отменить выдачу задания '.$task->name.' командe '.$teamState->Team->name.' ?')));
                break;
              
              case (TaskState::TASK_STARTED):
                echo ' '.decorate_span('warnAction', link_to('Прочесть','taskState/forceAccept?id='.$currentTaskState->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Подтвердить прочтение задания '.$task->name.' командой '.$teamState->Team->name.' ?')));
                echo ' '.decorate_span('warnAction', link_to('Отменить','teamState/abandonTask?id='.$teamState->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Отменить выдачу задания '.$task->name.' командe '.$teamState->Team->name.' ?')));
                break;
              
              case (TaskState::TASK_ACCEPTED):
                echo ' '.decorate_span('dangerAction', link_to('Прекратить','teamState/abandonTask?id='.$teamState->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Прекратить выполнение задания '.$task->name.' командой '.$teamState->Team->name.' ?')));
                break;
            };            
            ?>
          <?php else: ?>
            <?php
            if ($teamState->status == TeamState::TEAM_FINISHED)
            {
              echo decorate_span('info', link_to('Финишировала', 'teamState/task?id='.$teamState->id, array('target' => 'new')));
            }
            else
            {
              echo decorate_span('warnBorder', link_to('Нет&nbsp;задания', 'teamState/task?id='.$teamState->id, array('target' => 'new')));
              
              if ($teamState->status == TeamState::TEAM_WAIT_TASK)
              {
                $htmlLink = link_to('Финишировать','teamState/forceFinish?id='.$teamState->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Отправить команду '.$teamState->Team->name.' на финиш?'));
                echo ' '.decorate_span('dangerAction', $htmlLink);
              }
            }
            ?>
          <?php endif ?>
        </div>
        <div class="comment">
          <?php
          if ($currentTaskState)
          {
            foreach ($currentTaskState->usedTips as $usedTip)
            {
              $tip = DCTools::recordById($_usedTips->getRawValue(), $usedTip->id)->Tip;
              echo $tip->name.' ';
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
  
  <h3>Справка</h3>
  
  <div class="comment">
    
    <h4>Ответы к текущим заданиям</h4>
    <p>
      Ссылка с названием команды - переход к настройкам команды.
    </p>
    <p>
      Ожидаемые ответы указаны названиями.
    </p>
    <p>
      Полученные ответы указаны значениями: <span class="info">правильными</span>, <span class="warn">не проверенными</span> и <span class="danger">неверными</span>.
    </p>

    <h4>Текущие задания</h4>
    <p>
      Ссылка с названием задания - переход к просмотру задания.
    </p>
    <p>
      Ссылка с состоянием задания - переход к странице текущего задания.
    </p>
    <p>
      Формат строки:
    </p>
    <div class="complexList">
      <div class="oddLine">
        <div class="cell">Название_Команды: </div>
        <div class="cell">
          <div>Название_задания - состояние_задания</div>
          <div>Подсказка1 ... ПодсказкаN</div>
        </div>
      </div>
    </div>
    
  </div>

</div>