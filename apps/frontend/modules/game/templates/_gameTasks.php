<?php
/* Входные данные:
 * - $_game - игра
 * - $_retUrlRaw - ссылка обратного перехода
 * - $_sessionCanManage - руководитель игры
 * - $_sessionIsModerator - модератор игры
 * - $_tasks - задания
 */
?>

<?php
render_h3_inline_begin('Задания');
if ($_sessionCanManage || $_sessionIsModerator) echo decorate_span('safeAction', link_to('Добавить', 'task/new?gameId='.$_game->id));
render_h3_inline_end();
?>

<div>
  <?php
  $widthName = get_max_field_length($_tasks->getRawValue(), 'name');
  $widthTaskTime = get_text_block_size_ex('Дано времени');
  $widthTryCount = get_text_block_size_ex('Ошибок');
  $widthMaxTeam = get_text_block_size_ex('Команд');
  render_column_name('Название', $widthName);
  render_column_name('Дано времени', $widthTaskTime);
  render_column_name('Ошибок', $widthTryCount);
  render_column_name('Команд', $widthMaxTeam);
  ?>
</div>
<?php foreach ($_tasks as $task): ?>
  <div>
    <?php
    render_column_value(link_to($task->name, 'task/show?id='.$task->id, array ('target' => 'new')), $widthName, 'left');
    render_column_value(Timing::intervalToStr($task->time_per_task_local*60), $widthTaskTime, 'center');
    render_column_value('&lt;='.$task->try_count_local, $widthTryCount, 'center');
    render_column_value(($task->max_teams > 0) ? '&lt;='.$task->max_teams : '&infin;', $widthMaxTeam, 'center');
    ?>
  </div>
<?php endforeach ?>

<h3>Собственные приоритеты</h3>
<div>
  <?php
  render_column_name('Задание', $widthName);
  $widthFree = get_text_block_size_ex('Свободно');
  render_column_name('Свободно', $widthFree);
  $widthQueue = get_text_block_size_ex('Выдано');
  render_column_name('Выдано', $widthQueue);
  $widthBusy = get_text_block_size_ex('Выполняется');
  render_column_name('Выполняется', $widthBusy);
  $widthFill = get_text_block_size_ex('Заполнено');
  render_column_name('Заполнено', $widthFill);
  $widthPerTeam = get_text_block_size_ex('На&nbsp;команду');
  render_column_name('На&nbsp;команду', $widthPerTeam);
  ?>
</div>
<?php foreach ($_tasks as $task): ?>
  <div>
    <?php
    render_column_value(link_to($task->name, 'task/show?id='.$task->id, array ('target' => 'new')), $widthName, 'left');
    render_column_value(decorate_number($task->priority_free), $widthFree, 'center');
    render_column_value(decorate_number($task->priority_queued), $widthQueue, 'center');
    render_column_value(decorate_number($task->priority_busy), $widthBusy, 'center');
    render_column_value(decorate_number($task->priority_filled), $widthFill, 'center');
    render_column_value(decorate_number($task->priority_per_team), $widthPerTeam, 'center');
    ?>
  </div>
<?php endforeach ?>

<h3>Приоритеты переходов</h3>
<ul>
<?php
  foreach ($_tasks as $task)
  {
    $html = '';
    if ($task->taskConstraints->count() <= 0)
    { 
      $html = 'Приоритеты переходов не заданы';
    }
    else
    {
      foreach ($task->taskConstraints as $taskConstraint)
      {
        $html .= ($html !== '') ? ', ' : '';
        if ($taskConstraint->priority_shift != 0)
        {
          $targetTask = $taskConstraint->getTargetTaskSafe();
          if ( ! $targetTask)
          {
            $html .= decorate_span('danger', 'Переход не найден!');
          }
          else
          {  
            $html .= decorate_number($taskConstraint->priority_shift).'&nbsp;';
            $html .= link_to($targetTask->name, 'task/show?id='.$targetTask->id, array ('target' => 'new'));
          }
        }
      }
    }
    render_property(link_to($task->name, 'task/show?id='.$task->id, array ('target' => 'new')).': ', $html, $widthName);
  }
?>
</ul>