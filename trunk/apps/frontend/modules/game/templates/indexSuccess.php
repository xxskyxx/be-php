<?php 
  echo render_breadcombs(array(
      'Игры' 
  ));
  $this->_retUrlRaw = Utils::encodeSafeUrl(url_for('game/index'));
?>

<h2>Игры</h2>

<div class="spaceAfter">
  <?php if ($_sessionIsGameModerator): ?>
  <div><?php echo link_to('Создать новую игру', 'game/new') ?></div>
  <?php else: ?>
  <div><?php echo link_to_if(false, 'Подать заявку на создание игры', 'gameCreateRequest/new') ?></div>
  <?php endif; ?>
</div>

<?php if ($_activeGames->count() > 0): ?>
<h3>Проходят сейчас</h3>
<ul>
  <?php foreach ($_activeGames as $game): ?>
  <li>
    <div class="<?php
                if     ($_sessionPlayIndex[$game->id])
                {
                  if ($game->status == Game::GAME_READY || $game->status == Game::GAME_STEADY)
                      echo 'warn';
                  else
                      echo 'info';
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
          echo ' (окончание в '.$game->start_briefing_datetime;
          echo ', итоги '.$game->start_datetime.')';
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
<h3>Запланированы</h3>
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
      echo ' (брифинг '.$game->start_briefing_datetime;
      echo ', старт '.$game->start_datetime.')';
      echo ($_sessionPlayIndex[$game->id]) ? ' -&nbsp;Вы&nbsp;будете&nbsp;играть' : '';
      echo ($_sessionIsActorIndex[$game->id]) ? ' -&nbsp;Вы&nbsp;организатор' : '';
      ?>
    </div>
  </li>
  <?php endforeach ?>
</ul>
<?php endif; ?>

<?php if ($_archivedGames->count() > 0): ?>
<h3>Завершены</h3>
<ul>
  <?php foreach ($_archivedGames as $game): ?>
  <li>
    <div class="<?php
                if     ($_sessionPlayIndex[$game->id])    echo 'info';
                elseif ($_sessionIsActorIndex[$game->id]) echo 'warn';
                else                                      echo 'indent';
                ?>">
      <?php
      echo link_to($game->name, 'gameStats/report?id='.$game->id);
      echo ($_sessionPlayIndex[$game->id]) ? '&nbsp;-&nbsp;Вы&nbsp;играли' : '';
      echo ($_sessionIsActorIndex[$game->id]) ? '&nbsp;-&nbsp;Вы&nbsp;были&nbsp;организатором' : '';
      ?>
    </div>
  </li>
  <?php endforeach ?>
</ul>
<?php endif; ?>