<?php $sessionWebUser = $sf_user->getSessionWebUser()->getRawValue(); ?>

<h2>Все пользователи</h2>
<table cellspacing="0">
  <thead>
    <tr>
      <th>Имя</th>
      <th>Полное имя</th>
      <th>E-Mail</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($webUsers as $currWebUser): ?>
    <tr>
      <td>
        <?php if ($currWebUser->id == $sessionWebUser->id): ?>
        <div class="info">
          <?php echo link_to($currWebUser->login, url_for('webUser/show?id='.$currWebUser->id)) ?>&nbsp;-&nbsp;это&nbsp;Вы
        </div>
        <?php elseif (!$currWebUser->getIsEnabled()): ?>
        <div class="warn">
          <?php echo link_to($currWebUser->login, url_for('webUser/show?id='.$currWebUser->id)) ?>&nbsp;-&nbsp;блокирован
        </div>
        <?php else: ?>
        <div class="indent">
          <?php echo link_to($currWebUser->login, url_for('webUser/show?id='.$currWebUser->id)); ?>
        </div>
        <?php endif; ?>
      </td>
      <td>
        <?php echo $currWebUser->full_name ?>
      </td>
      <td>
        <?php
        if (($currWebUser->email === null) || ($currWebUser->email === ''))
        {
          echo 'Не указан';
        }
        else
        {
          echo mail_to($currWebUser->email);
        }
        ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
