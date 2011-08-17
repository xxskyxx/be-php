<?php
/**
 * Входные аргументы:
 * - taskState - текущее задание.
 * - compact - краткий вид.
 * - describe - не найденные ответы будут указаны назниваем, а не описанием.
 */
$compact = (isset($compact)) ? $compact : false;
$describe = (isset($describe)) ? $describe : false;
?>

<?php
//Построим индекс ответов:
//- ключ - id ответа
//- данные - true если принят и проверен
$answersIndex = array();
foreach ($taskState->Task->answers as $answer)
{
  $answersIndex[$answer->id] = false;
}
//Проверим, какие из них введены и подтверждены
foreach ($taskState->postedAnswers as $postedAnswer)
{
  if ($postedAnswer->status == PostedAnswer::ANSWER_OK)
  {
    $answersIndex[$postedAnswer->answer_id] = true;
  }
}
?>

<?php if (!$compact): ?>
<h4>Ответы:</h4>
<?php endif ?>
<?php foreach ($taskState->Task->answers as $answer): ?>
<?php   if (!$answersIndex[$answer->id]): ?>
<?php     if (!$describe): ?>
<span class="indent"><?php echo $answer->info ?></span>
<?php     else: ?>
<span class="indent"><?php echo $answer->name ?></span>
<?php     endif; ?>
<?php   else: ?>
<span class="info"><?php echo $answer->value ?></span>
<?php   endif; ?>
<?php endforeach; ?>

<?php
//Выпишем ответы, находящиеся на проверке, заодно сосчитаем неверные.
$allFresh = '';
$allBad = '';
$badCount = 0;
foreach ($taskState->postedAnswers as $postedAnswer)
{
  if ($postedAnswer->status == PostedAnswer::ANSWER_POSTED)
  {
    $allFresh .= '<span class="warn">'.$postedAnswer->value.'</span> ';
  }
  elseif ($postedAnswer->status == PostedAnswer::ANSWER_BAD)
  {
    $allBad .= '<span class="danger">'.$postedAnswer->value.'</span> ';
    $badCount++;
  }
}
?>

<?php if (!$compact): ?>
<?php   if ($allFresh !== ''): ?>
<h5>Проверяются:</h5>
<div><?php echo $allFresh ?></div>
<?php   endif ?>
<?php   if ($badCount > 0): ?>
<h5>Неверные:</h5>
<div>
  <?php echo $allBad ?>
  <?php $tryCount = $taskState->Task->try_count_local - $badCount; ?>
  <?php   if ($tryCount >= 0): ?>
  <p>
    <div class="danger">Осталось неверных попыток: <?php echo $taskState->Task->try_count_local - $badCount ?></div>
  </p>
</div>
<?php     endif ?>
<?php   endif ?>

<?php else: ?>
  <?php echo $allFresh.' '.$allBad; ?>
<?php endif ?>

