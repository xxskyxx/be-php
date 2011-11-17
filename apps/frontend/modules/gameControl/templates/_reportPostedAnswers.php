<?php
/**
 * Входные аргументы:
 * - integer $taskState - состояние задания команды
 * - boolean $highlight - отмечать ли цветом состояния ответов
 * - boolean $withTime - указывать ли время отправки
 * - boolean $withSender - указывать ли игрока, который отправил
 */
$highlight = (isset($highlight)) ? $highlight : false;
$withTime = (isset($withTime)) ? $withTime : false;
$withSender = (isset($withSender)) ? $withSender : false;

$postedAnswers = Doctrine::getTable('PostedAnswer')
    ->createQuery('pa')
    ->select()->leftJoin('pa.TaskState')->innerJoin('pa.WebUser')
    ->where('pa.task_state_id = ?', $taskState->id)
    ->orderBy('pa.post_time')
    ->execute();

foreach ($postedAnswers as $postedAnswer)
{
  $value = $postedAnswer->value;
  $needSender = $withSender && ($postedAnswer->web_user_id > 0);
  $value = ($withTime || $withSender) ? $value.'(' : $value;
  $value = $needSender ? $value.$postedAnswer->WebUser->login : $value;
  $value = ($withTime && $withSender) ? $value.',' : $value;
  $value = $withTime ? $value.Timing::timeToStr($postedAnswer->post_time) : $value;
  $value = ($withTime || $withSender) ? $value.')' : $value;

  if ($highlight)
  {
    switch ($postedAnswer->status)
    {
      case PostedAnswer::ANSWER_POSTED:
        $class = 'warn';
        break;
      case PostedAnswer::ANSWER_OK:
        $class = 'info';
        break;
      case PostedAnswer::ANSWER_BAD:
        $class = 'danger';
        break;
      default:
        $class = 'indent';
    }
  }
  else
  {
    $class = 'indent';
  }

  echo '<span class="'.$class.'">'.$value.'</span> ';
}
?>

