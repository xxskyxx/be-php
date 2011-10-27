<?php
/* Входные данные:
 * $_game - игра
 * $_isManager - пользователь - руководитель игры
 * $_retUrlRaw - ссылка для обратного перехода
 * $_activeTab - активная страница
 */
?>

<h2>Управление игрой <?php echo $_game->name ?></h2>

<p>
  <span class="safeAction"><?php echo link_to('Обновить', Utils::decodeSafeUrl($_retUrlRaw)) ?></span>

  Игра&nbsp;в&nbsp;<?php echo Timing::timeToStr($_game->game_last_update) ?>: <?php echo $_game->describeStatus() ?>

  <?php if ($_isManager): ?>
  <?php   if     ($_game->status == Game::GAME_PLANNED): ?>
  <span class="safeAction"><?php echo link_to('Подготовить к запуску', 'gameControl/verify?id='.$_game->id.'&returl='.$_retUrlRaw, array('method' => 'post', 'confirm' => 'Подготовить игру '.$_game->name.' к запуску?')); ?></span>

  <?php   elseif ($_game->status == Game::GAME_VERIFICATION): ?>
  <span class="safeAction"><?php echo link_to('Повторить проверку', 'gameControl/verify?id='.$_game->id.'&returl='.$_retUrlRaw, array('method' => 'post', 'confirm' => 'Повторить предстартовую проверку игры '.$_game->name.'?')); ?></span>

  <?php   elseif ($_game->status == Game::GAME_READY): ?>
  <span class="warnAction"><?php echo link_to('Запустить', 'gameControl/start?id='.$_game->id.'&returl='.$_retUrlRaw, array('method' => 'post', 'confirm' => 'Запустить игру '.$_game->name.'?')); ?></span>
  <span class="safeAction"><?php echo link_to('Повторить проверку', 'gameControl/verify?id='.$_game->id.'&returl='.$_retUrlRaw, array('method' => 'post', 'confirm' => 'Повторить предстартовую проверку игры '.$_game->name.'?')); ?></span>

  <?php   elseif (($_game->status == Game::GAME_STEADY) || ($_game->status == Game::GAME_ACTIVE)): ?>
  <span class="dangerAction"><?php echo link_to('Остановить', 'gameControl/stop?id='.$_game->id.'&returl='.$_retUrlRaw, array('method' => 'post', 'confirm' => 'Остановить игру '.$_game->name.'?')); ?></span>

  <?php   elseif ($_game->status == Game::GAME_FINISHED): ?>
  <span class="warnAction"><?php echo link_to('Сдать в архив', 'gameControl/close?id='.$_game->id.'&returl='.$_retUrlRaw, array('method' => 'post', 'confirm' => 'Игру больше нельзя будет редактировать! Вы уверены, что хотите сдать в архив игру '.$_game->name.'?')); ?></span>
  <?php   endif; ?>

  <?php   if ($_game->status > Game::GAME_PLANNED): ?>
  <span class="dangerAction"><?php echo link_to('Перезапустить', 'gameControl/reset?id='.$_game->id.'&returl='.$_retUrlRaw, array('method' => 'post', 'confirm' => 'Перезапустить игру '.$_game->name.'?'));?></span>
  <?php   endif; ?>
  <?php endif; ?>  
</p>
  
<p>
  <?php if ($_isManager
            && ($_game->status >= Game::GAME_STEADY)
            && ($_game->status <= Game::GAME_FINISHED) ): ?>
  <span class="warnAction"><?php echo link_to('Пересчитать', 'gameControl/update?id='.$_game->id.'&returl='.$_retUrlRaw, array('method' => 'post')); ?></span>
  <span class="warnAction"><?php echo link_to('Запуск автопересчета', url_for('gameControl/autoUpdate?id='.$_game->id), array('target' => 'window')) ?></span>  
  <?php endif; ?>

  <?php if (( ! $_game->teams_can_update) && $_game->isActive() && (Timing::isExpired(time(), $_game->update_interval_max, $_game->game_last_update))): ?>
  <span class="danger">Пересчет состояния просрочен на <?php echo Timing::intervalToStr(time() - $_game->game_last_update - $_game->update_interval_max) ?>!</span>
  <?php endif ?>
</p>

<?php
echo ($_activeTab == 'pilot')
    ? decorate_div('tabHeaderBox', decorate_div('tabHeaderActive', 'Пилот'))
    : decorate_div('tabHeaderBox', decorate_div('tabHeader', link_to('Пилот', 'gameControl/pilot?id='.$_game->id)));
echo ($_activeTab == 'sturman')
    ? decorate_div('tabHeaderBox', decorate_div('tabHeaderActive', 'Штурман'))
    : decorate_div('tabHeaderBox', decorate_div('tabHeader', link_to('Штурман', 'gameControl/sturman?id='.$_game->id)));
echo ($_activeTab == 'engineer')
    ? decorate_div('tabHeaderBox', decorate_div('tabHeaderActive', 'Бортмеханик'))
    : decorate_div('tabHeaderBox', decorate_div('tabHeader', link_to('Бортмеханик', 'gameControl/engineer?id='.$_game->id)));
echo ($_activeTab == 'stuart')
    ? decorate_div('tabHeaderBox', decorate_div('tabHeaderActive', 'Стюардесса'))
    : decorate_div('tabHeaderBox', decorate_div('tabHeader', link_to('Стюардесса', 'gameControl/stuart?id='.$_game->id)));
?>


