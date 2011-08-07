<?php echo render_breadcombs(array(link_to('Игры', 'game/index'))) ?>

<h2>Создание игры</h2>
<?php include_partial('form', array('form' => $form)) ?>
