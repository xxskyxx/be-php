<?php render_breadcombs(array('Люди')); ?>

<?php if ($_currentRegion->id == Region::DEFAULT_REGION): ?>
<h2>Люди из всех регионов</h2>
<?php else: ?>
<h2>Люди в регионе <?php echo $_currentRegion->name ?></h2>
<?php endif ?>

<?php include_partial('region/setRegion', array('retUrl' => 'webUser/index'))?>
<div class="hr"></div>

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