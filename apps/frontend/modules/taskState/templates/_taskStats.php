<?php
/**
 * Входные аргументы:
 * - taskState - текущее задание.
 */
?>
<h4>Статистика:</h4>
<div>
  Время сервера: <span id="serverTime">--:--:--</span>
</div>
<div>
  Задание:
  <ul>
    <li>обновлено <?php echo Timing::timeToStr($taskState->task_last_update) ?></li>
    <li>идет уже <?php echo Timing::intervalToStr($taskState->getTaskSpentTimeCurrent()) ?></li>
    <li>завершение <?php echo Timing::timeToStr($taskState->getTaskStopTime()) ?></li>
  </ul>
</div>
<div>
  Игра:
  <ul>
    <li>идет уже <?php echo Timing::intervalToStr($taskState->TeamState->getGameSpentTimeCurrent()) ?></li>
    <li>завершение <?php echo Timing::timeToStr($taskState->TeamState->getTeamStopTime()) ?></li>
  </ul>
</div>
