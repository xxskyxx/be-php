<?php echo render_breadcombs(array('Модерирование')) ?>

<h2>Модерирование</h2>

<?php
if ($_isAdmin)
{
render_h3_inline_begin('Системные настройки');
echo decorate_span('safeAction', link_to('Редактировать', 'moderation/edit'));
render_h3_inline_end();
}
?>

<h4>Реквизиты сайта</h4>
<?php
$width = get_text_block_size_ex('Адрес администраторов:');
render_property('Название сайта:', $_settings->site_name, $width);
render_property('Домен сайта:', $_settings->site_domain, $width);
render_property('Адрес администраторов:', $_settings->contact_email_addr, $width);
?>
<?php if ($_isAdmin): ?>
<h4>Отправка уведомлений</h4>
<?php
render_property('Обратный адрес:', $_settings->notify_email_addr, $width);
render_property('SMTP-сервер:', $_settings->smtp_host, $width);
render_property('Порт:', $_settings->smtp_port, $width);
if (($_settings->smtp_security !== null) && ($_settings->smtp_security !== ''))
{
render_property('Шифрование:', $_settings->smtp_security, $width);
}
if (($_settings->smtp_login !== null) && ($_settings->smtp_login !== ''))
{
  render_property('Аккаунт:', $_settings->smtp_login, $width);
  render_property('Пароль:', $_settings->smtp_password, $width);
}
echo decorate_div('spaceBefore', decorate_span('safeAction', link_to('Отправить тестовое уведомление на '.$_settings->contact_email_addr, 'moderation/SMTPTest')));
?>
<?php endif ?>

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
