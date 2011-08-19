<?php render_breadcombs(array(link_to('Игры', 'game/index'))) ?>
<h2>Подача заявки на создание игры</h2>
<p>
  <span class="info">Игра будет создана после проверки заявки модератором.</span>
</p>
<?php include_partial('form', array('form' => $form)) ?>