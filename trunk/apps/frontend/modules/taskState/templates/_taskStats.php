<?php
/**
 * Входные аргументы:
 * - taskState - текущее задание.
 */
?>
<h4>Статистика:</h4>
<div>
  Сейчас:&nbsp;<?php echo Timing::timeToStr($taskState->task_last_update) ?>
</div>
<div>
  Задание:
  <ul>
    <li>идет уже <?php echo Timing::intervalToStr($taskState->getTaskSpentTimeCurrent()) ?></li>
    <li>завершение <?php echo Timing::timeToStr($taskState->getTaskStopTime()) ?>
    </li>
  </ul>
</div>
<div>
  Игра:
  <ul>
    <li>идет уже <?php echo Timing::intervalToStr($taskState->TeamState->getGameSpentTimeCurrent()) ?></li>
    <li>завершение <?php echo Timing::timeToStr($taskState->TeamState->getTeamStopTime()) ?></li>
  </ul>
</div>
