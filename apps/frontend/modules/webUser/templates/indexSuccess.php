<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();

render_breadcombs(array('Пользователи'));
?>

<h2>Все пользователи</h2>

<?php if ($sessionWebUser->can(Permission::byId(Permission::WEB_USER_MODER), 0)): ?>
<div class="spaceAfter">
  <div class="info">Вы можете управлять любой анкетой</div>
</div>
<?php endif; ?>

<ul>
  <?php foreach ($webUsers as $webUser): ?>
  <li>
    <div class="<?php
                if ($webUser->id == $sessionWebUser->id) { echo 'info'; }
                elseif (!$webUser->is_enabled) { echo 'warn'; }
                else echo 'indent';
                ?>">
    <?php
    echo link_to($webUser->login, url_for('webUser/show?id='.$webUser->id));
    echo ' ('.$webUser->full_name.')';
    echo ($webUser->id == $sessionWebUser->id) ? ' - это Вы' : '';
    echo (!$webUser->is_enabled) ? ' - блокирован' : '';
    ?>
    </div>
  </li>
  <?php endforeach; ?>
</ul>