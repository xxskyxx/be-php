<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
$sessionIsLeader    = $team->isLeader($sessionWebUser);
$sessionIsPlayer    = $team->isPlayer($sessionWebUser);
$sessionIsCandidate = $team->isCandidate($sessionWebUser);
$sessionCanManage   = $team->canBeManaged($sessionWebUser)
?>

<h2>Команда <?php echo $team->name ?></h2>
<?php echo link_to('Вернуться к списку команд', 'team/index') ?>
<?php if ($sessionIsLeader || $sessionIsPlayer || $sessionIsCandidate): ?>
<div class="<?php if ($sessionIsLeader) { echo 'warn'; } else { echo 'info'; } ?>">
  <?php
  if     ($sessionIsLeader)    { echo 'Вы капитан этой команды.'; }
  elseif ($sessionIsPlayer)    { echo 'Вы игрок этой команды.'; }
  elseif ($sessionIsCandidate) { echo 'Вы подали заявку в состав этой команды.'; }
  ?>
</div>
<?php endif; ?>
<?php if ($sessionCanManage && !$sessionIsLeader): ?>
<div class="warn">
  Вы имеете право руководить этой командой.
</div>
<?php endif; ?>

<h3>Свойства</h3>
<table cellspacing="0">
  <tbody>
    <?php if ($sessionCanManage): ?>
    <tr>
      <th>No</th><td><?php echo $team->id ?></td>
    </tr>
    <?php endif; ?>
    <tr>
      <th>Название</th><td><?php echo $team->name ?></td>
    </tr>
    <tr>
      <th>Полное название</th><td><?php echo $team->full_name ?></td>
    </tr>
  </tbody>
  <?php if ($sessionCanManage): ?>
  <tfoot>
    <tr>
      <td colspan="2">
        <span class="safeAction"><?php echo link_to('Редактировать', 'team/edit?id='.$team->id) ?></span>
        <span class="dangerAction"><?php echo Utils::buttonTo('Удалить команду', 'team/delete?id='.$team->id, 'delete', 'Вы точно хотите удалить команду "'.$team->name.'"?'); ?></span>
      </td>
    </tr>
  </tfoot>
  <?php endif; ?>
</table>

<h3>Cостав</h3>
<?php if ($sessionCanManage): ?>
<div class="spaceAfter">
  <span class="safeAction"><?php echo link_to('Зарегистрировать нового участника', 'team/registerPlayer'.'?id='.$team->id.'&returl='.Utils::encodeSafeUrl('team/show?id='.$team->id)); ?></span>
</div>
<?php endif; ?>
<?php $playersList = $team->teamPlayers; ?>
<?php if ($playersList->count() <= 0): ?>
<div class="warn">
  В команде нет игроков.
</div>
<?php else: ?>
<ul>
  <?php foreach ($playersList as $currPlayer): ?>
  <?php
  $currPlayer = $currPlayer->WebUser->getRawValue();
  $currIsLeader = $team->isLeader($currPlayer);
  $currIsPlayer = $team->isPlayer($currPlayer);
  ?>
  <li>
    <span class="<?php echo $currIsLeader ? 'warn' : 'safe' ?>"><?php echo $currIsLeader ? 'Капитан' : 'Рядовой'?></span>
    <?php echo link_to($currPlayer->login, 'webUser/show?id='.$currPlayer->id) ?>
    <?php if ($sessionCanManage): ?>
    <?php   if ($currIsLeader): ?>
    <span class="safeAction"><?php echo Utils::buttonTo('Разжаловать', 'team/setPlayer?id='.$team->id.'&userId='.$currPlayer->id.'&returl='.Utils::encodeSafeUrl(url_for('team/show?id='.$team->id)), 'post', 'Отобрать у игрока '.$currPlayer->login.' полномочия капитана команды '.$team->name.'?') ?></span>
    <?php   else: ?>
    <span class="warnAction"><?php echo Utils::buttonTo('Повысить', 'team/setLeader?id='.$team->id.'&userId='.$currPlayer->id.'&returl='.Utils::encodeSafeUrl(url_for('team/show?id='.$team->id)), 'post', 'Назначить игрока '.$currPlayer->login.' капитаном команды '.$team->name.'?') ?></span>
    <?php   endif; ?>
    <span class="<?php echo $currIsLeader ? 'dangerAction' : 'warnAction' ?>"><?php echo Utils::buttonTo('Демобилизовать', 'team/unregister?id='.$team->id.'&userId='.$currPlayer->id.'&returl='.Utils::encodeSafeUrl(url_for('team/show?id='.$team->id)), 'post', 'Отчислить игрока '.$currPlayer->login.' из команды '.$team->name.'?') ?></span>
    <?php endif; ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<h3>Заявки в состав</h3>
<?php ($candidatesList = $team->teamCandidates) ?>
<?php if ($candidatesList->count() <= 0):?>
<div>
  <span class="indentAction">Нет активных заявок.</span>
</div>
<?php else: ?>
<ul>
  <?php foreach ($candidatesList as $currCandidate): ?>
  <?php   $currCandidate = $currCandidate->WebUser->getRawValue(); ?>
  <li>
    Новобранец <?php echo link_to($currCandidate->login, 'webUser/show?id='.$currCandidate->id) ?>
    <?php if ($sessionCanManage): ?>
    <span class="warnAction"><?php echo Utils::buttonTo('Вербовать', 'team/setPlayer?id='.$team->id.'&userId='.$currCandidate->id.'&returl='.Utils::encodeSafeUrl(url_for('team/show?id='.$team->id)), 'post', 'Утвердить игрока '.$currCandidate->login.' в состав команды '.$team->name.'?') ?></span>
    <span class="safeAction"><?php echo Utils::buttonTo('Оставить в запасе', 'team/cancelJoin?id='.$team->id.'&userId='.$currCandidate->id.'&returl='.Utils::encodeSafeUrl(url_for('team/show?id='.$team->id)), 'post', 'Отклонить заявку игрока '.$currCandidate->login.' в состав команды '.$team->name.'?') ?></span>
    <?php endif; ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<h3>Участие в играх</h3>
<?php if ($team->teamStates->count() == 0): ?>
<div>
  Команда не принята к участию ни в одной из игр.
</div>
<?php else: ?>
<?php   foreach ($team->teamStates as $teamState): ?>
<?php     if ($teamState->Game->isActive()): ?>
<div class="info">
  <?php
  echo link_to($teamState->Game->name, 'game/show?id='.$teamState->game_id);
  echo '&nbsp;-&nbsp;'.link_to('Вы&nbsp;играете', 'teamState/task?id='.$teamState->id);
  ?>
</div>
<?php     elseif ($teamState->Game->status == Game::GAME_ARCHIVED): ?>
<div class="indent">
  <?php
  echo link_to($teamState->Game->name, 'game/show?id='.$teamState->game_id);
  echo '&nbsp;-&nbsp;'.link_to('завершена', 'gameStats/report?id='.$teamState->game_id);
  ?>
</div>
<?php     else: ?>
<div class="indent">
  <?php echo link_to($teamState->Game->name, 'game/show?id='.$teamState->game_id); ?>
</div>
<?php     endif; ?>
<?php   endforeach; ?>
<?php endif; ?>

<h3>Заявки на игры</h3>
<?php if ($team->gameCandidates->count() == 0): ?>
<div>
  Команда не подавала заявок ни в одну из игр.
</div>
<?php else: ?>
<?php   foreach ($team->gameStates as $teamState): ?>
<div class="indent">
  <?php echo link_to($teamState->Game->name, 'game/show?id='.$teamState->game_id); ?>
</div>
<?php   endforeach; ?>
<?php endif; ?>

<h3>Организация игр</h3>
<?php if ($team->games->count() == 0): ?>
<div>
  Команда не организовала ни одной игры.
</div>
<?php else: ?>
<?php   foreach ($team->games as $game): ?>
<?php     if ($game->isActive()): ?>
<div class="info">
  <?php echo link_to($game->name, 'game/show?id='.$game->id); ?>
  <?php echo link_to('проводится сейчас', 'gameStats/status?id='.$game->id); ?>
</div>
<?php     elseif ($game->status == Game::GAME_ARCHIVED): ?>
<div class="info">
  <?php echo link_to($game->name, 'game/show?id='.$game->id); ?>
  <?php echo link_to('завершена', 'gameStats/report?id='.$game->id); ?>
</div>
<?php     else: ?>
<div class="indent">
  <?php echo link_to($game->name, 'game/show?id='.$game->id); ?>
</div>
<?php     endif; ?>
<?php   endforeach; ?>
<?php endif; ?>