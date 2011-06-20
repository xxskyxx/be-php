<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
$backLinkEncoded = Utils::encodeSafeUrl(url_for('team/index'));
?>

<h2>Существующие команды</h2>

<?php if ($teamModerator): ?>
<div>
  <?php echo link_to('Создать новую команду', 'team/new') ?>
</div>
<?php else: ?>
<div>
  <?php echo link_to('Подать заявку на создание команды', 'teamCreateRequest/new') ?>
</div>
<?php endif ?>
<?php if ($teamModerator): ?>
<div class="info">
  Вы можете модерировать любую команду.
</div>
<?php endif; ?>
<div class="spaceAfter"></div>

<?php if (!$teams): ?>
<div class="info">
  Пока еще не создано ни одной команды.
</div>
<?php else: ?>
<table cellspacing="0">
  <thead>
    <tr>
      <th rowspan="2">Название</th>
      <th colspan="2">Состав</th>
      <th rowspan="2">Вы...</th>
    </tr>
    <tr>
      <td>Игроков</td>
      <td>Заявок</td>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($teams as $currTeam): ?>
    <?php
    $currIsLeader    = $currTeam->isLeader($sessionWebUser);
    $currIsPlayer    = $currTeam->isPlayer($sessionWebUser);
    $currIsCandidate = $currTeam->isCandidate($sessionWebUser);
    $currCanManage   = $currTeam->canBeManaged($sessionWebUser);
    ?>
    <tr>
      <td>
        <?php echo link_to($currTeam->name, url_for('team/show?id='.$currTeam->id)) ?>
      </td>
      <td>
        <?php echo $currTeam->teamPlayers->count(); ?>
      </td>
      <td>
        <?php echo $currTeam->teamCandidates->count(); ?>
      </td>
      <td>
        <?php if ($currIsLeader): ?>
        <span class="warn">Капитан</span>
        <?php elseif ($currIsPlayer): ?>
        <span class="safe">Рядовой</span>
        <?php elseif ($currIsCandidate): ?>
        <span class="safeAction">
          <?php
          echo 'Новобранец ';
          echo Utils::buttonTo('Передумать', 'team/cancelJoin?id='.$currTeam->id.'&userId='.$sessionWebUser->id.'&returl='.$backLinkEncoded, 'post');
          ?>
        </span>
        <?php elseif ($currCanManage && !$currIsLeader): ?>
        <span class="warn">Модератор</span>
        <?php else: ?>
        <span class="indentAction"><?php echo Utils::buttonTo('Вербоваться', 'team/postJoin?id='.$currTeam->id.'&userId='.$sessionWebUser->id.'&returl='.$backLinkEncoded, 'post'); ?></span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>


<?php if (($teamCreateRequests !== false) && ($teamCreateRequests->count() > 0)): ?>
<h2>Заявки на создание команд</h2>
<table cellspacing="0">
  <thead>
    <tr>
      <th>Автор</th>
      <th>Название</th>
      <th>Обоснование</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($teamCreateRequests as $teamCreateRequest): ?>
    <tr>
      <td>
        <span class="safeAction"><?php echo Utils::buttonTo('Отклонить', 'teamCreateRequest/delete?id='.$teamCreateRequest->id, 'post') ?></span>
        <?php if ($teamModerator): ?>
        <span class="warnAction"><?php echo Utils::buttonTo('Создать', 'teamCreateRequest/acceptManual?id='.$teamCreateRequest->id, 'post', 'Подтвердить создание команды '.$teamCreateRequest->name.' ('.$teamCreateRequest->WebUser->login.' будет назначен ее капитаном) ?') ?></span>
        <?php endif ?>
        <?php echo link_to($teamCreateRequest->WebUser->login, 'webUser/show?id='.$teamCreateRequest->web_user_id, array('target' => 'new')); ?>
      </td>
      <td><?php echo $teamCreateRequest->name ?></td>
      <td><?php echo $teamCreateRequest->description ?></td>      
    </tr>
    <?php endforeach ?>
  </tbody>
</table>
<?php endif ?>
