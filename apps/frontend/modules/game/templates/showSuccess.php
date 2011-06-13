<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
$sessionIsManager = $game->canBeManaged($sessionWebUser);
$backLinkEncoded = Utils::encodeSafeUrl('game/show?id='.$game->id);
?>

<h2>Игра <?php echo $game->name ?></h2>
<?php echo link_to('Вернуться к списку игр', 'game/index') ?>
<?php if ($sessionIsManager): ?>
<div class="info">
  Вы руководитель этой игры.
</div>
<?php else: ?>
<div class="warn">
  Вы не можете редактровать игру, так как не являетесь ее руководителем.
</div>
<?php endif; ?>

<h3>Параметры</h3>
<div>
  <span class="safeAction"><?php echo link_to('Состояние и управление игрой', 'gameStats/status?id='.$game->id) ?></span>
</div>
<div class="spaceAfter">
</div>

<table cellspacing="0">
  <tbody>
    <?php if (Game::isModerator($sessionWebUser)): ?>
    <tr>
      <th>No</th><td><?php echo $game->id ?></td>
    </tr>
    <?php endif; ?>
    <tr>
      <th>Название</th><td><?php echo $game->name ?></td>
    </tr>
    <tr>
      <th>Описание</th><td>см.&nbsp;<?php echo link_to('афишу', 'game/info?id='.$game->id, array ('target' => 'new')) ?></td>
    </tr>
    <tr>
      <th>Организаторы</th><td><?php echo ($game->team_id <= 0) ? $game->getTeamBackupName() : link_to($game->Team->name, 'team/show?id='.$game->Team->id, array ('target' => 'new')); ?></td>
    </tr>
    <tr>
      <th>Брифинг</th><td><?php echo $game->start_briefing_datetime ?></td>
    </tr>
    <tr>
      <th>Начало игры</th><td><?php echo $game->start_datetime ?></td>
    </tr>
    <tr>
      <th>Окончание игры</th><td><?php echo $game->stop_datetime ?></td>
    </tr>
    <tr>
      <th>Награждение</th><td><?php echo $game->finish_briefing_datetime ?></td>
    </tr>
    <tr>
      <th>Длительность игры</th><td><?php echo $game->time_per_game ?>&nbsp;мин</td>
    </tr>
    <tr>
      <th>Длительность задания</th><td><?php echo $game->time_per_task ?>&nbsp;мин</td>
    </tr>
    <tr>
      <th>Интервал между подсказками</th><td><?php echo $game->time_per_tip ?>&nbsp;мин</td>
    </tr>
    <tr>
      <th>Неверных ответов не более</th><td><?php echo $game->try_count ?></td>
    </tr>
    <?php if ($sessionIsManager): ?>
    <tr>
      <td colspan="2">Остальные параметры - <?php echo link_to('в редакторе', 'game/edit?id='.$game->id) ?>.</td>
    </tr>
    <?php else: ?>
    <tr>
      <td colspan="2">Остальные параметры доступны только руководителю.</td>
    </tr>
    <?php endif; ?>
  </tbody>
  <?php if ($sessionIsManager): ?>
  <tfoot>
    <tr>
      <td colspan="2">
        <span class="safeAction"><?php echo link_to('Редактировать', 'game/edit?id='.$game->id) ?></span>
        <span class="dangerAction"><?php echo Utils::buttonTo('Удалить игру', 'game/delete?id='.$game->id, 'delete', 'Вы точно хотите удалить игру '.$game->name.'?'); ?></span>
      </td>
    </tr>
  </tfoot>
  <?php endif; ?>
</table>

<h3>Зарегистрированные команды</h3>
<?php if ($sessionIsManager): ?>
<div class="spaceAfter">
  <span class="safeAction"><?php echo link_to('Зарегистрировать команду', 'game/addTeamManual?id='.$game->id.'&returl='.$backLinkEncoded, array('method' => 'post')); ?></span>
</div>
<?php endif; ?>
<?php $teamStates = $game->teamStates; ?>
<?php if ($teamStates->count() <= 0): ?>
<div>
  <span class="warn">Нет участвующих команд.</span>
</div>
<?php else: ?>
<table cellspacing="0">
  <thead>
    <tr>
      <th>Команда</th>
      <th>Задержка старта, мин</th>
      <th>Действия</th>
    </tr>
  </thead>
  <?php foreach($teamStates as $teamState): ?>
  <tbody>
    <tr>
      <td><?php echo link_to($teamState->Team->name, 'team/show?id='.$teamState->team_id, array ('target' => 'new')); ?></td>
      <td><?php echo $teamState->start_delay.'&nbsp;мин' ?></td>
      <td>
        <span class="safeAction">
          <?php echo link_to('Настройки', 'teamState/show?id='.$teamState->id).' '; ?>
        </span>
        <?php if ($sessionIsManager): ?>
        <span class="dangerAction">
          <?php echo Utils::buttonTo('Снять с игры', 'Game/removeTeam?id='.$game->id.'&teamId='.$teamState->team_id.'&returl='.$backLinkEncoded, 'post', 'Вы точно хотите снять команду '.$teamState->Team->name.' с игры '.$game->name.'?'); ?>
        </span>
        <?php endif; ?>
      </td>
    </tr>
  </tbody>
  <?php endforeach; ?>
</table>
<?php endif; ?>

<h3>Заявки на участие</h3>
<?php $candidates = $game->gameCandidates; ?>
<?php if ($candidates->count() <= 0): ?>
<div>
  <span class="indent">Нет активных заявок.</span>
</div>
<?php else: ?>
<ul>
  <?php foreach($candidates as $candidate): ?>
  <li>
    <?php echo link_to($candidate->Team->name, 'team/show?id='.$candidate->team_id) ?>
    <?php if ($sessionIsManager): ?>  
      <span class="safeAction"><?php echo Utils::buttonTo('Отклонить', 'Game/cancelJoin?id='.$game->id.'&teamId='.$candidate->team_id.'&returl='.$backLinkEncoded, 'post', 'Отклонить заявку команды '.$candidate->Team->name.' на участие в игре '.$game->name.'?') ?></span>
      <span class="safeAction"><?php echo Utils::buttonTo('Утвердить', 'Game/addTeam?id='.$game->id.'&teamId='.$candidate->team_id.'&returl='.$backLinkEncoded, 'post', 'Принять команду '.$candidate->Team->name.' к участию в игре '.$game->name.'?') ?></span>
    <?php endif ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<h3>Задания</h3>
  <?php include_partial('tasksEditor', array('game' => $game, 'editable' => $sessionIsManager))?>