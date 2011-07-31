<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
$sessionIsSelf = ($webUser->id == $sessionWebUser->id);
$sessionCanUpdate = $webUser->canBeManaged($sessionWebUser);
$backLinkEncoded = Utils::encodeSafeUrl('webUser/show?id='.$webUser->id);

render_breadcombs(array(
    link_to('Пользователи', 'webUser/index'),
    $webUser->login
));
?>

<h2>Пользователь <?php echo $webUser->login ?></h2>

<?php if ($sessionIsSelf): ?>
<div class="info">Это ваша анкета.</div>
<?php endif ?>

<?php
render_h3_inline_begin('Анкета');
echo decorate_span('safeAction', link_to('Редактировать', url_for('webUser/edit?id='.$webUser->id)));
echo decorate_span('dangerAction', link_to('Удалить пользователя', 'webUser/delete?id='.$webUser->id, array('method' => 'delete', 'confirm' => 'Вы точно хотите удалить пользователя '.$webUser->login.'?')));
render_h3_inline_end();
?>

<?php
//Формирование анкеты
$width = get_text_block_size_ex('Полное имя:');
render_property_if($sessionCanUpdate, 'No:', $webUser->id, $width);
render_property('Имя:', $webUser->login, $width);
render_property_if($sessionIsSelf, 'Пароль:', decorate_span('warnAction', link_to('Сменить пароль', 'auth/changePassword', array('method' => 'get'))), $width);
render_property('Полное имя:', $webUser->full_name, $width);
render_property('E-Mail:', ($webUser->email !== '') ? mail_to($webUser->email) : '', $width);
render_property('Статус:', $webUser->is_enabled ? 'Активен' : 'Заблокирован', $width);
render_property_if(WebUser::isModerator($sessionWebUser), 'Тэг:', $webUser->tag, $width);
?>

<?php if ($sessionWebUser->can(Permission::PERMISSION_MODER, $sessionWebUser->id)): ?>
<h3>Разрешения и запреты</h3>
<ul>
  <?php if ($webUser->grantedPermissions->count() <= 0): ?>
  <li>
    <div class="indent">У пользователя нет особых разрешений или запретов.</div>
  </li>
  <?php else: ?>
  <?php   foreach ($webUser->grantedPermissions as $grantedPermission): ?>
  <li>
    <?php
    if ( ! $grantedPermission->deny)
    {
      echo decorate_span('info', 'Имеет право');
      echo ' '.$grantedPermission->Permission->description;
      echo ($grantedPermission->filter_id == 0) ? '' : ' с номером #'.$grantedPermission->filter_id;
      echo '. '.decorate_span('warnAction', link_to('Лишить', 'grantedPermission/delete?id='.$grantedPermission->id.'&returl='.$backLinkEncoded, array('method' => 'delete', 'confirm' => 'Вы точно хотите лишить пользователя '.$webUser->login.' права '.$grantedPermission->Permission->description.'?')));
    }
    else
    {
      echo decorate_span('warn', 'Запрещено');
      echo ' '.$grantedPermission->Permission->description;
      echo ($grantedPermission->filter_id == 0) ? '' : ' с номером #'.$grantedPermission->filter_id;
      echo '. '.decorate_span('warnAction', link_to('Отозвать', 'grantedPermission/delete?id='.$grantedPermission->id.'&returl='.$backLinkEncoded, array('method' => 'delete', 'confirm' => 'Вы точно хотите снять с пользователя '.$webUser->login.' запрет '.$grantedPermission->Permission->description.'?')));
    }
    ?>
  </li>
  <?php   endforeach; ?>
  <?php endif; ?>
  <li>
    <span class="safeAction"><?php echo link_to('Наделить правом или назначить запрет', 'grantedPermission/new?webUserId='.$webUser->id) ?></span>
  </li>
</ul>
<?php endif; ?>