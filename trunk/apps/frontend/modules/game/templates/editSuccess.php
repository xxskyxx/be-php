<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($game->name, 'game/show?id='.$form->getObject()->id)
))
?>

<h2>Редактирование игры <?php echo $form->getObject()->name ?></h2>
<?php include_partial('form', array('form' => $form)) ?>
