<?php echo render_breadcombs(array('Команды')); ?>

<h2>Команды</h2>

<div class="spaceAfter">
  <?php if ($_isModerator): ?>
  <div><?php echo link_to('Создать новую команду', 'team/new') ?></div>
  <?php else: ?>
  <div><?php echo link_to('Подать заявку на создание команды', 'teamCreateRequest/new') ?></div>
  <?php endif; ?>
</div>

<?php if ($_teams->count() > 0): ?>
<h3>Существующие</h3>
<ul>
  <?php $sessionWebUser = $sf_user->getSessionWebUser()->getRawValue() ?>
  <?php foreach ($_teams as $team): ?>
  <li>
    <?php
    $isLeader = $team->isLeader($sessionWebUser);
    $isPlayer = $team->isPlayer($sessionWebUser);
    $isCandidate = $team->isCandidate($sessionWebUser);
    ?>
    <div class="<?php
                $class = 'indent';
                if     ($isLeader)    $class = 'warn';
                elseif ($isPlayer)    $class = 'info';
                elseif ($isCandidate) $class = 'warn';
                echo $class;
                ?>">
    <?php
    echo link_to(($team->full_name !== '') ? $team->full_name : $team->name, 'team/show?id='.$team->id);
    echo ($team->full_name === '') ? '' : ' ('.$team->name.')';
    if     ($isLeader)    echo ' - Вы капитан';
    elseif ($isPlayer)    echo ' - Вы игрок';
    elseif ($isCandidate) echo ' - Вы подали заявку в состав';
    ?>
    </div>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if ($_teamCreateRequests->count() > 0): ?>
<h3>Заявки на создание</h3>
<ul>
  <?php foreach ($_teamCreateRequests as $teamCreateRequest): ?>
  <li>
    <div>
      <?php
      echo $teamCreateRequest->name;
      echo '&nbsp'.decorate_span('safeAction', link_to('Отменить', 'teamCreateRequest/delete?id='.$teamCreateRequest->id, array('method' => 'post')));
      echo $_isModerator ? '&nbsp'.decorate_span('warnAction', link_to('Создать', 'teamCreateRequest/acceptManual?id='.$teamCreateRequest->id, array('method' => 'post', 'confirm' => 'Подтвердить создание команды '.$teamCreateRequest->name.' ('.$teamCreateRequest->WebUser->login.' будет назначен ее капитаном) ?'))) : '';
      echo ', '.link_to($teamCreateRequest->WebUser->login, 'webUser/show?id='.$teamCreateRequest->web_user_id).':&nbsp;'.$teamCreateRequest->description;
      ?>
    </div>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>