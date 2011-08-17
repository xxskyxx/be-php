<div>
  <span class="safeAction"><?php echo link_to('Обновить', 'teamState/task?id='.$teamState->id) ?></span>
</div>

<h2><?php echo $teamState->Game->name ?></h2>
<h3><?php echo $teamState->Team->name ?></h3>

<?php if ($teamState->status == TeamState::TEAM_WAIT_GAME): ?>
<p>
  Игра начнется в <?php echo Timing::timeToStr(Timing::strToDate($teamState->Game->start_datetime)) ?>.
</p>
<p>
  После наступления момента начала игры обновите страницу для получения дальнейших инструкций.
</p>

<?php elseif ($teamState->status == TeamState::TEAM_WAIT_START): ?>
<p>
  Игра началась, но Ваша команда стартует в <?php echo Timing::timeToStr($teamState->getActualStartDateTime()) ?>.
</p>
<p>
  После наступления момента старта Вашей команды обновите страницу для получения дальнейших инструкций.
</p>

<?php elseif ($teamState->status == TeamState::TEAM_WAIT_TASK): ?>
<?php   if ($teamState->task_id <= 0): ?>
<p>
  Следующее задание для Вашей команды сейчас в процессе подготовки.
</p>
<p>
  Обновляйте страницу время от времени.
</p>
<p>
  Как только задание будет готово, Вы получите дальнейшие инструкции.
</p>
<p>
  <div class="info">Задание стартует только тогда, когда Вы его в первый раз увидите.</div>
</p>
<?php   else: ?>
<p>
  Вашей команде назначено задание, ожидайте его старта.
</p>
<p>
  Обновляйте страницу время от времени.
</p>
<p>
  Как только Вашему заданию будет дан старт, вы его увидите.
</p>
<p>
  <div class="info">Задание стартует только тогда, когда вы его в первый раз увидите.</div>
</p>
<?php   endif ?>

<?php elseif ($teamState->status == TeamState::TEAM_HAS_TASK): ?>
<div>
  <?php if ($taskState = $teamState->getCurrentTaskState()): ?>
  <?php
  $taskResult = $taskState->status;
  $taskName = $taskState->Task->name;
  ?>
  <span class="<?php echo $taskState->getHighlightClass() ?>">
    <?php
      switch ($taskResult)
      {
        case TaskState::TASK_DONE_SUCCESS:
          echo 'Вы успешно выполнили задание '.$taskName.'.';
          break;
        case TaskState::TASK_DONE_TIME_FAIL:
          echo 'Вы не успели выполнить задание '.$taskName.' за отведенное время.';
          break;
        case TaskState::TASK_DONE_SKIPPED:
          echo 'Вы решили отказаться от выполнения задания '.$taskName.'.';
          break;
        case TaskState::TASK_DONE_GAME_OVER:
          echo 'Вы не успели выполнить задание '.$taskName.' в связи с окончанием игры.';
          break;
        case TaskState::TASK_DONE_BANNED:
          echo 'Ваше задание '.$taskName.' дисквалифицировано.';
          break;
        case TaskState::TASK_DONE_ABANDONED:
          echo 'Ваше задание '.$taskName.' было отменено. Обратитесь к организаторам.';
          break;
        default:
          echo 'Вы завершили задание '.$taskName.', но не ясно с каким результатом. Обратитесь к организаторам.';
          break;
      }
    ?>
  </span>
  <?php else: ?>
  <p>
    <div class="danger">У Вас должно быть текущее задание, но его не удалось найти.</div>      
  </p>
  <p>
    <div>Скорее всего, оно было отменено. Обратитесь к организаторам.</div>
  </p>
  <?php endif; ?>
</div>
<p>
  Обновляйте страницу время от времени для получения дальнейших инструкций.
</p>

<?php elseif ($teamState->status == TeamState::TEAM_FINISHED): ?>
<div>Ваша команда завершила игру.</div>
<p>
  Ваши достижения:
</p>
<div>
  <?php include_partial('TaskHistory', array('teamState' => $teamState, 'withLink' => false, 'withTime' => true, 'highlight' => true))?>
</div>

<?php elseif ($teamState->status == TeamState::TEAM_BANNED): ?>
<p>
  <div class="warn">Ваша команда дисквалифицирована.</div>
</p>

<?php else: ?>
<p>
  <div class="danger">Чем занята ваша команда - не ясно. Обратитесь к организаторам.</div>
</p>

<?php endif; ?>

<p>
  <div class="info">Время ожидания не влияет на доступное игровое время.</div>
</p>
