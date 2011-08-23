<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_task->Game->name, 'game/show?id='.$_task->game_id),
    link_to('Задания', 'game/show?id='.$_task->game_id.'&tab=tasks'),
    $_task->name,
));

$retUrlRaw = Utils::encodeSafeUrl(url_for('task/show?id='.$_task->id));
?>

<h2>Задание <?php echo $_task->name ?> игры <?php echo $_task->Game->name ?></h2>

<?php
render_h3_inline_begin('Cвойства');
if ($_isManager || $_isModerator) echo ' '.decorate_span('safeAction', link_to('Редактировать', 'task/edit?id='.$_task->id));
if ($_isModerator) echo '&nbsp;'.decorate_span('dangerAction', link_to('Удалить задание', 'task/delete?id='.$_task->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'confirm' => 'Вы точно хотите удалить задание '.$_task->name.'?')));
render_h3_inline_end();
?>
<h4>Основные</h4>
<?php
$width = get_text_block_size_ex('Когда кем-то выполняется:');
render_property_if($_isModerator, 'id:', $_task->id, $width);
render_property('Название:', $_task->name, $width);
render_property('Длительность:', Timing::intervalToStr($_task->time_per_task_local), $width);
render_property('Неверных ответов:', 'не&nbsp;более&nbsp;'.$_task->try_count_local, $width);
?>
<h4>Управление</h4>
<?php
render_property('Выполняющих команд:', ($_task->max_teams > 0) ? 'не&nbsp;более&nbsp;'.$_task->max_teams : 'без&nbsp;ограничений', $width);
render_property('Ручной старт:', $_task->manual_start ? decorate_span('info', 'Да') : 'Нет', $width);
render_property('Заблокировано:', $_task->locked ? decorate_span('warn', 'Да') : 'Нет', $width);
?>
<h4>Приоритеты опорные</h4>
<?php
render_property('Когда свободно:', decorate_number($_task->priority_free), $width);
render_property('Когда кому-то выдано:', decorate_number($_task->priority_queued), $width);
render_property('Когда кем-то выполняется:', decorate_number($_task->priority_busy), $width);
?>
<h4>Приоритеты дополнительные</h4>
<?php
render_property('Когда заполнено:', decorate_number($_task->priority_filled), $width);
render_property('На каждую команду:', decorate_number($_task->priority_per_team), $width);
?>

<?php
render_h3_inline_begin('Подсказки');
if ($_isManager || $_isModerator) echo ' '.decorate_span('safeAction', link_to('Добавить подсказку', 'tip/new?taskId='.$_task->id));
render_h3_inline_end();
?>
<?php if ($_tips->count() <= 0): ?>
<div class="danger">Нет подсказок!</div>
<?php else: ?>
<ul>
  <?php foreach ($_tips as $tip): ?>
  <li>
    <?php
    echo link_to($tip->name, 'tip/edit?id='.$tip->id);
    echo '&nbsp;';
    
    if ($tip->answer_id > 0) echo 'после&nbsp;ответа&nbsp;'.link_to($tip->Answer->name, 'answer/edit?id='.$tip->answer_id);
    elseif ($tip->delay == 0) echo 'сразу';
    else echo 'через&nbsp;'.Timing::intervalToStr($tip->delay*60);
    
    if ($_isManager || $_isModerator) echo ' '.decorate_span('dangerAction', link_to('Удалить', 'tip/delete?id='.$tip->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'conform' => 'Вы действительно хотите удалить подсказку '.$tip->name.' к заданию '.$tip->Task->name.'?')));
    ?>
  </li>
  <?php endforeach ?>
</ul>
<?php endif ?>


<?php
render_h3_inline_begin('Ответы');
if ($_isManager || $_isModerator) echo ' '.decorate_span('safeAction', link_to('Добавить ответ', 'answer/new?taskId='.$_task->id));
render_h3_inline_end();
?>
<?php if ($_answers->count() <= 0): ?>
<div class="danger">Нет подсказок!</div>
<?php else: ?>
<ul>
  <?php foreach ($_answers as $answer): ?>
  <li>
    <?php
    echo link_to($answer->name, 'answer/edit?id='.$answer->id);
    echo '&nbsp;'.$answer->value.'&nbsp;('.$answer->info.')';
    $onlyForTeam = Team::byId($answer->team_id);
    echo $onlyForTeam
        ? ' только для '.link_to($onlyForTeam->name, 'team/show?id='.$onlyForTeam->id, array('target' => 'new'))
        : '';
    echo ($_isManager || $_isModerator)
        ? ' '.decorate_span('dangerAction', link_to('Удалить', 'answer/delete?id='.$answer->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'conform' => 'Вы действительно хотите удалить ответ '.$answer->name.' задания '.$_task->name.'?')))
        : '';
    ?>
  </li>
  <?php endforeach ?>
</ul>
<?php endif ?>

<?php
render_h3_inline_begin('Правила переходов');
if ($_isManager || $_isModerator) echo ' '.decorate_span('safeAction', link_to('Добавить правило перехода', 'taskConstraint/new?taskId='.$_task->id));
render_h3_inline_end();
?>
<ul>
  <?php foreach ($_taskConstraints as $taskConstraint): ?>
  <li>
    <?php
    $targetTask = $taskConstraint->getTargetTaskSafe();
    if (!$targetTask)
    {
      $msg  = 'Целевое задание не найдено!';
      $msg .= ($_isManager || $_isModerator) ? '&nbsp'.link_to('Исправить', 'taskConstraint/edit?id='.$taskConstraint->id.'&returl='.$retUrlRaw, array('method' => 'post')) : '';
      $msg .= ($_isManager || $_isModerator) ? '&nbsp'.link_to('Удалить', 'taskConstraint/delete?id='.$taskConstraint->id.'&returl='.$retUrlRaw, array('method' => 'delete')) : '';
    }
    else
    {
      $numLink = link_to('&nbsp;&nbsp;'.$taskConstraint->priority_shift.'&nbsp;&nbsp;', 'taskConstraint/edit?id='.$taskConstraint->id);
      echo decorate_span(($taskConstraint->priority_shift > 0) ? 'info' : 'warn', $numLink);
      echo '&nbsp;на&nbsp;';
      echo link_to($targetTask->name, 'task/show?id='.$targetTask->id, array('target' => 'new'));
      if ($_isManager || $_isModerator) echo ' '.decorate_span('dangerAction', link_to('Удалить', 'taskConstraint/delete?id='.$taskConstraint->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'confirm' => 'Вы действительно хотите удалить правило перехода с задания '.$_task->name.' на задание '.$targetTask->name.'?')));
    }
    ?>
  </li>
  <?php endforeach ?>
</ul>

<h3>Предварительный просмотр</h3>
<?php foreach ($_tips as $tip): ?>
<p>
  <div style="background-color: Navy"><?php echo link_to($tip->name, 'tip/edit?id='.$tip->id); ?></div>
</p>
<div>
  <?php echo Utils::decodeBB($tip->define) ?>
</div>
<?php endforeach; ?>