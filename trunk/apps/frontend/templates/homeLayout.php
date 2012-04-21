<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <?php include_partial('global/metas'); ?>
  <body onload="startTime()">
    <?php include_partial('global/header'); ?>
    <?php include_partial('global/mainMenu'); ?>
    <?php include_partial('global/flashes'); ?>
    <?php echo $sf_content; ?>
    <div class="hr"></div>
    <?php include_partial('global/footer'); ?>
  </body>

</html>