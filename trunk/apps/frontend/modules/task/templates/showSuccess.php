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
render_property('Внутреннее название:', $_task->name, $width);
render_property('Открытое название:', $_task->public_name, $width);
render_property('Длительность:', Timing::intervalToStr($_task->time_per_task_local*60), $width);
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
if ($_isManager || $_isModerator) echo ' '.decorate_span('safeAction', link_to('Добавить', 'tip/new?taskId='.$_task->id));
render_h3_inline_end();
?>
<?php if ($_tips->count() <= 0): ?>
<div class="danger">Нет подсказок!</div>
<?php else: ?>
<ul>
  <?php foreach ($_tips as $tip): ?>
  <li>
    <?php
    if ($_isManager || $_isModerator) echo decorate_span('dangerAction', link_to('Удалить', 'tip/delete?id='.$tip->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'conform' => 'Вы действительно хотите удалить подсказку '.$tip->name.' к заданию '.$tip->Task->name.'?')));
    echo ' '.link_to($tip->name, 'tip/edit?id='.$tip->id);
    echo '&nbsp;';
    if ($tip->answer_id > 0) echo 'после&nbsp;ответа&nbsp;'.link_to($tip->Answer->name, 'answer/edit?id='.$tip->answer_id);
    elseif ($tip->delay == 0) echo 'сразу';
    else echo 'через&nbsp;'.Timing::intervalToStr($tip->delay*60);
    
    ?>
  </li>
  <?php endforeach ?>
</ul>
<?php endif ?>


<?php
render_h3_inline_begin('Ответы');
if ($_isManager || $_isModerator) echo ' '.decorate_span('safeAction', link_to('Добавить', 'answer/new?taskId='.$_task->id));
render_h3_inline_end();
?>
<?php if ($_answers->count() <= 0): ?>
<div class="danger">Нет ответов!</div>
<?php else: ?>
<ul>
  <?php foreach ($_answers as $answer): ?>
  <li>
    <?php
    echo ($_isManager || $_isModerator)
        ? decorate_span('dangerAction', link_to('Удалить', 'answer/delete?id='.$answer->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'conform' => 'Вы действительно хотите удалить ответ '.$answer->name.' задания '.$_task->name.'?')))
        : '';
    echo ' '.link_to($answer->name, 'answer/edit?id='.$answer->id);
    echo '&nbsp;'.$answer->value.'&nbsp;('.$answer->info.')';
    echo (($answer->team_id !== null) && ($answer->team_id != 0))
        ? ' только для '.link_to($answer->Team->name, 'team/show?id='.$answer->team_id, array('target' => 'new'))
        : '';
    ?>
  </li>
  <?php endforeach ?>
</ul>
<?php endif ?>

<?php
render_h3_inline_begin('Приоритеты переходов');
if ($_isManager || $_isModerator) echo ' '.decorate_span('safeAction', link_to('Добавить', 'taskConstraint/new?taskId='.$_task->id));
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
      echo decorate_span('danger', $msg);
    }
    else
    {
      if ($_isManager || $_isModerator) echo decorate_span('dangerAction', link_to('Удалить', 'taskConstraint/delete?id='.$taskConstraint->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'confirm' => 'Вы действительно хотите удалить правило перехода с задания '.$_task->name.' на задание '.$targetTask->name.'?'))).' ';
      $numLink = link_to('&nbsp;&nbsp;'.$taskConstraint->priority_shift.'&nbsp;&nbsp;', 'taskConstraint/edit?id='.$taskConstraint->id);
      echo ($taskConstraint->priority_shift > 0)
          ? decorate_span('info', $numLink)
          : decorate_span('warn', $numLink);
      echo '&nbsp;на&nbsp;';
      echo link_to($targetTask->name, 'task/show?id='.$targetTask->id, array('target' => 'new'));
    }
    ?>
  </li>
  <?php endforeach ?>
</ul>

<?php
render_h3_inline_begin('Фильтры переходов');
if ($_isManager || $_isModerator) echo ' '.decorate_span('safeAction', link_to('Добавить', 'taskTransition/new?taskId='.$_task->id));
render_h3_inline_end();
?>
<?php if ($_isManager || $_isModerator): ?>
<p>
  <span class="safeAction"><?php echo link_to('Добавить фильтры переходов на все остальные задания', 'task/transitions?id='.$_task->id.'&operation=addAll'.'&returl='.$retUrlRaw, array('method' => 'post')) ?></span>
</p>
<?php   if ($_taskTransitions->count() > 0): ?>
<p>
  <span class="safeAction"><?php echo link_to('Поставить всем фильтрам признак "Разрешить ручной выбор"', 'task/transitions?id='.$_task->id.'&operation=allManual'.'&returl='.$retUrlRaw, array('method' => 'post')) ?></span>
</p>
<p>
  <span class="safeAction"><?php echo link_to('Снять со всех всех фильтров признак "Разрешить ручной выбор"', 'task/transitions?id='.$_task->id.'&operation=allAuto'.'&returl='.$retUrlRaw, array('method' => 'post')) ?></span>
</p>
<?php   endif ?>
<?php endif ?>
<ul>
  <?php foreach ($_taskTransitions as $taskTransition): ?>
  <li>
    <?php
    $targetTask = $taskTransition->getTargetTaskSafe();
    if ( ! $targetTask)
    {
      $msg  = 'Целевое задание не найдено!';
      $msg .= ($_isManager || $_isModerator) ? '&nbsp'.link_to('Исправить', 'taskTransition/edit?id='.$taskTransition->id.'&returl='.$retUrlRaw, array('method' => 'post')) : '';
      $msg .= ($_isManager || $_isModerator) ? '&nbsp'.link_to('Удалить', 'taskTransition/delete?id='.$taskTransition->id.'&returl='.$retUrlRaw, array('method' => 'delete')) : '';
      echo decorate_span('danger', $msg);
    }
    else
    {
      if ($_isManager || $_isModerator) echo decorate_span('dangerAction', link_to('Удалить', 'taskTransition/delete?id='.$taskTransition->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'confirm' => 'Вы действительно хотите удалить фильтр перехода с задания '.$_task->name.' на задание '.$targetTask->name.'?'))).' ';
      $linkTarget = 'taskTransition/edit?id='.$taskTransition->id;
      if ($taskTransition->allow_on_success && $taskTransition->allow_on_fail)
      {
        echo link_to('В любом случае', $linkTarget);
      }
      elseif ($taskTransition->allow_on_success)
      {
        echo decorate_span('info', link_to('При успехе', $linkTarget));
      }
      else
      {
        echo decorate_span('warn', link_to('При неудаче', $linkTarget));
      }
      echo '&nbsp;на&nbsp;';
      echo link_to($targetTask->name, 'task/show?id='.$targetTask->id, array('target' => 'new'));
      echo $taskTransition->manual_selection ? '&nbsp;вручную' : '';      
    }
    ?>
  </li>
  <?php endforeach ?>
</ul>
<p class="comment">
  <span class="warn">Если через фильтры не пройдет ни одного задания, то выбор будет выполняться среди всех доступных заданий без учета фильтров</span>
</p>

<h3>Предварительный просмотр</h3>
<?php foreach ($_tips as $tip): ?>
<p>
  <div style="background-color: Navy"><?php echo link_to($tip->name, 'tip/edit?id='.$tip->id); ?></div>
</p>
<div>
  <?php echo Utils::decodeBB($tip->define) ?>
</div>
<?php endforeach; ?>
