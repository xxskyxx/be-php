<?php
include_partial('header', array('teamState' => $_teamState));
$taskState = $_teamState->getCurrentTaskState()
?>

<div>
  <?php if ($taskState): ?>
  <span class="<?php echo $taskState->getHighlightClass() ?>">
    <?php
      switch ($taskState->status)
      {
        case TaskState::TASK_DONE_SUCCESS: echo 'Вы успешно выполнили задание.'; break;
        case TaskState::TASK_DONE_TIME_FAIL: echo 'Вы не успели выполнить задание за отведенное время.'; break;
        case TaskState::TASK_DONE_SKIPPED: echo 'Вы решили отказаться от выполнения задания.'; break;
        case TaskState::TASK_DONE_GAME_OVER: echo 'Вы не успели выполнить задание в связи с окончанием игры.'; break;
        case TaskState::TASK_DONE_BANNED: echo 'Ваше задание дисквалифицировано.'; break;
        case TaskState::TASK_DONE_ABANDONED: echo 'Ваше задание было отменено. Обратитесь к организаторам.'; break;

        default: echo 'Вы завершили задание, но не ясно с каким результатом. Обратитесь к организаторам.'; break;
      }
    ?>
  </span>
  <?php else: ?>
  <p>
    <div class="danger">У Вас должно быть текущее задание, но его не удалось найти.</div>      
  </p>
  <p>
    <div class="warn">Скорее всего, оно было отменено.</div>
  </p>
  <p>
    <div class="info">Обратитесь к организаторам.</div>
  </p>
  <?php endif; ?>
</div>

<p>
  Обновляйте страницу время от времени для получения дальнейших инструкций.
</p>