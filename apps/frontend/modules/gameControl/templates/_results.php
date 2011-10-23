<?php
/**
 * Входные аргументы:
 * - Game $_game - игра, для которой строится отчет.
 */
$results = $_game->getGameResults();
?>

<table cellspacing="0">
  <thead>
    <tr>
      <th>Место</th>
      <th>Команда</th>
      <th>Очки</th>
      <th>Время</th>
    </tr>
  </thead>

  <tbody>
    <?php $place = 1 ?>
    <?php foreach ($results as $teamResult): ?>
    <tr>
      <td style="text-align: center"><?php echo $place ?></td>
      <td><?php echo Team::byId($teamResult['id'])->name ?></td>
      <td style="text-align: center"><?php echo $teamResult['points'] ?></td>
      <td><?php echo Timing::intervalToStr($teamResult['time']) ?></td>
    </tr>
    <?php $place++ ?>
    <?php endforeach; ?>
  </tbody>
</table>
