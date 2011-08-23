<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($form->getObject()->Task->Game->name, 'game/show?id='.$form->getObject()->Task->game_id),
    link_to('Задания', 'game/show?id='.$form->getObject()->Task->game_id.'&tab=tasks'),
    link_to($form->getObject()->Task->name, 'task/show?id='.$form->getObject()->task_id)
));
?>

<h2>Редактирование ответа к заданию <?php echo $form->getObject()->Task->name ?></h2>
<?php include_partial('form', array('form' => $form)) ?>
