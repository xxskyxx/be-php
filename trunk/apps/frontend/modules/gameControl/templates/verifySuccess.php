<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();

render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_game->name, 'game/show?id='.$_game->id),
    link_to('Состояние', 'gameControl/pilot?id='.$_game->id)
))
?>
<h2>Предстартовая проверка игры <?php echo $_game->name ?></h2>

<?php $cannotStart = false; ?>

<?php if (isset($report['tasks'])):?>
<h3>Проверка заданий</h3>
<?php   foreach ($report['tasks'] as $taskId => $taskReport): ?>
<div><?php echo link_to(Task::byId($taskId)->name, 'task/show?id='.$taskId, array('target' => 'new')).':' ?></div>
<ul>
<?php    foreach ($taskReport as $reportLine): ?>
<?php      if ($reportLine['errLevel'] == Game::VERIFY_ERR):?>
  <li><div class="warn"><?php echo $reportLine['msg']; $cannotStart = true; ?></div></li>
<?php       else:?>
  <li><div class="info"><?php echo $reportLine['msg'] ?></div></li>
<?php      endif; ?>
<?php     endforeach; ?>
</ul>
<?php   endforeach; ?>
<?php endif; ?>

<?php if (isset($report['teams'])):?>
<h3>Проверка команд</h3>
<?php   foreach ($report['teams'] as $teamId => $teamReport): ?>
<div><?php echo link_to(Team::byId($teamId)->name, 'team/show?id='.$teamId, array('target' => 'new')).':' ?></div>
<ul>
<?php    foreach ($teamReport as $reportLine): ?>
<?php      if ($reportLine['errLevel'] == Game::VERIFY_ERR):?>
  <li><div class="warn"><?php echo $reportLine['msg']; $cannotStart = true; ?></div></li>
<?php       else:?>
  <li><div class="info"><?php echo $reportLine['msg'] ?></div></li>
<?php      endif; ?>
<?php     endforeach; ?>
</ul>
<?php   endforeach; ?>
<?php endif; ?>

<h3>Выводы:</h3>
<div>
  <?php if ($cannotStart): ?>
  <div class="danger">Игра не может быть запущена, так как есть принципиальные проблемы.</div>
  <?php else:?>
  <div class="warn">Игру можно запустить, но в ходе проведения возможны организационные проблемы.</div>
  <?php endif; ?>
</div>
<p>
  <?php if ( ! $cannotStart): ?>
  <span class="warnAction"><?php echo link_to('Запустить игру', 'gameControl/start?id='.$_game->id.'&returl='.Utils::encodeSafeUrl(url_for('gameControl/pilot?id='.$_game->id)), array('method' => 'post', 'confirm' => 'Запустить игру '.$_game->name.'?')); ?></span>
  <?php endif; ?>
  <span class="safeAction"><?php echo link_to('Повторить проверку', 'gameControl/verify?id='.$_game->id.'&returl='.Utils::encodeSafeUrl(url_for('gameControl/pilot?id='.$_game->id)), array('method' => 'post')); ?></span>
</p>
