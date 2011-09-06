<?php
/* Входные данные:
 * - $game - игра
 * - $backLinkEncoded - ссылка для обратных переходов из диалогов/действий кодированная
 * - $sessionIsManager - текущий пользователь - руководитель игры
 */
?>

<h3>Текущие результаты команд</h3>
<p>
  <?php echo link_to('Просмотр полной телеметрии', 'gameStats/report?id='.$game->id, array('target' => 'new')) ?>
</p>
<?php include_partial('results', array('game' => $game)) ?>
