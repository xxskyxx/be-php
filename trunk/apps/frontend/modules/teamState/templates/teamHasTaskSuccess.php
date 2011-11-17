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
  <div class="danger">
    <p>
      У Вас должно быть текущее задание, но его не удалось найти.
    </p>
  </div>
  <div class="warn">
    <p>
      Скорее всего, оно было отменено.
    </p>
  </div>
  <div class="info">
    <p>
      Обратитесь к организаторам.
    </p>
  </div>
  <?php endif; ?>
</div>

<p>
  Обновляйте страницу время от времени для получения дальнейших инструкций.
</p>