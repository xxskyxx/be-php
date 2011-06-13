<h2>Подача командной заявки на игру <?php echo $game->name ?></h2>

<div>
  <span class="info">Показаны только те команды, от имени которых Вы можете подавать заявку</span>, и которые еще не подавали заявки или не регистрировались на игру.
</div>
<div class="spaceBefore">
  Выберите одну из команд (нажмите на ссылку):
</div>
<?php foreach ($teamList as $team): ?>
<div>
  <span class="safeAction"><?php echo link_to($team->name, 'game/postJoin'.'?id='.$game->id.'&teamId='.$team->id.'&returl='.$retUrl, array ('method' => 'post')); ?></span>
</div>
<?php endforeach; ?>