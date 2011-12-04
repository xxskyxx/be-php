<?php if (!$sf_user->isAuthenticated()): ?>
<?php   include('customization/homeNonAuth.php') ?>
<?php else: ?>
<?php   include('customization/homeAuth.php') ?>
<?php endif; ?>

<div>
  <?php include('customization/homeCommon.php') ?>
</div>

<?php   if ($_currentRegion->id == Region::DEFAULT_REGION): ?>
<h3>Анонсы игр из всех регионов</h3>
<?php   else: ?>
<h3><?php echo $_currentRegion->name?> - анонсы игр</h3>
<?php   endif; ?>

<?php if ($_games->count() > 0): ?>

<?php
  foreach ($_games as $game)
  {
    $name = '<div><span class="info" style="font-weight:bold">'.$game->name.'</span></div>';
    $region = ($_currentRegion->id == Region::DEFAULT_REGION) ? '<div>'.$game->getRegionSafe()->name.'</div>' : '';
    $date = '<div>'.$game->start_datetime.'</div>';
    echo render_named_line(0, $name.$region.$date, Utils::DecodeBB($game->short_info));
  }
?>

<?php else:?>
<div class="info">В ближайшее время игр не планируется.</div>
<?php endif; ?>

