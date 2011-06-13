<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <?php include_partial('global/Metas'); ?>
  <body style="width:auto">
    <?php include_partial('global/Flashes'); ?>

    <?php echo $sf_content; ?>

    <div class="footer">
      <?php echo link_to('Главная', 'home/index').' ' ?>
      <?php echo $sf_user->getAttribute('login') ?>,
      <?php echo link_to('выйти', 'auth/logout') ?>
    </div>
  </body>

</html>