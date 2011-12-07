<div>
  <?php if (!$_userAuthenticated): ?>
  <?php   include('customization/homeNonAuth.php') ?>
  <?php else: ?>
  <?php   include('customization/homeAuth.php') ?>
  <?php endif; ?>
</div>
<div>
  <?php include('customization/homeCommon.php') ?>
</div>

<?php if ($_currentRegion->id == Region::DEFAULT_REGION): ?>
<h3>Анонсы игр из всех регионов</h3>
<?php else: ?>
<h3><?php echo $_currentRegion->name?> - анонсы игр</h3>
<?php endif; ?>

<?php if ($_games->count() > 0): ?>
<?php
  foreach ($_games as $game)
  {
    $name = $_userAuthenticated
        ? link_to($game->name, 'game/show?id='.$game->id)
        : $game->name;
    $formatedName = '<div><span class="info" style="font-weight:bold">'.$name.'</span></div>';
    $region = ($_currentRegion->id == Region::DEFAULT_REGION) ? '<div>'.$game->getRegionSafe()->name.'</div>' : '';
    $date = '<div>'.$game->start_datetime.'</div>';
    $info = $game->short_info.($_userAuthenticated ? ' '.link_to('Подробнее...', 'game/show?id='.$game->id) : '');
    echo render_named_line(0, $formatedName.$region.$date, Utils::DecodeBB($info));
  }
?>
<?php else:?>
<div class="info">В ближайшее время игр не планируется.</div>
<?php endif; ?>