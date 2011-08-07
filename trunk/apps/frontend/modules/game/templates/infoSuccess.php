<?php
echo render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($game->name, 'game/show?id='.$game->id)
));

$backLinkEncoded = Utils::encodeSafeUrl('game/info?id='.$game->id)
?>

<?php
if ($game->team_id > 0)
{
  $authors = $game->Team->getLeaders();
  $authorsStr = '';
  if ($authors)
  {
    foreach ($authors as $author)
    {
      if ($authorsStr !== '')
      {
        $authorsStr .= ', ';
      }
      $authorsStr .= $author->WebUser->login;
    }
    if ($authors->count() > 1)
    {
      $authorsStr .= ' представляют';
    }
    else
    {
      $authorsStr .= ' представляет';
    }
?>
<h3><?php echo $authorsStr ?></h3>
<?php    
  }
}
?>

<h1><?php echo $game->name ?></h1>

<?php if ($game->team_id > 0): ?>
<h5>При содействии команды <?php echo $game->Team->full_name ?></h5>
<?php
$actors = $game->Team->getPlayersStrict();
$actorsStr = '';
if ($actors)
{
  foreach ($actors as $actor)
  {
    if ($actorsStr !== '')
    {
      $actorsStr .= ', ';
    }    
    $actorsStr .= $actor->WebUser->login;
  }
?>
<h5>В главных ролях: <?php echo $actorsStr ?></h5>
<?php
}
?>
<?php else: ?>
<h5 style="color: SkyBlue">It was along time ago in a galaxy far far away...</h5>
<?php endif; ?>

<div class="spaceBefore">
  <?php echo Utils::decodeBB($game->description) ?>
</div>
<div class="spaceBefore">
  <span class="safeAction"><?php echo link_to('Подать заявку на участие', 'game/postJoinManual?id='.$game->id.'&returl='.$backLinkEncoded, array('method' => 'post')); ?></span>
</div>

<h3>Регламент:</h3>
<div>
  <table cellspacing="0" class="noBorder">
    <tbody>
      <tr>
        <th><span class="indent">Брифинг:</span></th>
        <td><span class="indent"><?php echo $game->start_briefing_datetime ?></span></td>
      </tr>

      <tr>
        <th><span class="warn">Старт игры:</span></th>
        <td><span class="warn"><?php echo $game->start_datetime ?></span></td>
      </tr>

      <tr>
        <th><span class="info">Времени на игру:</span></th>
        <td><span class="info"><?php echo Timing::intervalToStr($game->time_per_game*60) ?></span></td>
      </tr>

      <tr>
        <th><span class="indent">Остановка игры:</span></th>
        <td><span class="indent"><?php echo $game->stop_datetime ?></span></td>
      </tr>

      <tr>
        <th><span class="indent">Подведение итогов:</span></th>
        <td><span class="indent"><?php echo $game->finish_briefing_datetime ?></span></td>
      </tr>
      
      <tr>
        <th colspan="2">
          <h4>Задания:</h4>
        </th>
      </tr>
        
      <tr>
        <th><span class="indent">Планируется заданий:</span></th>
        <td><span class="indent"><?php echo $game->tasks->count() ?></span></td>
      </tr>
      
      <tr>
        <th><span class="indent">Времени на задание:</span></th>
        <td><span class="indent"><?php echo Timing::intervalToStr($game->time_per_task*60) ?></span></td>
      </tr>
      
      <tr>
        <th><span class="indent">Интервал подсказок:</span></th>
        <td><span class="indent"><?php echo Timing::intervalToStr($game->time_per_tip*60) ?></span></td>
      </tr>
      
    </tbody>
  </table>
</div>

<?php if ($game->teamStates->count() > 0): ?>
<h3>Участвуют команды:</h3>
<ul>
<?php   foreach ($game->teamStates as $teamState): ?>
  <li>
    <?php
    echo ($teamState->Team->isPlayer($sf_user->getSessionWebUser()->getRawValue()))
        ? decorate_span('info', link_to($teamState->Team->name, 'team/show?id='.$teamState->team_id).' -&nbsp;'.link_to('перейти&nbsp;к&nbsp;заданию', 'teamState/task?id='.$teamState->id))
        : link_to($teamState->Team->name, 'team/show?id='.$teamState->team_id);
    echo ($teamState->Team->canBeManaged($sf_user->getSessionWebUser()->getRawValue()))
        ? ' '.decorate_span('warnAction', link_to('Отказаться', 'game/removeTeam?id='.$game->id.'&teamId='.$teamState->team_id.'&returl='.$backLinkEncoded, array('confirm' => 'Вы точно хотите снять команду '.$teamState->Team->name.' с игры '.$game->name.' ?')))
        : '';
    ?>
  </li>
<?php   endforeach; ?>
</ul>
<?php endif; ?>

<?php if ($game->gameCandidates->count() > 0): ?>
<h3>Подали заявки:</h3>
<ul>
<?php   foreach ($game->gameCandidates as $gameCandidate): ?>
  <li>
    <?php
    echo ($gameCandidate->Team->isPlayer($sf_user->getSessionWebUser()->getRawValue()))
        ? decorate_span('info', link_to($gameCandidate->Team->name, 'team/show?id='.$gameCandidate->team_id))
        : link_to($gameCandidate->Team->name, 'team/show?id='.$gameCandidate->team_id);
    echo ($gameCandidate->Team->canBeManaged($sf_user->getSessionWebUser()->getRawValue()))
        ? ' '.decorate_span('safeAction', link_to('Отозвать', 'game/cancelJoin?id='.$game->id.'&teamId='.$gameCandidate->team_id.'&returl='.$backLinkEncoded))
        : '';
    ?>
  </li>
<?php   endforeach; ?>
</ul>
<?php endif; ?>