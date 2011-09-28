<?php include_partial('header', array('teamState' => $_teamState)) ?>

<p>
  Игра началась.
</p>
<p>
  <span class="warn">Ваша команда стартует в <?php echo Timing::timeToStr($_teamState->getActualStartDateTime()) ?>.</span>
</p>
<p>
  После наступления момента старта Вашей команды обновите страницу для получения дальнейших инструкций.
</p>