<?php
$sessionCanManage = $task->Game->canBeManaged($sf_user->getSessionWebUser()->getRawValue());
$backLinkEncoded = Utils::encodeSafeUrl(url_for('task/show?id='.$task->id));
?>

<h2>Задание <?php echo $task->name ?> игры <?php echo $task->Game->name ?></h2>
<?php echo link_to('Перейти к игре '.$task->Game->name, 'game/show?id='.$task->game_id) ?>

<h3>Cвойства</h3>
<table cellspacing="0">
  <tbody>
    <tr>
      <th>No:</th><td><?php echo $task->id ?></td>
    </tr>
    <tr>
      <th>Название:</th><td><?php echo $task->name ?></td>
    </tr>
    <tr>
      <th>Длительность, мин:</th><td><?php echo $task->time_per_task_local ?></td>
    </tr>
    <tr>
      <th>Требует разрешения на старт:</th><td><?php echo $task->manual_start ? 'Да' : 'Нет' ?></td>
    </tr>
    <tr>
      <th>Неверных ответов не более:</th><td><?php echo $task->try_count_local ?></td>
    </tr>
    <tr>
      <th>Приоритет когда свободно:</th><td><?php echo $task->priority_free ?></td>
    </tr>
    <tr>
      <th>Приоритет когда занято:</th><td><?php echo $task->priority_busy ?></td>
    </tr>
    <tr>
      <th>Приоритет на каждую команду:</th><td><?php echo $task->priority_per_team ?></td>
    </tr>
    <tr>
      <th>Выполняющих команд не более:</th><td><?php echo $task->max_teams ?></td>
    </tr>
    <tr>
      <th>Заблокировано</th>
      <td>
        <?php if ($task->locked): ?>
        <span class="warn">Да</span>
        <?php else: ?>
        Нет
        <?php endif; ?>
      </td>
    </tr>
  </tbody>
  <?php if ($sessionCanManage): ?>
  <tfoot>
    <tr>
      <td colspan="2">
        <span class="safeAction"><?php echo link_to('Редактировать', 'task/edit?id='.$task->id) ?></span>
        <span class="dangerAction"><?php echo Utils::buttonTo('Удалить задание', 'task/delete?id='.$task->id.'&returl='.$backLinkEncoded, 'delete', 'Вы точно хотите удалить задание '.$task->name.'?'); ?></span>
      </td>
    </tr>
  </tfoot>
  <?php endif; ?>
</table>

<h3>Подсказки</h3>
<table cellspacing="0">
  <thead>
    <tr>
      <th>Название</th>
      <th>Выдается</th>
    </tr>
  </thead>
  <tbody>
    <?php $tips = $task->tips ?>
    <?php if ($tips->count() <= 0): ?>
    <tr>
      <td colspan="2">
        <div class="danger">
          Нет подсказок!
        </div>
      </td>
    </tr>
    <?php else: ?>
    <?php   foreach ($tips as $tip): ?>
    <tr>
      <td>
        <?php if ($sessionCanManage): ?>
        <span class="dangerAction"><?php echo Utils::buttonTo('Удалить', 'tip/delete?id='.$tip->id.'&returl='.$backLinkEncoded, 'delete', 'Вы действительно хотите удалить подсказку '.$tip->name.' к заданию '.$tip->Task->name.'?')?></span>
        <?php endif; ?>
        <?php echo link_to_if($sessionCanManage, $tip->name, 'tip/edit?id='.$tip->id) ?>
      </td>
      <td>
        <?php
        if ($tip->answer_id > 0)
        {
          echo 'после&nbsp;ответа&nbsp;"'.$tip->Answer->name.'"';
        }
        elseif ($tip->delay == 0)
        {
          echo 'сразу';
        }
        else
        {
          echo 'через&nbsp;'.Timing::intervalToStr($tip->delay*60);
        }
        ?>
      </td>
    </tr>
    <?php   endforeach; ?>
    <?php endif; ?>
  </tbody>
  <?php if ($sessionCanManage): ?>
  <tfoot>
    <tr>
      <td colspan="2" style="text-align:left">
        <span class="safeAction"><?php echo link_to('Добавить подсказку', 'tip/new?taskId='.$task->id) ?>
      </td>
    <tr>
  </tfoot>
  <?php endif; ?>
</table>

<h3>Ответы</h3>
<table cellspacing="0">
  <thead>
    <tr>
      <th>Название</th>
      <th>Описание</th>
      <th>Значение</th>
    </tr>
  </thead>
  <tbody>
    <?php $answers = $task->answers ?>
    <?php if ($answers->count() <= 0): ?>
    <tr>
      <td colspan="4">
        <div class="danger">
          Нет ответов!
        </div>
      </td>
    </tr>
    <?php else: ?>
    <?php   foreach ($answers as $answer): ?>
    <tr>
      <td>
        <?php if ($sessionCanManage): ?>
          <span class="dangerAction"><?php echo Utils::buttonTo('Удалить', 'answer/delete?id='.$answer->id.'&returl='.$backLinkEncoded, 'delete', 'Вы действительно хотите удалить ответ '.$answer->name.' задания '.$answer->Task->name.'?')?></span>
        <?php endif; ?>
        <?php echo link_to_if($sessionCanManage, $answer->name, 'answer/edit?id='.$answer->id) ?>
      </td>
      <td><?php echo $answer->info ?></td>
      <td><?php echo $answer->value ?></td>
    </tr>
    <?php   endforeach; ?>
    <?php endif; ?>
  </tbody>
  <?php if ($sessionCanManage): ?>
  <tfoot>
    <tr>
      <td colspan="4" style="text-align:left">
        <span class="safeAction"><?php echo link_to('Добавить ответ', 'answer/new?taskId='.$task->id) ?></span>
      </td>
    </tr>
  </tfoot>
  <?php endif; ?>
</table>

<h3>Правила переходов</h3>
<table cellspacing="0">
  <thead>
    <tr>
      <th>На задание</th>
      <th>Приоритет</th>
    </tr>
  </thead>
  <tbody>
    <?php $taskConstraints = $task->taskConstraints ?>
    <?php if ($taskConstraints->count() <= 0): ?>
    <tr>
      <td colspan="2">
        <div class="info">
          Правила переходов не определены.
        </div>
      </td>
    </tr>
    <?php else: ?>
    <?php   foreach ($taskConstraints as $taskConstraint): ?>
    <?php     $targetTask = $taskConstraint->getTargetTaskSafe(); ?>
    <?php     if (!$targetTask): ?>
    <tr>
      <td colspan="2">
        <div class="danger">
          Целевое задание не найдено!
          <?php if ($sessionCanManage): ?>
          <?php   echo Utils::buttonTo('Исправить', 'taskConstraint/edit?id='.$taskConstraint->id.'&returl='.$backLinkEncoded, 'delete')?></span>
          <?php   echo Utils::buttonTo('Удалить', 'taskConstraint/delete?id='.$taskConstraint->id.'&returl='.$backLinkEncoded, 'delete')?></span>
          <?php endif; ?>
        </div>
      </td>
    </tr>
    <?php     else: ?>
    <tr>
      <td>
        <?php if ($sessionCanManage): ?>
        <span class="dangerAction"><?php echo Utils::buttonTo('Удалить', 'taskConstraint/delete?id='.$taskConstraint->id.'&returl='.$backLinkEncoded, 'delete', 'Вы действительно хотите удалить правило перехода с задания '.$task->name.' на задание '.$targetTask->name.'?')?></span>
        <?php endif; ?>
        <?php echo link_to($targetTask->name, 'taskConstraint/edit?id='.$taskConstraint->id.'&returl='.$backLinkEncoded) ?>
      </td>
      <td>
        <?php
        echo ($taskConstraint->priority_shift >= 0) ? '+' : '';
        echo $taskConstraint->priority_shift;
        ?>
      </td>
    </tr> 
    <?php     endif; ?>
    <?php   endforeach; ?>
    <?php endif; ?>
  </tbody>
  <?php if ($sessionCanManage): ?>
  <tfoot>
    <tr>
      <td colspan="2" style="text-align:left">
        <span class="safeAction"><?php echo link_to('Добавить правило перехода', 'taskConstraint/new?taskId='.$task->id) ?></span>
      </td>
    </tr>        
  </tfoot>
  <?php endif; ?>
</table>

<h3>Предварительный просмотр</h3>
<?php foreach ($tips as $tip): ?>
<div class="spaceBefore">
  <div class="info">
    <?php echo link_to($tip->name, 'tip/edit?id='.$tip->id) ?>
  </div>
</div>
<div>
  <?php echo Utils::decodeBB($tip->define) ?>
</div>
<?php endforeach; ?>
