<?php
/* Входные данные:
 * - $game - игра
 * - $backLinkEncoded - ссылка для обратных переходов из диалогов/действий кодированная
 * - $sessionIsManager - текущий пользователь - руководитель игры
 * - $seat - текущая вкладка.
 */
?>

<table cellspacing="0" class="tabControl">
  <thead>
    <tr>
      <?php if ($seat == 'pilot'): ?>
      <th class="tabTitleActive">Пилот</th>
      <?php else: ?>
      <th class="tabTitle"><?php echo link_to('Пилот', 'gameStats/status?id='.$game->id.'&seat=pilot'); ?></th>
      <?php endif; ?>

      <?php if ($seat == 'sturman'): ?>
      <th class="tabTitleActive">Штурман</th>
      <?php else: ?>
      <th class="tabTitle"><?php echo link_to('Штурман', 'gameStats/status?id='.$game->id.'&seat=sturman'); ?></th>
      <?php endif; ?>

      <?php if ($seat == 'engineer'): ?>
      <th class="tabTitleActive">Бортмеханик</th>
      <?php else: ?>
      <th class="tabTitle"><?php echo link_to('Бортмеханик', 'gameStats/status?id='.$game->id.'&seat=engineer'); ?></th>
      <?php endif; ?>
      
      <?php if ($seat == 'stuart'): ?>
      <th class="tabTitleActive">Стюардесса</th>
      <?php else: ?>
      <th class="tabTitle"><?php echo link_to('Стюардесса', 'gameStats/status?id='.$game->id.'&seat=stuart'); ?></th>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="4" class="tabSheet">
        <?php
          if ($seat == 'pilot')
          {
            include_partial('StatusTabPilot', array('game' => $game, 'backLinkEncoded' => $backLinkEncoded, 'sessionIsManager' => $sessionIsManager));
          }
          elseif ($seat == 'sturman')
          {
            include_partial('StatusTabSturman', array('game' => $game, 'backLinkEncoded' => $backLinkEncoded, 'sessionIsManager' => $sessionIsManager));
          }
          elseif ($seat == 'engineer')
          {
            include_partial('StatusTabEngineer', array('game' => $game, 'backLinkEncoded' => $backLinkEncoded, 'sessionIsManager' => $sessionIsManager));
          }
          elseif ($seat == 'stuart')
          {
            include_partial('StatusTabStuart', array('game' => $game, 'backLinkEncoded' => $backLinkEncoded, 'sessionIsManager' => $sessionIsManager));
          }
          else
          {
          ?><div class="danger">У нас нет такой должности - <?php echo $seat; ?></div><?php
          }
        ?>
      </td>
    </tr>
  </tbody>
</table>