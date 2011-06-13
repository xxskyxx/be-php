<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
$backLinkEncoded = Utils::encodeSafeUrl(url_for('team/index'));
?>

<h2>Все команды</h2>

<?php if ($sessionWebUser->can(Permission::GAME_MODER, 0)): ?>
<div class="spaceAfter">
  <?php echo link_to('Создать новую команду', 'team/new') ?>
</div>
<?php endif; ?>

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
      <th rowspan="2">Вы&nbsp;в&nbsp;составе</th>
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
        <span class="warnAction">
          <?php echo link_to('Капитан', url_for('team/show?id='.$currTeam->id)) ?>
        </span>
        <?php elseif ($currIsPlayer): ?>
        <span class="safeAction">
          <?php echo link_to('Рядовой', url_for('team/show?id='.$currTeam->id)) ?>
        </span>
        <?php elseif ($currIsCandidate): ?>
        <span class="safeAction">
          <?php echo 'Новобранец' ?>
          <?php echo Utils::buttonTo('Передумать', 'team/cancelJoin?id='.$currTeam->id.'&userId='.$sessionWebUser->id.'&returl='.$backLinkEncoded, 'post'); ?>
        </span>
        <?php else: ?>
        <span class="indentAction">
          <?php echo Utils::buttonTo('Вербоваться', 'team/postJoin?id='.$currTeam->id.'&userId='.$sessionWebUser->id.'&returl='.$backLinkEncoded, 'post'); ?>
        </span>
        <?php endif; ?>

        <?php if ($currCanManage && !$currIsLeader): ?>
        <span class="info">Руководитель</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
