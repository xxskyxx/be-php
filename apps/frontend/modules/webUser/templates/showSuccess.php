<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
$sessionIsSelf = ($webUser->id == $sessionWebUser->id);
$sessionCanUpdate = $webUser->canBeManaged($sessionWebUser);
$backLinkEncoded = Utils::encodeSafeUrl('webUser/show?id='.$webUser->id);
?>

<h2>Пользователь <?php echo $webUser->login ?></h2>
<?php echo link_to('Перейти к списку пользователей', 'webUser/index') ?>
<?php if ($sessionIsSelf): ?>
<div class="info">
  Это ваша анкета.
</div>
<?php endif; ?>

<h3>Анкетные данные</h3>
<?php if ($sessionIsSelf): ?>
<div class="spaceBefore">
  <div class="spaceAfter">
    <span class="warnAction"><?php echo Utils::buttonTo('Сменить пароль', 'auth/changePassword', 'get'); ?></span>
  </div>
</div>
<?php endif; ?>
<table cellspacing="0">
  <tbody>
    <?php if ($sessionCanUpdate):?>
    <tr>
      <th>No</th><td><?php echo $webUser->id ?></td>
    </tr>
    <?php endif; ?>
    <tr>
      <th>Имя</th><td><?php echo $webUser->login ?></td>
    </tr>
    <tr>
      <th>Полное имя</th><td><?php echo $webUser->full_name ?></td>
    </tr>
    <tr>
      <th>E-Mail</th><td><?php echo mail_to($webUser->email) ?></td>
    </tr>
    <tr>
      <th>Статус</th><td><?php echo ($webUser->is_enabled ? 'Активен' : 'Заблокирован') ?></td>
    </tr>
    <?php if (WebUser::isModerator($sessionWebUser)): ?>
    <tr>
      <th>Тэг</th><td><?php echo $webUser->tag ?></td>
    </tr>
    <?php endif; ?>
    <?php if ($sessionCanUpdate):?>
    <tr>
      <td colspan="2">
        <div>
          <span class="safeAction"><?php echo link_to('Редактировать', url_for('webUser/edit?id='.$webUser->id)) ?></span>
          <span class="dangerAction"><?php echo Utils::buttonTo('Удалить пользователя', 'webUser/delete?id='.$webUser->id, 'delete', 'Вы точно хотите удалить пользователя '.$webUser->login.'?' ); ?></span>
        </div>
      </td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>

<?php if ($sessionWebUser->can(Permission::PERMISSION_MODER, $sessionWebUser->id)): ?>
<h3>Разрешения и запреты</h3>
<div class="spaceAfter">
  <?php echo link_to('Наделить правом или назначить запрет', 'grantedPermission/new?webUserId='.$webUser->id) ?>
</div>
<?php   $permissions = ($webUser->grantedPermissions->count() == 0) ? false : $webUser->grantedPermissions; ?>
<?php   if (!$permissions): ?>
<div>
  У пользователя нет особых разрешений или запретов.
</div>
<?php   else: ?>
<ul>
<?php     foreach ($permissions as $grantedPermission): ?>
  <li>
    <?php     if (!$grantedPermission->deny): ?>
    <span class="info">Имеет право</span>
    <?php echo $grantedPermission->Permission->description.(($grantedPermission->filter_id == 0) ? '' : ' с номером #'.$grantedPermission->filter_id) ?>.
    <span class="warnAction"><?php echo Utils::buttonTo('Лишить права', 'grantedPermission/delete?id='.$grantedPermission->id.'&returl='.$backLinkEncoded, 'delete', 'Вы точно хотите лишить пользователя '.$webUser->login.' права '.$grantedPermission->Permission->description.'?') ?></span>
    <?php     else: ?>
    <span class="warn">Запрещено</span>
    <?php echo $grantedPermission->Permission->description.(($grantedPermission->filter_id == 0) ? '' : ' с номером #'.$grantedPermission->filter_id) ?>.
    <span class="warnAction"><?php echo Utils::buttonTo('Отменить запрет', 'grantedPermission/delete?id='.$grantedPermission->id.'&returl='.$backLinkEncoded, 'delete', 'Вы точно хотите снять с пользователя '.$webUser->login.' запрет '.$grantedPermission->Permission->description.'?') ?></span>
    <?php     endif; ?>
  </li>
<?php     endforeach; ?>
</ul>
<?php   endif; ?>
<?php endif; ?>
