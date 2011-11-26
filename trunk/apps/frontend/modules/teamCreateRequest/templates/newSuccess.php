<?php render_breadcombs(array(link_to('Команды', 'team/index'))) ?>
<h2>Подача заявки на создание команды</h2>
<?php if (SystemSettings::getInstance()->fast_team_create): ?>
<p>
  <span class="info">Разрешено создание команд без модерирования:</span> после подачи заявки подтвердите ее самостоятельно на странице со списком команд.
</p>
<?php elseif (SystemSettings::getInstance()->email_team_create): ?>
<p>
  <span class="info">После подачи заявки подтвердите ее через ссылку из письма</span>, которое будет Вам отправлено.
</p>
<?php else: ?>
<p>
  <span class="info">Команда будет создана после проверки заявки модератором.</span>
</p>
<?php endif ?>
<div>
  <div><span class="warn">При подтверждении заявки команда будет зарегистрирована в регионе автора заявки.</span></div>
  <div><span class="info">Позже капитан команды может сменить регион.</span></div>
</div>
<?php include_partial('form', array('form' => $form)) ?>
