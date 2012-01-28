<?php
$retUrlRaw = Utils::encodeSafeUrl(url_for('team/show?id='.$_team->id));

render_breadcombs(array(
    link_to('Команды', 'team/index'),
    $_team->name
));
?>

<h2>Команда <?php echo $_team->name ?></h2>

<?php
render_h3_inline_begin('Свойства');
if ($_sessionIsLeader || $_sessionIsModerator) echo ' '.decorate_span('safeAction', link_to('Редактировать', url_for('team/edit?id='.$_team->id)));
if ($_sessionIsModerator) echo '&nbsp;'.decorate_span('dangerAction', link_to('Удалить команду', 'team/delete?id='.$_team->id, array('method' => 'delete', 'confirm' => 'Вы точно хотите удалить команду '.$_team->name.'?')));
render_h3_inline_end();
?>

<?php
$width = get_text_block_size_ex('Полное название:');
render_named_line_if($_sessionIsModerator,
                     $width, 'Id:', $_team->id);
render_named_line   ($width, 'Название:', $_team->name);
render_named_line_if($_team->full_name !== '',
                     $width, 'Полное название:', $_team->full_name);
render_named_line   ($width, 'Регион:', $_team->getRegionSafe()->name);
?>

<?php if ($_sessionIsPlayer || $_sessionIsModerator): ?>
<?php
render_h3_inline_begin('Игроки');
if ($_sessionIsLeader || $_sessionIsModerator) echo ' '.decorate_span('safeAction', link_to('Вербовать нового', 'team/registerPlayer'.'?id='.$_team->id.'&returl='.$retUrlRaw));
render_h3_inline_end();
?>
<ul>
  <?php if ($_team->teamPlayers->count() > 0): ?>
  <?php   foreach ($_team->teamPlayers as $teamPlayer): ?>
  <li>
    <?php
    $webUser = $teamPlayer->WebUser->getRawValue();
    $leader = $teamPlayer->is_leader;
    echo decorate_span(
        ($leader ? 'warn' : 'indent'),
        link_to($webUser->login, 'webUser/show?id='.$webUser->id, array('target' => 'new')).($leader ? ',&nbsp;капитан' : ',&nbsp;рядовой')
    );
    echo ' ';
    if ($_sessionIsLeader || $_sessionIsModerator)
    {
      echo $leader
          ? decorate_span('safeAction', link_to('Разжаловать', 'team/setPlayer?id='.$_team->id.'&userId='.$webUser->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Отобрать у игрока '.$webUser->login.' полномочия капитана команды '.$_team->name.'?')))
          : decorate_span('warnAction', link_to('Повысить', 'team/setLeader?id='.$_team->id.'&userId='.$webUser->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Назначить игрока '.$webUser->login.' капитаном команды '.$_team->name.'?')));
      echo '&nbsp;';
      echo decorate_span(
          ($leader ? 'dangerAction' : 'warnAction'),
          link_to('Демобилизовать', 'team/unregister?id='.$_team->id.'&userId='.$webUser->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Отчислить игрока '.$webUser->login.' из команды '.$_team->name.'?'))
      );
    }
    ?>
  </li>
  <?php   endforeach; ?>
  <?php else: ?>
  <li><div class="warn">В команде нет игроков.</div></li>
  <?php endif; ?>
</ul>
<?php endif; ?>

<?php if (($_teamCandidates->count() > 0) || (!$_sessionIsPlayer && !$_sessionIsCandidate)): ?>
<?php
render_h3_inline_begin('Заявки в состав');
if (!$_sessionIsPlayer && !$_sessionIsCandidate) echo ' '.decorate_span('safeAction', link_to('Подать свою', 'team/postJoin'.'?id='.$_team->id.'&userId='.$_sessionWebUserId.'&returl='.$retUrlRaw, array('method' => 'post')));
render_h3_inline_end();
?>
<?php endif ?>
<ul>
  <?php if ($_teamCandidates->count() > 0): ?>
  <?php   foreach ($_teamCandidates as $teamCandidate): ?>
  <?php     $webUser = $teamCandidate->WebUser->getRawValue(); ?>
  <li>
    <?php echo link_to($webUser->login, 'webUser/show?id='.$webUser->id, array('target' => 'new')) ?>
    <?php   if ($_sessionIsLeader || $_sessionIsModerator): ?>
    <span class="warnAction"><?php echo link_to('Вербовать', 'team/setPlayer?id='.$_team->id.'&userId='.$webUser->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Утвердить '.$webUser->login.' в состав команды '.$_team->name.'?')) ?></span>
    <?php   endif; ?>
    <span class="safeAction"><?php echo link_to('Отменить', 'team/cancelJoin?id='.$_team->id.'&userId='.$webUser->id.'&returl='.$retUrlRaw, array('method' => 'post', 'confirm' => 'Отменить заявку '.$webUser->login.' в состав команды '.$_team->name.'?')) ?></span>
  </li>
  <?php   endforeach; ?>
  <?php endif; ?>
</ul>

<?php if ($_teamStates->count() > 0): ?>
<h3>Участие в играх</h3>
<ul>
<?php foreach ($_teamStates as $teamState): ?>
  <li>
    <?php
    switch ($teamState->Game->status)
    {
      case Game::GAME_STEADY:
      case Game::GAME_ACTIVE: $css = 'warn'; break;
      case Game::GAME_STEADY:
      case Game::GAME_ACTIVE: $css = 'info'; break;
      default:                $css = 'indent'; break;
    }
    ?>
    <div class="<?php echo $css ?>">
      <?php
      echo link_to($teamState->Game->name, 'game/show?id='.$teamState->game_id, array('target' => 'new'));
      {
        switch ($teamState->Game->status)
        {
          case Game::GAME_STEADY:
          case Game::GAME_ACTIVE:
            echo '&nbsp-&nbsp;идет&nbsp;сейчас';
            if ($_sessionIsPlayer)
            {
              echo ', '.link_to('перейти&nbsp;к&nbsp;игре', 'teamState/task?id='.$teamState->id, array('target' => 'new'));
            }
            break;
          case Game::GAME_STEADY:
          case Game::GAME_ACTIVE:
            echo '&nbsp-&nbsp;завершена';
            if ($_sessionIsPlayer)
            {
              echo ', '.link_to('посмотреть&nbsp;итоги', 'gameControl/report?id='.$teamState->game_id, array('target' => 'new'));
            }
            break;
          default:
            break;
        }
      }
      ?>
    </div>
  </li>
<?php endforeach; ?>
</ul>
<?php endif ?>

<?php if ($_games->count() > 0): ?>
<h3>Организация игр</h3>
<ul>
<?php foreach ($_games as $game): ?>
  <li>
    <?php
    switch ($game->status)
    {
      case Game::GAME_STEADY:
      case Game::GAME_ACTIVE: $css = 'warn'; break;
      case Game::GAME_STEADY:
      case Game::GAME_ACTIVE: $css = 'info'; break;
      default:                $css = 'indent'; break;
    }
    ?>
    <div class="<?php echo $css ?>">
      <?php
      echo link_to($game->name, 'game/show?id='.$game->id, array('target' => 'new'));
      {
        switch ($game->status)
        {
          case Game::GAME_STEADY:
          case Game::GAME_ACTIVE:
            echo '&nbsp-&nbsp;проводится&nbsp;сейчас';
            if ($_sessionIsPlayer)
            {
              echo ', '.link_to('перейти&nbsp;к&nbsp;состоянию', 'gameControl/pilot?id='.$game->id, array('target' => 'new'));
            }
            break;
          case Game::GAME_STEADY:
          case Game::GAME_ACTIVE:
            echo '&nbsp-&nbsp;завершена';
            if ($_sessionIsPlayer)
            {
              echo ', '.link_to('перейти&nbsp;к&nbsp;итогам', 'gameControl/report?id='.$game->id, array('target' => 'new'));
            }
            break;
          default:
            break;
        }
      }
      ?>
    </div>
  </li>
<?php endforeach; ?>
</ul>
<?php endif ?>