<?php
foreach ($_timeSortedPostAnswers as $postedAnswer)
{
  $class = 'indent';
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
  }
  echo decorate_div($class, $postedAnswer->value.'('.$postedAnswer->WebUser->login.'@'.Timing::timeToStr($postedAnswer->post_time).')');
}
?>