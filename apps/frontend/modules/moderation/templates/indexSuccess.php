<?php echo render_breadcombs(array('Модерирование')) ?>

<h2>Модерирование</h2>

<?php
if ($_isAdmin)
{
render_h3_inline_begin('Настройки сайта');
echo decorate_span('safeAction', link_to('Редактировать', 'moderation/editSettings'));
render_h3_inline_end();

$width = get_text_block_size_ex('E-mail для связи с администрацией сайта:');
render_property('E-mail для писем-уведомлений:', $_settings->notify_email_addr, $width);
render_property('E-mail для связи с администрацией сайта:', $_settings->contact_email_addr, $width);
}
?>

<?php if ($_isAdmin): ?>
<?php
{
render_h3_inline_begin('Вы');
echo '- администратор сайта и '.decorate_span('warn', 'обладаете полномочиями на любые действия');
render_h3_inline_end();
}
?>
<?php else: ?>

<?php   if ($_isWebUserModer): ?>
<h3>Пользователи</h3>
<div>Вы можете управлять анкетой <?php echo link_to('любого пользователя', 'webUser/index', array('target' => 'new'))?>.</div>
<?php     if ($_isPermissionModer): ?>
<div>Вы также можете управлять полномочиями пользователей.</div>
<?php     endif ?>
<?php   endif ?>

<?php   if ($_isFullTeamModer): ?>
<h3>Команды</h3>
<div>Вы можете управлять <?php echo link_to('любой командой', 'team/index', array('target' => 'new'))?>.</div>
<div>Вы также можете управлять <?php echo link_to('заявками на создание команд', 'team/index', array('target' => 'new'))?>.</div>
<?php   endif ?>
<?php   if (( ! $_isFullTeamModer) && ($_teamsUnderModeration->count() > 0)): ?>
<h3>Команды</h3>
<ul>
  <?php   foreach ($_teamsUnderModeration as $team): ?>
  <li>
    <?php echo link_to($team->name, 'team/show?id='.$team->id) ?>
  </li>
  <?php   endforeach ?>
</ul>
<?php   endif ?>

<?php   if ($_isFullGameModer): ?>
<h3>Игры</h3>
<div>Вы можете управлять <?php echo link_to('любой игрой', 'game/index', array('target' => 'new'))?>.</div>
<?php   endif ?>
<?php   if (( ! $_isFullGameModer) && ($_gamesUnderModeration->count() > 0)): ?>
<h3>Игры</h3>
<ul>
  <?php   foreach ($_gamesUnderModeration as $game): ?>
  <li>
    <?php echo link_to($game->name, 'game/show?id='.$game->id) ?>
  </li>
  <?php   endforeach ?>
</ul>
<?php   endif ?>

<?php endif; ?>
