<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    $_game->name
));
    
$retUrlRaw = Utils::encodeSafeUrl(url_for('game/show?id='.$_game->id.'&tab='.$_tab));
?>

<h2>Игра <?php echo $_game->name ?></h2>

<p>
  <span class="indentAction"><?php echo link_to('Афиша', 'game/info?id='.$_game->id) ?></span>
  <span class="info"><?php echo link_to('Состояние и управление', 'gameControl/pilot?id='.$_game->id) ?></span>
</p>

<table cellspacing="0" class="tabControl">
  <thead>
    <tr>
      <?php if ($_tab == 'props'): ?>
      <th class="tabTitleActive">Настройки</th>
      <?php else: ?>
      <th class="tabTitle"><?php echo link_to('Настройки', 'game/show?id='.$_game->id.'&tab=props'); ?></th>
      <?php endif; ?>

      <?php if ($_tab == 'teams'): ?>
      <th class="tabTitleActive">Регистрация команд</th>
      <?php else: ?>
      <th class="tabTitle"><?php echo link_to('Регистрация команд', 'game/show?id='.$_game->id.'&tab=teams'); ?></th>
      <?php endif; ?>

      <?php if ($_tab == 'tasks'): ?>
      <th class="tabTitleActive">Задания</th>
      <?php else: ?>
      <th class="tabTitle"><?php echo link_to('Задания', 'game/show?id='.$_game->id.'&tab=tasks'); ?></th>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="4" class="tabSheet">
        <?php
          $partial = null;
          if     ($_tab == 'props') $partial = 'gameProps';
          elseif ($_tab == 'teams') $partial = 'gameRegistration';
          elseif ($_tab == 'tasks') $partial = 'gameTasks';

          if (isset($partial))
              include_partial($partial, array(
                  '_game' => $_game,
                  '_retUrlRaw' => $retUrlRaw,
                  '_sessionCanManage' => $_sessionCanManage,
                  '_sessionIsModerator' => $_sessionIsModerator,
                  '_teamStates' => isset($_teamStates) ? $_teamStates : null,
                  '_gameCandidates' => isset($_gameCandidates) ? $_gameCandidates : null,
                  '_tasks' => isset($_tasks) ? $_tasks : null
              ));
          else
              echo decorate_div('danger', 'Нет такой вкладки - '.$_tab);
        ?>
      </td>
    </tr>
  </tbody>
</table>