<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($form->getObject()->Game->name, 'game/show?id='.$form->getObject()->game_id),
    link_to('Регистрация', 'game/show?id='.$form->getObject()->game_id.'&tab=teams'),
    link_to($form->getObject()->Team->name, 'teamState/show?id='.$form->getObject()->id)
));
?>
<h2>Настройки команды <?php echo $form->getObject()->Team->name ?> на игру <?php echo $form->getObject()->Game->name ?></h2>
<?php include_partial('form', array('form' => $form)) ?>