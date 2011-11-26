<?php render_breadcombs(array('Люди')); ?>

<h2>Люди</h2>

<?php if ($_currentRegion->id == Region::DEFAULT_REGION): ?>
<h3>Все</h3>
<?php else: ?>
<h3>Из региона <?php echo $_currentRegion->name ?></h3>
<?php endif ?>

<?php include_partial('region/setRegion', array('retUrl' => 'webUser/index'))?>

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
      echo ($webUser->full_name !== '') ? ', '.$webUser->full_name : '';
      echo ($webUser->id == $_sessionWebUserId) ? ' - это Вы' : '';
      echo (!$webUser->is_enabled) ? ' - блокирован' : '';
      ?>
    </div>
  </li>
  <?php endforeach; ?>
</ul>