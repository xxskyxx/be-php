<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($form->getObject()->Game->name, 'game/show?id='.$form->getObject()->game_id),
    link_to('Задания', 'game/show?id='.$form->getObject()->game_id.'&tab=tasks')
));
?>
<h2>Новое задание игры <?php echo $form->getObject()->Game->name ?></h2>
<?php include_partial('form', array('form' => $form)) ?>