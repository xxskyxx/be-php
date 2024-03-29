<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_game->name, 'game/show?id='.$_game->id),
    'Афиша'
));
$retUrlRaw = Utils::encodeSafeUrl('game/info?id='.$_game->id)
?>

<h1><?php echo $_game->name ?></h1>

<?php echo Utils::decodeBB($_game->description) ?>

<div class="hr"></div>
<?php if (($_canPostJoin) && ($_game->status < Game::GAME_ARCHIVED)): ?>
<p>
  <span class="safeAction"><?php echo link_to('Подать заявку на участие', 'game/postJoinManual?id='.$_game->id.'&returl='.$retUrlRaw, array('method' => 'post')); ?></span>
</p>
<?php endif ?>

<?php
$width = get_text_block_size_ex('Планируется заданий:');
render_named_line($width, 'Организаторы:', ($_game->team_id !== null) ? $_game->Team->name : $_game->getTeamBackupName());
render_named_line($width, 'Регион:', $_game->getRegionSafe()->name);
?>
<h4>Регламент</h4>
<div class="comment info">Время соответствует серверному</div>
<?php
render_named_line($width, 'Брифинг:', $_game->start_briefing_datetime);
render_named_line($width, 'Старт игры:', $_game->start_datetime);
render_named_line($width, 'Длительность игры:', Timing::intervalToStr($_game->time_per_game*60));
render_named_line($width, 'Остановка игры:', $_game->stop_datetime);
render_named_line($width, 'Подведение итогов:', $_game->finish_briefing_datetime);
?>
<h4>Задания</h4>
<?php
render_named_line($width, 'Планируется заданий:', $_game->tasks->count());
render_named_line($width, 'Времени на задание:', Timing::intervalToStr($_game->time_per_task*60));
render_named_line($width, 'Интервал подсказок:', Timing::intervalToStr($_game->time_per_tip*60));
?>
<?php if ($_game->status >= GAME::GAME_ARCHIVED): ?>
<div class="info">
  <h4>Игра завершена</h4>
</div>
<?php endif ?>

<?php if ($_game->teamStates->count() > 0): ?>
<h3>Участвуют</h3>
<ul>
<?php   foreach ($_game->teamStates as $teamState): ?>
  <li>
    <?php
    $teamName = ($teamState->Team->full_name !== '') ? $teamState->Team->full_name : $teamState->Team->name;
    if ($teamState->Team->isPlayer($sf_user->getSessionWebUser()->getRawValue()))
    {
      echo ($_game->status >= Game::GAME_ARCHIVED)
          ? decorate_span('info', link_to($teamName, 'team/show?id='.$teamState->team_id).' -&nbsp;'.link_to('перейти&nbsp;к&nbsp;итогам', 'gameControl/report?id='.$_game->id))
          : decorate_span('info', link_to($teamName, 'team/show?id='.$teamState->team_id).' -&nbsp;'.link_to('перейти&nbsp;к&nbsp;заданию', 'teamState/task?id='.$teamState->id));
    }
    else
    {
      echo link_to($teamName, 'team/show?id='.$teamState->team_id); 
    }
    echo ($teamState->Team->canBeManaged($sf_user->getSessionWebUser()->getRawValue()))
        ? ' '.decorate_span('warnAction', link_to('Отказаться', 'game/removeTeam?id='.$_game->id.'&teamId='.$teamState->team_id.'&returl='.$retUrlRaw, array('confirm' => 'Вы точно хотите снять команду "'.$teamName.'" с игры "'.$_game->name.'" ?')))
        : '';
    ?>
  </li>
<?php   endforeach; ?>
</ul>
<?php endif; ?>

<?php if ($_game->gameCandidates->count() > 0): ?>
<h3>Подали заявки</h3>
<ul>
<?php   foreach ($_game->gameCandidates as $gameCandidate): ?>
  <li>
    <?php
    $teamName = ($gameCandidate->Team->full_name !== '') ? $gameCandidate->Team->full_name : $gameCandidate->Team->name;
    echo ($gameCandidate->Team->isPlayer($sf_user->getSessionWebUser()->getRawValue()))
        ? decorate_span('info', link_to($teamName, 'team/show?id='.$gameCandidate->team_id))
        : link_to($teamName, 'team/show?id='.$gameCandidate->team_id);
    echo ($gameCandidate->Team->canBeManaged($sf_user->getSessionWebUser()->getRawValue()))
        ? ' '.decorate_span('safeAction', link_to('Отменить', 'game/cancelJoin?id='.$_game->id.'&teamId='.$gameCandidate->team_id.'&returl='.$retUrlRaw))
        : '';
    ?>
  </li>
<?php   endforeach; ?>
</ul>
<?php endif; ?>