<?php
$retUrlRaw = Utils::encodeSafeUrl('webUser/show?id='.$_webUser->id);

render_breadcombs(array(
    link_to('Пользователи', 'webUser/index'),
    $_webUser->login
));
?>

<h2>Пользователь <?php echo $_webUser->login ?></h2>

<?php if ($_isSelf): ?>
<div class="info">Это Ваша анкета.</div>
<?php endif ?>

<?php
render_h3_inline_begin('Анкета');
if ($_isSelf || $_isModerator) echo ' '.decorate_span('safeAction', link_to('Редактировать', url_for('webUser/edit?id='.$_webUser->id)));
if ($_isModerator) echo '&nbsp'.decorate_span('dangerAction', link_to('Удалить пользователя', 'webUser/delete?id='.$_webUser->id, array('method' => 'delete', 'confirm' => 'Вы точно хотите удалить пользователя '.$_webUser->login.'?')));
render_h3_inline_end();
?>

<?php
//Формирование анкеты
$width = get_text_block_size_ex('Ф.И.(О.):');
render_named_line_if($_isModerator,
                     $width, 'Id:', $_webUser->id);
render_named_line   ($width, 'Имя:', $_webUser->login);
render_named_line   ($width, 'Ф.И.(О.):', $_webUser->full_name);
render_named_line   ($width, 'Регион:', $_webUser->getSafeRegion()->name);
render_named_line_if($_isSelf,
                     $width, 'Пароль:', decorate_span('warnAction', link_to('Сменить пароль', 'auth/changePassword', array('method' => 'get'))));
render_named_line_if($_isSelf || $_isModerator,
                     $width, 'E-Mail:', ($_webUser->email !== '') ? mail_to($_webUser->email) : 'не&nbsp;указан');
$propValue = $_webUser->is_enabled ? 'Активен' : decorate_span('warn', 'Заблокирован');
if ($_isModerator)
{
  $propValue .= $_webUser->is_enabled
      ? '&nbsp;'.decorate_span('warnAction', link_to('Блокировать', 'webUser/disable?id='.$_webUser->id.'&returl='.$retUrlRaw, array('method' => 'post')))
      : '&nbsp;'.decorate_span('warnAction', link_to('Разблокировать', 'webUser/enable?id='.$_webUser->id.'&returl='.$retUrlRaw, array('method' => 'post')));
}
render_named_line_if($_isModerator,
                     $width, 'Статус:', $propValue);
render_named_line_if($_isModerator,
                     $width, 'Тэг:', $_webUser->tag);
?>

<?php if ($_isSelf || $_isModerator || $_isPermissionModerator): ?>
<?php
render_h3_inline_begin('Разрешения и запреты');
if ($_isPermissionModerator) echo ' '.decorate_span('warnAction', link_to('Добавить', 'grantedPermission/new?webUserId='.$_webUser->id));
render_h3_inline_end();
?>

<ul>
  <?php if ($_webUser->grantedPermissions->count() <= 0): ?>
  <li>
    <div class="indent">У пользователя нет особых разрешений или запретов.</div>
  </li>
  <?php else: ?>
  <?php   foreach ($_webUser->grantedPermissions as $grantedPermission): ?>
  <li>
    <?php
    if ( ! $grantedPermission->deny)
    {
      echo decorate_span('info', 'Может');
      echo ' '.$grantedPermission->Permission->description;
      echo ($grantedPermission->filter_id == 0) ? '' : ' с номером #'.$grantedPermission->filter_id;
      echo $_isPermissionModerator
          ? '. '.decorate_span('warnAction', link_to('Лишить', 'grantedPermission/delete?id='.$grantedPermission->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'confirm' => 'Вы точно хотите лишить пользователя '.$_webUser->login.' права '.$grantedPermission->Permission->description.'?')))
          : '';
    }
    else
    {
      echo decorate_span('warn', 'Запрещено');
      echo ' '.$grantedPermission->Permission->description;
      echo ($grantedPermission->filter_id == 0) ? '' : ' с номером #'.$grantedPermission->filter_id;
      echo $_isPermissionModerator
          ? '. '.decorate_span('warnAction', link_to('Отозвать', 'grantedPermission/delete?id='.$grantedPermission->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'confirm' => 'Вы точно хотите снять с пользователя '.$_webUser->login.' запрет '.$grantedPermission->Permission->description.'?')))
          : '';
    }
    ?>
  </li>
  <?php   endforeach; ?>
  <?php endif; ?>
</ul>
<?php endif; ?>