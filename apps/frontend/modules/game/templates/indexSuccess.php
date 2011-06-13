<?php $sessionWebUser = $sf_user->getSessionWebUser()->getRawValue(); ?>

<h2>Все игры</h2>

<div class="spaceAfter">
  <?php echo link_to('Создать новую игру', 'game/new') ?>
</div>

<?php if (!$games): ?>
<div class="info">
  Пока еще не создано ни одной игры.
</div>
<?php else: ?>
<table cellspacing="0">
  <thead>
    <tr>
      <th rowspan="2">Название</th>
      <th rowspan="2">Ближайший срок</th>
      <th colspan="2">Команды</th>
      <th rowspan="2">Вы...</th>
    </tr>
    <tr>
      <td>На&nbsp;игре</td>
      <td>Заявок</td>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($games as $game): ?>
    <tr>
      <?php
      $currIsManager = $game->canBeManaged($sessionWebUser);
      $currIsActor = $game->canBeObserved($sessionWebUser);
      $currIsPlayer = $game->isPlayerRegistered($sessionWebUser);
      ?>
      <td><?php echo link_to($game->name, 'game/show?id='.$game->id) ?></td>
      <td>
        <?php
        switch ($game->status)
        {
          case Game::GAME_PLANNED:  { echo '<span class="info">'.$game->start_briefing_datetime.' - брифинг</span>'; break; }
          case Game::GAME_READY:    { echo '<span class="danger">'.$game->start_datetime.' - старт</span>'; break; }
          case Game::GAME_STEADY:
          case Game::GAME_ACTIVE:   { echo '<span class="warn">'.$game->stop_datetime.' - окончание</span>'; break; }
          case Game::GAME_FINISHED: { echo '<span class="info">'.$game->finish_briefing_datetime.' - награждение</span>'; break; }
          case Game::GAME_ARCHIVED:     { echo '<span class="indent">Игра завершена</span>'; break; }
          default: { echo '//Неизвестное состояние игры'; break; }
        }
        ?>
      </td>
      <td><?php echo $game->teamStates->count() ?></td>
      <td><?php echo $game->gameCandidates->count() ?></td>
      <td>
        <?php if ($currIsManager): ?>
        <span class="warn">Руководитель</span>
        <?php elseif ($currIsActor): ?>
        <span class="indent">Организатор</span>
        <?php elseif ($currIsPlayer): ?>
        <span class="info">Играете</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>

</table>
<?php endif; ?>
