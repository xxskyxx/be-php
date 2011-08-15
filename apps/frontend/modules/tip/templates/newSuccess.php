<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($form->getObject()->Task->Game->name, 'game/show?id='.$form->getObject()->Task->game_id),
    link_to('Задания', 'game/show?id='.$form->getObject()->Task->game_id.'&tab=tasks')
));
?>

<h2>Новая подсказка к заданию <?php echo $form->getObject()->Task->name ?></h2>
<?php include_partial('form', array('form' => $form)) ?>
