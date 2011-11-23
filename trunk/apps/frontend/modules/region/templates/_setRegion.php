<?php
/* Входные аргументы:
 * $retUrl  string  Куда возвращаться после выбора.
 */
?>
<span class="safeAction"><?php echo link_to('Сменить регион', 'region/setCurrent?returl='.Utils::encodeSafeUrl($retUrl)); ?></span>
<span class="safeAction"><?php echo link_to('Показать всех', 'region/setCurrent?id='.Region::DEFAULT_REGION.'&returl='.Utils::encodeSafeUrl($retUrl), array('method' => 'post')); ?></span>
