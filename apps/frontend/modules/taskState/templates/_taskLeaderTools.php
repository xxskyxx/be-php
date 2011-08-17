<?php
/**
 * Входные аргументы:
 * - taskState - текущее задание.
 */
?>
<h4>Капитану:</h4>
<?php if ($taskState->canBeSkipped()): ?>
<div><span class="dangerAction"><?php echo Utils::buttonTo('Пропустить задание', 'taskState/skip?id='.$taskState->id.'&returl='.Utils::encodeSafeUrl(url_for('taskState/task?id='.$taskState->id)), 'post', 'Вы точно хотите пропустить задание?'); ?></span></div>
<?php else: ?>
<div><span class="dangerAction">Пропустить&nbsp;задание</span></div>
<div>Пропустить задание Вы можете только после первой подсказки и только до ввода первого ответа.</div>
<?php endif; ?>

