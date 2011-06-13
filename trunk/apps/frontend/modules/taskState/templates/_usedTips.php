<?php
/**
 * Входные аргументы:
 * - integer $taskState - состояние задания команды
 * - integer $onlyUsed - показывать только использованные
 * - boolean $withLink - создавать ли ссылку на подсказку
 * - boolean $withTime - указывать ли время использования
 * - boolean $highlight - отмечать ли цветом состояния подсказок
 * - boolean $column - выводить столбиком, иначе в строку.
 */
$onlyUsed = (isset($onlyUsed)) ? $onlyUsed : true;
$withLink = (isset($withLink)) ? $withLink : false;
$withTime = (isset($withTime)) ? $withTime : false;
$highlight = (isset($highlight)) ? $highlight : false;
$column = (isset($column)) ? $column : false;

foreach ($taskState->usedTips as $usedTip)
{
  if ($onlyUsed && ($usedTip->status != UsedTip::TIP_USED))
  {
    continue;
  }
  
  $value = $withLink
      ? link_to($usedTip->Tip->name, 'tip/edit?id='.$usedTip->tip_id, array('target' => 'new'))
      : $usedTip->Tip->name;
  $value = $withTime
      ? $value.'('.Timing::timeToStr($usedTip->used_since).')'
      : $value;

  if ($highlight)
  {
    switch ($usedTip->status)
    {
      case UsedTip::TIP_WAIT:
        $class = 'indent';
        break;
      case UsedTip::TIP_USED:
        $class = 'info';
        break;
      default:
        $class = 'indent';
    }
  }
  else
  {
    $class = 'indent';
  }

  echo $column
      ? '<div class="'.$class.'">'.$value.'</div>'
      : '<span class="'.$class.'">'.$value.'</span> ';
}
?>
