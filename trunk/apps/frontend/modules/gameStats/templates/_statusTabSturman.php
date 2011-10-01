<?php
/* Входные данные:
 * - $game - игра
 * - $backLinkEncoded - ссылка для обратных переходов из диалогов/действий кодированная
 * - $sessionIsManager - текущий пользователь - руководитель игры
 */
?>
<h3>Карта игровой ситуации</h3>
<table cellspacing="0">
  <thead>
    <tr>
      <th rowspan="2">ИИ</th>
      <th rowspan="2"><?php echo Utils::renderVertical('Команда') ?></th>
      <th colspan="<?php echo $game->tasks->count() ?>">Задание</th>
    </tr>
    <tr>
      <?php foreach ($game->tasks as $task): ?>
      <td style="text-align:center">
        <div class="<?php
                    if ($task->locked)
                    {
                      echo 'danger';
                    }
                    elseif ($task->isFilled())
                    {
                      echo 'warn';
                    }
                    elseif ($task->taskStates->count() == 0)
                    {
                      echo 'info';
                    }
                    else
                      echo 'indent';
                    ?>">
          <?php echo link_to(Utils::renderVertical($task->name), 'task/edit?id='.$task->id, array('target' => 'new')) ?>
        </div>        
      </td>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($game->teamStates as $teamState): ?>
    <?php $currentTaskState = $teamState->getCurrentTaskState() ?>
    <tr>
      <td>
        <?php if ($teamState->ai_enabled): ?>
        <span class="info"><?php echo link_to_if($sessionIsManager, 'Вкл', 'teamState/edit?id='.$teamState->id.'&returl='.$backLinkEncoded, array('target' => 'new')) ?></span>
        <?php else: ?>
        <span class="warn"><?php echo link_to_if($sessionIsManager, 'Отк', 'teamState/edit?id='.$teamState->id.'&returl='.$backLinkEncoded, array('target' => 'new')) ?></span>
        <?php endif; ?>
      </td>
      <td>
        <div class="<?php echo ($currentTaskState !== false) ? 'indent' : 'warn' ?>">
          <?php echo link_to($teamState->Team->name, 'teamState/task?id='.$teamState->id, array('target' => 'new', 'confirm' => 'Просмотр задания равноценен его прочтению командой и запускает таймер задания. Вы точно хотите посмотреть задание?')) ?>
        </div>
      </td>
      <?php if ($teamState->status < TeamState::TEAM_FINISHED): ?>
      <?php   foreach ($game->tasks as $task): ?>
      <td style="text-align:center">
        <?php
        $priority = $teamState->getPriorityOfTask($task->getRawValue());
        if ( $priority === false )
        {
          $allowSetNext = false;
          if ( $currentTaskState && ($currentTaskState->task_id == $task->id) )
          {
            $class = 'indent';
            $message = $currentTaskState->describeStatus();
          }
          else
          {
            $knownTaskState = $teamState->findKnownTaskState($task->getRawValue());
            if ($knownTaskState)
            {
              $class = $knownTaskState->getHighlightClass();
              $message = $knownTaskState->describeStatus();
            }
            else
            {
              $class = 'indent';
              $message = '-';
            }
          }
        }
        else
        {
          $class = 'indent';
          $message = $priority;
          $allowSetNext = true;
        }
        ?>
        <div class="<?php echo ($teamState->task_id == $task->id) ? 'infoBorder' : 'indentBorder' ?>">
          <div class="<?php echo $class ?>">
            <?php if ($teamState->task_id == $task->id): ?>
            <?php   echo link_to_if($sessionIsManager && $allowSetNext, $message, 'gameStats/setNext?teamState='.$teamState->id.'&taskId=0'.'&returl='.$backLinkEncoded) ?>
            <?php else: ?>
            <?php   echo link_to_if($sessionIsManager && $allowSetNext, $message, 'gameStats/setNext?teamState='.$teamState->id.'&taskId='.$task->id.'&returl='.$backLinkEncoded) ?>
            <?php endif; ?>
          </div>
        </div>
      </td>
      <?php   endforeach; ?>
      <?php else: ?>
      <td colspan="<?php echo $game->tasks->count() ?>">Финишировала</td>
      <?php endif; ?>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h3>Управление игрой</h3>
<?php if ($sessionIsManager): ?>
<div>
  <span class="infoBorder">Игра&nbsp;сейчас: <?php echo $game->describeStatus() ?></span>
  
  <?php // switch как-то вообще по дикому себя ведет, включенного HTML не допускает никак, придется костылить ?>
  <?php if     ($game->status == Game::GAME_PLANNED): ?>
  <span class="safeAction"><?php echo link_to('Подготовить к запуску', 'gameStats/verify?id='.$game->id.'&returl='.$backLinkEncoded, array('method' => 'post', 'confirm' => 'Подготовить игру '.$game->name.' к запуску?')); ?></span>

  <?php elseif ($game->status == Game::GAME_VERIFICATION): ?>
  <span class="safeAction"><?php echo link_to('Повторить проверку', 'gameStats/verify?id='.$game->id.'&returl='.$backLinkEncoded, array('method' => 'post', 'confirm' => 'Повторить предстартовую проверку игры '.$game->name.'?')); ?></span>

  <?php elseif ($game->status == Game::GAME_READY): ?>
  <span class="warnAction"><?php echo link_to('Запустить', 'gameStats/start?id='.$game->id.'&returl='.$backLinkEncoded, array('method' => 'post', 'confirm' => 'Запустить игру '.$game->name.'?')); ?></span>
  <span class="safeAction"><?php echo link_to('Повторить проверку', 'gameStats/verify?id='.$game->id.'&returl='.$backLinkEncoded, array('method' => 'post', 'confirm' => 'Повторить предстартовую проверку игры '.$game->name.'?')); ?></span>

  <?php elseif (($game->status == Game::GAME_STEADY) || ($game->status == Game::GAME_ACTIVE)): ?>
  <span class="dangerAction"><?php echo link_to('Остановить', 'gameStats/stop?id='.$game->id.'&returl='.$backLinkEncoded, array('method' => 'post', 'confirm' => 'Остановить игру '.$game->name.'?')); ?></span>

  <?php elseif ($game->status == Game::GAME_FINISHED): ?>
  <span class="warnAction"><?php echo link_to('Сдать в архив', 'gameStats/close?id='.$game->id.'&returl='.$backLinkEncoded, array('method' => 'post', 'confirm' => 'Игру больше нельзя будет редактировать! Вы уверены, что хотите сдать в архив игру '.$game->name.'?')); ?></span>
  <?php endif; ?>

  <?php if ($game->status > Game::GAME_PLANNED): ?>
  <span class="dangerAction"><?php echo link_to('Перезапустить', 'gameStats/reset?id='.$game->id.'&returl='.$backLinkEncoded, array('method' => 'post', 'confirm' => 'Перезапустить игру '.$game->name.'?'));?></span>
  <?php endif; ?>
  
</div>
<p>
  <span class="warnAction"><?php echo link_to('Запустить автоматический пересчет (открывать в новом окне!)', url_for('gameStats/autoUpdate?id='.$game->id), array('target' => 'window')) ?></span>
</p>
<?php endif; ?>

<?php if ($game->teams_can_update): ?>
<p>
  <div class="info">Командам разрешен пересчет состояния при обновлении страницы текущего задания.</div>
</p>
<?php endif; ?>


<div class="comment">
  <h3>Справка</h3>
  <h4>Карта игровой ситуации</h4>
  <ul>
    <li>Ссылка названия задания открывает радактор задания.</li>
    <li>Ссылка названия команды открывает страницу текущего задания команды.</li>
    <li>Ссылка в колонке "ИИ" открывает редактор игровых настроек команды.</li>
    <li>Статус задания по доступности для команд: <span class="info">свободно</span>, занято, <span class="warn">перегружено</span>, <span class="danger">заблокировано</span>.</li>
    <li>Статус команды по наличию текущего задания: есть или <span class="warn">нет</span>.</li>
    <li>Число в ячейке отображает текущий приоритет неизвестного команде задания.</li>
    <li>Слова в ячейках обозначают состояния заданий.</li>
    <li>Цветом в ячейках выделены задания завершенные <span class="info">успешно</span>, <span class="warn">неудачно</span> или <span class="danger">некорректно</span>.</li>
    <li><span class="infoBorder">Рамка</span> отмечает выбранное следующее задание.</li>
    <li>Нажатие ссылки с числом (приоритетом) выбирает следующее задание или отменяет выбор.</li>
  </ul>
</div>
  