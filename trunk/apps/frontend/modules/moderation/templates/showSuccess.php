<?php render_breadcombs(array('Модерирование')) ?>

<h2>Модерирование</h2>

<?php
if ($_isAdmin)
{
  render_h3_inline_begin('Системные настройки');
  echo ' '.decorate_span('safeAction', link_to('Редактировать', 'moderation/edit'));
  render_h3_inline_end();
}
?>

<?php if ($_isAdmin): ?>
<h4>Реквизиты сайта</h4>
<?php
$width = get_text_block_size_ex('Создание команд по почте:');
render_named_line($width, 'Название сайта:', $_settings->site_name);
render_named_line($width, 'Домен сайта:', $_settings->site_domain);
render_named_line($width, 'Адрес администраторов:', $_settings->contact_email_addr);
?>
<h4>Модерация</h4>
<?php
render_named_line($width, 'Создание команд по почте:', $_settings->email_team_create ? decorate_span('info', 'Разрешено') : 'Не разрешено');
render_named_line($width, 'Создание игр по почте:', $_settings->email_game_create ? decorate_span('info', 'Разрешено') : 'Не разрешено');
render_named_line($width, 'Быстрое создание команд:', $_settings->fast_team_create ? decorate_span('warn', 'Разрешено') : 'Не разрешено');
render_named_line($width, 'Быстрая регистрация:', $_settings->fast_user_register ? decorate_span('warn', 'Разрешена') : 'Не разрешена');
?>
<h4>Отправка уведомлений</h4>
<?php
render_named_line($width, 'Обратный адрес:', $_settings->notify_email_addr);
render_named_line($width, 'SMTP-сервер:', $_settings->smtp_host);
render_named_line($width, 'Порт:', $_settings->smtp_port);
if (($_settings->smtp_security !== null) && ($_settings->smtp_security !== ''))
{
render_named_line($width, 'Шифрование:', $_settings->smtp_security);
}
if (($_settings->smtp_login !== null) && ($_settings->smtp_login !== ''))
{
  render_named_line($width, 'Аккаунт:', $_settings->smtp_login);
  render_named_line($width, 'Пароль:', $_settings->smtp_password);
}
echo "<p>\n".decorate_span('safeAction', link_to('Отправить тестовое уведомление на '.$_settings->contact_email_addr, 'moderation/SMTPTest'))."\n</p>\n";
?>
<?php endif ?>

<?php if ($_isAdmin): ?>
<?php
{
render_h3_inline_begin('Вы');
echo ' - администратор сайта и '.decorate_span('warn', 'обладаете полномочиями на любые действия');
render_h3_inline_end();
}
?>
<?php else: ?>

<?php   if ($_isWebUserModer): ?>
<h3>Пользователи</h3>
<p>
  Вы можете управлять анкетой <?php echo link_to('любого пользователя', 'webUser/index', array('target' => 'new'))?>.
</p>
<?php     if ($_isPermissionModer): ?>
<p>
  Вы также можете управлять полномочиями пользователей.
</p>
<?php     endif ?>
<?php   endif ?>

<?php   if ($_isFullTeamModer): ?>
<h3>Команды</h3>
<p>
  Вы можете управлять <?php echo link_to('любой командой', 'team/index', array('target' => 'new'))?>.
</p>
<p>
  Вы также можете управлять <?php echo link_to('заявками на создание команд', 'team/index', array('target' => 'new'))?>.
</p>
<?php   endif ?>
<?php   if (( ! $_isFullTeamModer) && ($_teamsUnderModeration->count() > 0)): ?>
<h3>Команды</h3>
<ul>
  <?php   foreach ($_teamsUnderModeration as $team): ?>
  <li><?php echo link_to($team->name, 'team/show?id='.$team->id) ?></li>
  <?php   endforeach ?>
</ul>
<?php   endif ?>

<?php   if ($_isFullGameModer): ?>
<h3>Игры</h3>
<p>
  Вы можете управлять <?php echo link_to('любой игрой', 'game/index', array('target' => 'new'))?>.
</p>
<?php   endif ?>
<?php   if (( ! $_isFullGameModer) && ($_gamesUnderModeration->count() > 0)): ?>
<h3>Игры</h3>
<ul>
  <?php   foreach ($_gamesUnderModeration as $game): ?>
  <li><?php echo link_to($game->name, 'game/show?id='.$game->id) ?></li>
  <?php   endforeach ?>
</ul>
<?php   endif ?>

<?php endif; ?>
