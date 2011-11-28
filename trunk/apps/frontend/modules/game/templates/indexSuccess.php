<?php 
render_breadcombs(array(
    'Игры' 
));
$this->_retUrlRaw = Utils::encodeSafeUrl(url_for('game/index'));
?>

<h2>Игры</h2>

<div>
  <?php if ($_sessionIsGameModerator): ?>
  <div><?php echo link_to('Создать новую игру', 'game/new') ?></div>
  <?php else: ?>
  <div><?php echo link_to('Подать заявку на создание игры', 'gameCreateRequest/newManual') ?></div>
  <?php endif; ?>
</div>

<?php if ($_currentRegion->id == Region::DEFAULT_REGION): ?>
<h3>Все</h3>
<?php else: ?>
<h3>В регионе <?php echo $_currentRegion->name ?></h3>
<?php endif ?>

<?php include_partial('region/setRegion', array('retUrl' => 'game/index'))?>

<?php
if ( !
     (   ($_plannedGames->count() > 0)
      || ($_activeGames->count() > 0)
      || ($_archivedGames->count() > 0)
     )
   )
{
  echo decorate_div('info', 'В этом регионе нет игр.');  
}
?>

<?php if ($_activeGames->count() > 0): ?>
<h4>Проходят сейчас</h4>
<ul>
  <?php foreach ($_activeGames as $game): ?>
  <li>
    <div class="<?php
                if     ($_sessionPlayIndex[$game->id])
                {
                  echo ($game->status == Game::GAME_READY || $game->status == Game::GAME_STEADY)
                      ? 'warn'
                      : 'info';
                }
                elseif ($_sessionIsActorIndex[$game->id]) echo 'warn';
                else                                      echo 'indent';
                ?>">
      <?php
      echo link_to($game->name, 'game/show?id='.$game->id);
      switch ($game->status)
      {
        case Game::GAME_READY:
        case Game::GAME_STEADY:
          echo ' (старт '.$game->start_datetime.')';
          break;
        case Game::GAME_ACTIVE:
          echo ' (окончание '.$game->start_briefing_datetime.', итоги '.$game->start_datetime.')';
          break;
        case Game::GAME_FINISHED:
          echo ' (финишировала, итоги '.$game->start_datetime.')';
          break;
      }
      echo ($_sessionPlayIndex[$game->id]) ? '&nbsp;-&nbsp;Вы&nbsp;играете' : '';
      echo ($_sessionIsActorIndex[$game->id]) ? '&nbsp;-&nbsp;Вы&nbsp;организатор' : '';
      ?>
    </div>
  </li>
  <?php endforeach ?>
</ul>
<?php endif; ?>

<?php if ($_plannedGames->count() > 0): ?>
<h4>Запланированы</h4>
<ul>
  <?php foreach ($_plannedGames as $game): ?>
  <li>
    <div class="<?php
                if     ($_sessionPlayIndex[$game->id])    echo 'info';
                elseif ($_sessionIsActorIndex[$game->id]) echo 'warn';
                else                                      echo 'indent';
                ?>">
      <?php
      echo link_to($game->name, 'game/show?id='.$game->id);
      echo ' (брифинг '.$game->start_briefing_datetime.')';
      echo ($_sessionPlayIndex[$game->id]) ? ' -&nbsp;Вы&nbsp;будете&nbsp;играть' : '';
      echo ($_sessionIsActorIndex[$game->id]) ? ' -&nbsp;Вы&nbsp;организатор' : '';
      ?>
    </div>
  </li>
  <?php endforeach ?>
</ul>
<?php endif; ?>

<?php if ($_archivedGames->count() > 0): ?>
<h4>Завершены</h4>
<ul>
  <?php foreach ($_archivedGames as $game): ?>
  <li>
    <div class="<?php
                if     ($_sessionPlayIndex[$game->id])    echo 'info';
                elseif ($_sessionIsActorIndex[$game->id]) echo 'warn';
                else                                      echo 'indent';
                ?>">
      <?php
      echo link_to($game->name, 'game/show?id='.$game->id).' ('.link_to('итоги', 'gameControl/report?id='.$game->id).')';
      echo ($_sessionPlayIndex[$game->id]) ? '&nbsp;-&nbsp;Вы&nbsp;играли' : '';
      echo ($_sessionIsActorIndex[$game->id]) ? '&nbsp;-&nbsp;Вы&nbsp;были&nbsp;организатором' : '';
      ?>
    </div>
  </li>
  <?php endforeach ?>
</ul>
<?php endif; ?>

<?php if ($_gameCreateRequests->count() > 0): ?>
<?php   if ($_sessionIsGameModerator): ?>
<h3>Заявки на создание (все регионы)</h3>
<?php   else: ?>
<h3>Заявки на создание (ваши)</h3>
<?php   endif ?>
<ul>
  <?php foreach ($_gameCreateRequests as $gameCreateRequest): ?>
  <li>
    <div>
      <?php
      echo $gameCreateRequest->name;
      echo '&nbsp'.decorate_span('safeAction', link_to('Отменить', 'gameCreateRequest/delete?id='.$gameCreateRequest->id, array('method' => 'post')));
      echo ($_sessionIsGameModerator) ? '&nbsp'.decorate_span('warnAction', link_to('Создать', 'gameCreateRequest/acceptManual?id='.$gameCreateRequest->id, array('method' => 'post', 'confirm' => 'Подтвердить создание игры '.$gameCreateRequest->name.' ('.$gameCreateRequest->Team->name.' будут ее организаторами) ?'))) : '';
      echo ', '.link_to($gameCreateRequest->Team->name, 'team/show?id='.$gameCreateRequest->team_id, array('target' => 'new')).':&nbsp;'.$gameCreateRequest->description;
      ?>
    </div>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>