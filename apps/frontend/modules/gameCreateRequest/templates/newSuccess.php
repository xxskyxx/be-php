<?php render_breadcombs(array(link_to('Игры', 'game/index'))) ?>
<h2>Подача заявки на создание игры</h2>

<?php if (SystemSettings::getInstance()->email_game_create): ?>
<p>
  <span class="info">После подачи заявки подтвердите ее через ссылку из письма</span>, которое будет Вам отправлено.
</p>
<?php else: ?>
<p>
  <span class="info">Игра будет создана после проверки заявки модератором.</span>
</p>
<?php endif ?>
<div>
  <div><span class="warn">При подтверждении заявки игра будет создана в регионе команды-организатора.</span></div>
  <div><span class="info">Позже руководитель игры может сменить регион.</span></div>
</div>
    
<?php include_partial('form', array('form' => $form)) ?>