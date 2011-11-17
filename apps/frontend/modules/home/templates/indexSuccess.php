<?php if (!$sf_user->isAuthenticated()): ?>
<?php   include('customization/homeNonAuth.php') ?>
<?php else: ?>
<?php   include('customization/homeAuth.php') ?>
<?php endif; ?>

<div>
  <?php include('customization/homeCommon.php') ?>
</div>
