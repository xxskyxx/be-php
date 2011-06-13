<h2>Регистрация команды на игру <?php echo $game->name ?></h2>

<div>
  <span class="info">Если команды нет в списке</span>, значит она уже заявилась или зарегистрирована на эту игру.
</div>
<div class="spaceBefore">
  Выберите одну из команд (нажмите на ссылку):
</div>
<?php foreach ($teamList as $team): ?>
<div>
  <span class="safeAction"><?php echo link_to($team->name, 'game/addTeam'.'?id='.$game->id.'&teamId='.$team->id.'&returl='.$retUrl,array ('method' => 'post')); ?></span>
</div>
<?php endforeach; ?>
