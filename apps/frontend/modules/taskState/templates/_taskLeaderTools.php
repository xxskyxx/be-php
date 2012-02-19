<?php
/**
 * Входные аргументы:
 * - taskState - текущее задание.
 */
?>
<h4>Капитану:</h4>
<?php if ($taskState->canBeSkipped()): ?>
<div><span class="dangerAction"><?php echo link_to('Пропустить задание', 'taskState/skip?id='.$taskState->id.'&returl='.Utils::encodeSafeUrl(url_for('taskState/task?id='.$taskState->id)), array('method' => 'post', 'confirm' => 'Вы точно хотите пропустить задание?')); ?></span></div>
<?php else: ?>
<div><span class="danger">Пропустить&nbsp;задание</span> сейчас нельзя</div>
<?php endif; ?>

