<?php
$notice = $sf_user->getFlash('notice');
$error = $sf_user->getFlash('error');
?>
<?php if (isset($notice)): ?>
<div class="flashNotice">
  <?php echo $notice ?>
</div>
<?php endif; ?>
<?php if (isset($error)): ?>
<div class="flashError">
  <?php echo $error ?>
</div>
<?php endif; ?>