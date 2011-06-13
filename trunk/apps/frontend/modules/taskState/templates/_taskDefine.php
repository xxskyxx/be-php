<?php
/**
 * Входные аргументы:
 * - taskState - текущее задание.
 */
?>
<h4>Задание #<?php echo $taskState->TeamState->taskStates->count() ?>:</h4>

<?php foreach ($taskState->usedTips as $usedTip): ?>
<?php   if ($usedTip->status == UsedTip::TIP_USED): ?>
<h5>
  (<?php echo Timing::timeToStr($usedTip->used_since)?>):
</h5>
<div>
  <?php echo Utils::decodeBB($usedTip->Tip->define)?>
</div>
<?php   endif; ?>
<?php endforeach; ?>
