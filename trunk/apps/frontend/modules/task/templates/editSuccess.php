<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($form->getObject()->Game->name, 'game/show?id='.$form->getObject()->game_id),
    link_to('Задания', 'game/show?id='.$form->getObject()->game_id.'&tab=tasks'),
    link_to($form->getObject()->name, 'task/show?id='.$form->getObject()->id)
));
?>

<h2>Редактирование свойств задания <?php echo $form->getObject()->name ?></h2>
<?php include_partial('form', array('form' => $form)) ?>
