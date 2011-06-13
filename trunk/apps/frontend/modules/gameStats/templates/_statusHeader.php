<?php
/* Входные данные:
 * - $game - игра
 * - $backLinkEncoded - ссылка для обратных переходов из диалогов/действий кодированная
 * - $sessionIsManager - текущий пользователь - руководитель игры
 */
?>
<div class="spaceAfter">
  <span class="indentAction"><?php echo link_to('Перейти к игре '.$game->name, 'game/show?id='.$game->id, array('target' => 'new')) ?></span>
</div>
<div>
  <span class="safeAction"><?php echo link_to('Обновить (без пересчета)', Utils::decodeSafeUrl($backLinkEncoded)) ?></span>
  <?php if ($sessionIsManager): ?>
  <span class="warnAction"><?php echo Utils::buttonTo('Пересчитать состояние', 'gameStats/update?id='.$game->id.'&returl='.$backLinkEncoded); ?></span>
  <?php endif; ?>
</div>
