<?php
/* Входные данные:
 * - $game - игра
 * - $backLinkEncoded - ссылка для обратных переходов из диалогов/действий кодированная
 * - $sessionIsManager - текущий пользователь - руководитель игры
 */
?>

<h3>Текущие результаты команд</h3>
<div class="spaceAfter">
  <?php echo link_to('Просмотр полной телеметрии', 'gameStats/report?id='.$game->id, array('target' => 'new')) ?>
</div>
<?php include_partial('Results', array('game' => $game)) ?>