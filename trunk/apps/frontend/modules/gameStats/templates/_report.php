<?php
/**
 * Входные аргументы:
 * - Game $game - игра, для которой строится отчет.
 */
?>

<table cellspacing="0">
  <thead>
    <tr>
      <th>Команда</th>
      <?php foreach ($game->tasks as $Task): ?>
      <th><?php echo $Task->name?></th>
      <?php endforeach; ?>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($game->teamStates as $teamState): ?>

    <?php
    // Построим индекс колонок, чтобы не сопоставлять каждый раз задание с состоянием.
    $index = array();
    $column = 0;
    foreach ($teamState->Game->tasks as $task)
    {
      $index[$column] = $teamState->findKnownTaskState($task->getRawValue());
      $column++;
    }
    ?>

    <?php //Команда и название заданий ?>
    <tr style="background-color:Navy">
      <th class="bottomWeakBorder"><?php echo $teamState->Team->name ?></th>
      <?php foreach ($index as $taskState): ?>
      <?php   if (!$taskState): ?>
      <td class="bottomWeakBorder">&nbsp;</td>
      <?php   else: ?>
      <td class="bottomWeakBorder" style="font-weight:bold"><?php echo $taskState->Task->name ?></td>
      <?php   endif; ?>
      <?php endforeach; ?>
    </tr>

    <?php //Итог задания ?>
    <tr>
      <td class="bottomWeakBorder">...итог</td>
      <?php foreach ($index as $taskState): ?>
      <?php   if (!$taskState): ?>
      <td class="bottomWeakBorder">&nbsp;</td>
      <?php   else: ?>
      <td class="bottomWeakBorder">
        <div class="<?php echo $taskState->getHighlightClass() ?>">
          <?php echo $taskState->describeStatus() ?>
        </div>
      </td>
      <?php   endif; ?>
      <?php endforeach; ?>
    </tr>

    <?php //Потрачено минут ?>
    <tr>
      <td class="bottomWeakBorder">...длилось</td>
      <?php foreach ($index as $taskState): ?>
      <?php   if (!$taskState): ?>
      <td class="bottomWeakBorder">&nbsp;</td>
      <?php   else: ?>
      <td class="bottomWeakBorder"><?php echo Timing::intervalToStr($taskState->task_time_spent) ?></td>
      <?php   endif; ?>
      <?php endforeach; ?>
    </tr>
    <?php //Начало задания ?>

    <tr>
      <td class="bottomWeakBorder">...начато</td>
      <?php foreach ($index as $taskState): ?>
      <?php   if (!$taskState): ?>
      <td class="bottomWeakBorder">&nbsp;</td>
      <?php   else: ?>
      <td class="bottomWeakBorder"><?php echo Timing::timeToStr($taskState->accepted_at) ?></td>
      <?php   endif; ?>
      <?php endforeach; ?>
    </tr>

    <?php //Использование подсказок ?>
    <tr>
      <td class="bottomWeakBorder">...подсказки</td>
      <?php foreach ($index as $taskState): ?>
      <?php   if (!$taskState): ?>
      <td class="bottomWeakBorder">&nbsp;</td>
      <?php   else: ?>
      <td class="bottomWeakBorder"><?php include_partial('taskState/UsedTips', array('taskState' => $taskState, 'onlyUsed' => true, 'withTime' => true)) ?></td>
      <?php   endif; ?>
      <?php endforeach; ?>
    </tr>

    <?php //Полученные ответы ?>
    <tr>
      <td class="bottomWeakBorder">...ответы</td>
      <?php foreach ($index as $taskState): ?>
      <?php   if (!$taskState): ?>
      <td class="bottomWeakBorder">&nbsp;</td>
      <?php   else: ?>
      <td class="bottomWeakBorder"><?php include_partial('taskState/PostedAnswers', array('taskState' => $taskState, 'withTime' => true, 'withSender' => true, 'highlight' => true)) ?></td>
      <?php   endif; ?>
      <?php endforeach; ?>
    </tr>

    <?php //Окончание задания ?>
    <tr>
      <td>...окончание</td>
      <?php foreach ($index as $taskState): ?>
      <?php   if (!$taskState): ?>
      <td>&nbsp;</td>
      <?php   else: ?>
      <td><?php echo Timing::timeToStr($taskState->done_at) ?></td>
      <?php   endif; ?>
      <?php endforeach; ?>
    </tr>

    <?php endforeach; ?>
  </tbody>
</table>