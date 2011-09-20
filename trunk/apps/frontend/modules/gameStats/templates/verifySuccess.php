<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();

render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($game->name, 'game/show?id='.$game->id),
    link_to('Состояние', 'gameStats/status?id='.$game->id)
))
?>
<h2>Предстартовая проверка игры <?php echo $game->name ?></h2>

<?php $cannotStart = false; ?>

<?php if (isset($report['tasks'])):?>
<h3>Проверка заданий</h3>
<?php   foreach ($report['tasks'] as $taskId => $taskReport): ?>
<h4><?php echo link_to(Task::byId($taskId)->name, 'task/show?id='.$taskId, array('target' => 'new')) ?></h4>
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
<h4><?php echo link_to(Team::byId($teamId)->name, 'team/show?id='.$teamId, array('target' => 'new')) ?></h4>
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
<p>
  <?php if ($cannotStart): ?>
  <span class="dangerAction">Игра не может быть запущена, так как есть принципиальные проблемы.</span>
  <?php else:?>
  <span class="warnAction">Игру можно запустить, но в ходе проведения возможны организационные проблемы.</span>
</p>
<?php endif; ?>
<div>
  <?php if (!$cannotStart): ?>
  <span class="warnAction"><?php echo link_to('Запустить игру', 'gameStats/start?id='.$game->id.'&returl='.Utils::encodeSafeUrl(url_for('gameStats/status?id='.$game->id)), array('method' => 'post', 'confirm' => 'Запустить игру '.$game->name.'?')); ?></span>
  <?php endif; ?>
  <span class="safeAction"><?php echo link_to('Повторить проверку', 'gameStats/verify?id='.$game->id.'&returl='.Utils::encodeSafeUrl(url_for('gameStats/status?id='.$game->id)), array('method' => 'post')); ?></span>
</div>