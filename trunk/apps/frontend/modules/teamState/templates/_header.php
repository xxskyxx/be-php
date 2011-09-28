<?php
/* Входные данные:
 * $teamState - состояние команды
 */
?>
<div>
  <span class="safeAction"><?php echo link_to('Обновить', 'teamState/task?id='.$teamState->id) ?></span>
</div>
<h2><?php echo $teamState->Game->name ?></h2>
<h3><?php echo $teamState->Team->name ?></h3>
