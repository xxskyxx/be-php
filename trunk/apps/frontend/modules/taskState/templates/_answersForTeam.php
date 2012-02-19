<div>
  <?php
  foreach ($_restAnswers as $answer)
  {
    echo " ".$answer->info;
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
</div>
<?php if ($_badAnswers->count() > 0): ?>
<div class="danger">Допустимо ошибок: <?php echo $_badAnswersLeft ?></div>
<?php endif ?>