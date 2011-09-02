<?php
/* Входные данные:
 * - $_game - игра
 * - $_retUrlRaw - ссылка обратного перехода
 * - $_sessionCanManage - руководитель игры
 * - $_sessionIsModerator - модератор игры
 * - $_teamStates - зарегистрированные команды
 * - $_gameCandidates - поданные заявки
 */
?>

<?php
render_h3_inline_begin('Играют команды');
if ($_sessionCanManage || $_sessionIsModerator) echo ' '.decorate_span('safeAction', link_to('Добавить', 'game/addTeamManual?id='.$_game->id.'&returl='.$_retUrlRaw, array('method' => 'post')));
render_h3_inline_end();
?>

<?php if ($_teamStates->count() <= 0): ?>
<div><span class="warn">Нет участвующих команд.</span></div>
<?php else: ?>
<ol>
  <?php foreach($_teamStates as $teamState): ?>
  <li>
    <?php
    echo link_to($teamState->Team->name, 'team/show?id='.$teamState->team_id, array ('target' => 'new'));
    echo ' стартует '.(($teamState->start_delay == 0)? 'сразу' : 'через '.Timing::intervalToStr($teamState->start_delay*60));
    echo ' '.decorate_span('safeAction', link_to('Настройки', 'teamState/show?id='.$teamState->id));
    if ($_sessionCanManage || $_sessionIsModerator)
        echo '&nbsp;'.decorate_span('warnAction', link_to('Снять с игры', 'game/removeTeam?id='.$_game->id.'&teamId='.$teamState->team_id.'&returl='.$_retUrlRaw, array('method' => 'post', 'confirm' => 'Вы точно хотите снять команду '.$teamState->Team->name.' с игры '.$_game->name.'?')));
    ?>
  </li>
  <?php endforeach; ?>
</ol>
<?php endif; ?>

<?php if ($_gameCandidates->count() > 0): ?>
<h3>Заявки на участие</h3>
<ul>
  <?php foreach($_gameCandidates as $candidate): ?>
  <li>
    <?php
      echo link_to($candidate->Team->name, 'team/show?id='.$candidate->team_id);
      if ($_sessionCanManage || $_sessionIsModerator)
      {
        echo ' '.decorate_span('safeAction', link_to('Отклонить', 'game/cancelJoin?id='.$_game->id.'&teamId='.$candidate->team_id.'&returl='.$_retUrlRaw, array('method' => 'post', 'confirm' => 'Отклонить заявку команды '.$candidate->Team->name.' на участие в игре '.$_game->name.'?')));
        echo ' '.decorate_span('warnAction', link_to('Утвердить', 'game/addTeam?id='.$_game->id.'&teamId='.$candidate->team_id.'&returl='.$_retUrlRaw, array('method' => 'post', 'confirm' => 'Принять команду '.$candidate->Team->name.' к участию в игре '.$_game->name.'?')));
      }
    ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>