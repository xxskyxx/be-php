<?php
/**
 * Входные аргументы:
 * - integer $teamState - состояние команды
 * - boolean $withLink - создавать ли ссылку на задание
 * - boolean $withTime - указывать ли телеметрию
 * - boolean $highlight - отмечать ли цветом итог задания
 */
foreach ($teamState->taskStates as $taskState)
{
  $value = $withLink
      ? link_to($taskState->Task->name, 'task/show?id='.$taskState->task_id)
      : $taskState->Task->name;
  $value .= $withTime
      ? '&nbsp;c&nbsp;'.Timing::timeToStr($taskState->accepted_at).'&nbsp;по&nbsp;'.Timing::timeToStr($taskState->done_at)
      : '';
  $value .= '&nbsp;'.$taskState->describeStatus();

  $class = $highlight ? $taskState->getHighlightClass() : 'indent';

  echo '<div class="'.$class.'">'.$value.'</div>';
}
?>
