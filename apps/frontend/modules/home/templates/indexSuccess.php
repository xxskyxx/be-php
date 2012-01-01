<div class="columns">

  <div class="leftColumn">

    <div class="rightPadded">
      <h3>Анонсы</h3>
      <h4><?php echo ($_currentRegion->id == Region::DEFAULT_REGION) ? 'Все' : $_currentRegion->name ?></h4>
      <p>
        <?php include_partial('region/setRegion', array('retUrl' => 'home/index')); ?>
      </p>
      <?php if ($_games->count() > 0): ?>
      <?php
        foreach ($_games as $game)
        {
          include_partial('gameAnnounce', array(
              'game' => $game,
              '_isAuth' => $_userAuthenticated,
              '_showRegions' => ($_currentRegion->id == Region::DEFAULT_REGION)
          ));
        }
      ?>
      <?php else:?>
      <p>
        В ближайшее время игр не планируется.
      </p>
      <?php endif; ?>  
    </div>

  </div><div class="centerColumn">

    <div class="bothPadded">
      <?php if (!$_userAuthenticated): ?>
      <?php   include('customization/homeNonAuth.php') ?>
      <?php else: ?>
      <?php   include('customization/homeAuth.php') ?>
      <?php endif; ?>
    </div>
    <div>
      <?php include('customization/homeCommon.php') ?>
    </div>

  </div><div class="rightColumn">

    <div class="leftPadded">
      <h3>Новости</h3>

      <?php   if ($_canEditNews && $_localNews): ?>
      <div><span class="safeAction"><?php echo link_to('Редактировать', 'article/edit?id='.$_localNews->id); ?></span></div>
      <?php   endif ?>

      <h4><?php echo ($_currentRegion->id == Region::DEFAULT_REGION) ? 'Общие' : $_currentRegion->name ?></h4>
      <p>
        <?php include_partial('region/setRegion', array('retUrl' => 'home/index')); ?>
      </p>
      
      <?php if ($_localNews): ?>
      <div><?php echo Utils::decodeBB($_localNews->text) ?></div>
      <?php else: ?>
      <?php echo decorate_span('warn', 'Для этого региона не найден новостной канал.') ?>
      <?php endif ?>
      
    </div>

  </div>  
  
</div>
