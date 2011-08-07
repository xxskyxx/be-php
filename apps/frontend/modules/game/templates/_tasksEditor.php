<?php
/**
 * Входные аргументы:
 * @param   Game    $game       Игра, от которой редактируются задания
 * @param   boolean $editable   Разрешение на редактирование
 */
$tasks = ($game->tasks->count() == 0) ? false : $game->tasks;
?>
<table cellspacing="0">
  <thead>
    <tr>
      <th>Задание</th>
      <th>Дано времени</th>
      <th>Ошибок не более</th>
      <th>
        <div>
          Приоритеты:
        </div>
        <div class="comment">
          <div>
            <span class="indent">свободно</span>,
          </div>
          <div>
            <span class="indent">выдано</span>,
          </div>
          <div>
            <span class="info">занято</span>,
          </div>
          <div>
            <span class="warn">заполнено</span>,
          </div>
          <div>
            <span class="indent">на&nbsp;команду</span>
          </div>
        </div> 
      </th>
      <th>Команд не более</th>
      <th>Правила перехода</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!$tasks): ?>
    <tr>
      <td>
        <div class="danger">
          В игре нет заданий!
        </div>
      </td>
    </tr>
    <?php else: ?>
    <?php   foreach ($tasks as $task): ?>
    <tr>
      <td><?php echo link_to($task->name, 'task/show?id='.$task->id, array ('target' => 'new')) ?></td>
      <td style="text-align:center"><?php echo Timing::intervalToStr($task->time_per_task_local*60) ?></td>
      <td style="text-align:center"><?php echo $task->try_count_local ?></td>
      <td>
        <?php
        echo $task->priority_free.',';
        echo $task->priority_queued.',';
        echo '<span class="info">'.$task->priority_busy.'</span>,';
        echo '<span class="warn">'.$task->priority_filled.'</span>,';
        echo $task->priority_per_team
        ?>
      </td>
      <td style="text-align:center"><?php echo ($task->max_teams == 0) ? '&nbsp;' : $task->max_teams ?></td>
      <td>
        <?php if ($task->taskConstraints->count() <= 0): ?>
&nbsp;
        <?php else: ?>
        <table class="noBorder">
          <?php
          foreach($task->taskConstraints as $taskConstraint)
          {
            $targetTask = $taskConstraint->getTargetTaskSafe();
          ?>
          <tr>
            <?php if (!$targetTask): ?>
            <td colspan="2">
              <span class="danger">Переход с ошибкой!</span>
            </td>  
            <?php else: ?>
            <td style="text-align:right">
              <span class="<?php if ($taskConstraint->priority_shift == 0) { echo 'indent'; } elseif ($taskConstraint->priority_shift > 0) { echo 'info'; } else { echo 'warn'; } ?>">
              <?php echo $taskConstraint->priority_shift ?>
              </span>
            </td>
            <td>
              <?php
              if ($editable)
              {
                echo link_to($targetTask->name, 'taskConstraint/edit?id='.$taskConstraint->id);
              }
              else
              {
                echo $targetTask->name;
              }
              ?>
            </td>
            <?php endif; ?>
          </tr>
          <?php
          }
          ?>
        </table>
        <?php endif; ?>
      </td>
    </tr>
    <?php   endforeach; ?>
    <?php endif; ?>
  </tbody>
  <?php if ($editable): ?>
  <tfoot>
    <tr>
      <td colspan="6" style="text-align:left">
        <span class="safeAction"><?php echo link_to('Добавить задание', 'task/new?gameId='.$game->id) ?></span>
      </td>
    </tr>
  </tfoot>
  <?php endif; ?>    
</table>