<?php include_partial('header', array('teamState' => $_teamState)) ?>

<p>
  Ваша команда финишировала.
</p>

<?php if ($_teamState->Game->status <= Game::GAME_FINISHED): ?>
<p>
  Результаты игры будут опубликованы после подведения ее итогов, которое состоится <?php echo $_teamState->Game->finish_briefing_datetime ?>.
</p>
<?php else: ?>
<p>
  <?php echo link_to('Перейти к результатам игры', 'gameStats/report?id='.$_teamState->game_id) ?>
</p>
<?php endif ?>
