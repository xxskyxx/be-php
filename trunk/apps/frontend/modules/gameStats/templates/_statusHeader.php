<?php
/* Входные данные:
 * - $game - игра
 * - $backLinkEncoded - ссылка для обратных переходов из диалогов/действий кодированная
 * - $sessionIsManager - текущий пользователь - руководитель игры
 */
?>
<div>
  <span class="safeAction"><?php echo link_to('Обновить (без пересчета)', Utils::decodeSafeUrl($backLinkEncoded)) ?></span>
  <?php if ($sessionIsManager): ?>
  <span class="warnAction"><?php echo link_to('Пересчитать состояние', 'gameStats/update?id='.$game->id.'&returl='.$backLinkEncoded, array('method' => 'post')); ?></span>
  <?php endif; ?>
  
  <?php if ($game->isActive() && (Timing::isExpired(time(), $game->update_interval_max, $game->game_last_update))): ?>
  <p>
    <div class="danger">Пересчет состояния просрочен на <?php echo Timing::intervalToStr(time() - $game->game_last_update - $game->update_interval_max) ?>!</div>
  </p>
  <?php endif ?>
</div>
