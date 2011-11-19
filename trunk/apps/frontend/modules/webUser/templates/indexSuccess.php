<?php render_breadcombs(array('Пользователи')); ?>

<h2>Пользователи</h2>
<h3></h3>

<ul>
  <?php foreach ($_webUsers as $webUser): ?>
  <li>
    <div class="<?php
                if ($webUser->id == $_sessionWebUserId) { echo 'info'; }
                elseif (!$webUser->is_enabled) { echo 'warn'; }
                else echo 'indent';
                ?>">
      <?php
      echo link_to($webUser->login, url_for('webUser/show?id='.$webUser->id));
      echo ', '.$webUser->full_name;
      echo ($webUser->id == $_sessionWebUserId) ? ' - это Вы' : '';
      echo (!$webUser->is_enabled) ? ' - блокирован' : '';
      ?>
    </div>
  </li>
  <?php endforeach; ?>
</ul>