<h2>Регистрация игрока в команду <?php echo $team->name ?></h2>

<div>
  <span class="info">Если пользователя нет в списке</span>, значит он подал заявку или входит в состав команды.
</div>
<?php if ($team->teamPlayers->count() == 0): ?>
<div>
  <span class="warn">Пользователь будет назначен капитаном</span>, так как в команде еще нет игроков.
</div>
<?php endif; ?>
<div class="spaceBefore">
  Выберите одного из пользователей (нажмите на ссылку):
</div>
<?php foreach ($webUsers as $webUser): ?>
<div>
  <span class="safeAction"><?php echo link_to($webUser->login, 'team/setPlayer'.'?id='.$team->id.'&userId='.$webUser->id.'&returl='.$retUrl, array ('method' => 'post')); ?></span>
</div>
<?php endforeach; ?>
