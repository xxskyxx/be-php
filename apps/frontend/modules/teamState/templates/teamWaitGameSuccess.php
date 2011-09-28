<?php include_partial('header', array('teamState' => $_teamState)) ?>

<p>
  Игра начнется в <?php echo Timing::timeToStr(Timing::strToDate($_teamState->Game->start_datetime)) ?>.
</p>
<p>
  После наступления момента начала игры обновите страницу для получения дальнейших инструкций.
</p>