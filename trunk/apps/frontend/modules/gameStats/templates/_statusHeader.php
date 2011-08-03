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
  <span class="warnAction"><?php echo Utils::buttonTo('Пересчитать состояние', 'gameStats/update?id='.$game->id.'&returl='.$backLinkEncoded); ?></span>
  <?php endif; ?>
  <span class="indentAction"><?php echo link_to('Редактор игры', 'game/show?id='.$game->id, array('target' => 'new')) ?></span>
  
  <?php
  if ($game->isActive())
  {
    if (Timing::isExpired(time(), $game->update_interval_max, $game->game_last_update))
    {
  ?>
  <div class="spaceAround">
    <div class="danger">
      Пересчет состояния просрочен на <?php echo Timing::intervalToStr(time() - $game->game_last_update - $game->update_interval_max) ?>!
    </div>
  </div>
  <?php 
    }
  }
  ?>
</div>
