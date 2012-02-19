<?php
foreach ($_restAnswers as $answer)
{
  echo " ".$answer->name;
}
foreach ($_goodAnswers as $postedAnswer)
{
  echo " ".decorate_span("info", $postedAnswer->value);
}
foreach ($_beingVerifiedAnswers as $postedAnswer)
{
  echo " ".decorate_span("warn", $postedAnswer->value);
}
foreach ($_badAnswers as $postedAnswer)
{
  echo " ".decorate_span("danger", $postedAnswer->value);
}
?>
