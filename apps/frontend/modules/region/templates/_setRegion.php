<?php
/* Входные аргументы:
 * $retUrl  string  Куда возвращаться после выбора.
 */
?>
<?php if ($sf_user->getAttribute('region_id') > Region::DEFAULT_REGION): ?>
<span class="safeAction"><?php echo link_to('Сменить', 'region/setCurrent?returl='.Utils::encodeSafeUrl($retUrl)); ?></span>
<span class="safeAction"><?php echo link_to('Все', 'region/setCurrent?id='.Region::DEFAULT_REGION.'&returl='.Utils::encodeSafeUrl($retUrl), array('method' => 'post')); ?></span>
<?php else: ?>
<span class="safeAction"><?php echo link_to('Задать регион', 'region/setCurrent?returl='.Utils::encodeSafeUrl($retUrl)); ?></span>
<?php endif ?>
