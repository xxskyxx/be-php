<?php $sessionCanManage = $teamState->canBeManaged($sf_user->getSessionWebUser()->getRawValue()) ?>

<h2>Настройки команды <?php echo $teamState->Team->name ?> на игру <?php echo $teamState->Game->name ?></h2>
<div>
  <?php echo link_to('Перейти к игре '.$teamState->Game->name, 'game/show?id='.$teamState->game_id) ?>
</div>
<div>
  <?php echo link_to('Перейти к команде '.$teamState->Team->name, 'team/show?id='.$teamState->team_id) ?>
</div>

<h3>Стартовые параметры</h3>
<table cellspacing="0">
  <tbody>
    <tr>
      <th>Задержка старта:</th>
      <td><?php echo $teamState->start_delay.'&nbsp;мин' ?></td>
    </tr>
    <tr>
      <th>Использовать ИИ выбора заданий:</th>
      <td><?php echo ($teamState->ai_enabled) ? 'Да' : 'Нет' ?></td>
    </tr>
  </tbody>
  <?php if ($sessionCanManage): ?>
  <tfoot>
    <tr>
      <td colspan="2">
        <span class="safeAction"><?php echo link_to('Редактировать', 'teamState/edit?id='.$teamState->id) ?></span>
      </td>
    </tr>
  </tfoot>
  <?php endif; ?>
</table>
