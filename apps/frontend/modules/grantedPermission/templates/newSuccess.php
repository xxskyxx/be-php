<?php
render_breadcombs(array(
    link_to('Люди', 'webUser/index'),
    link_to($form->getObject()->WebUser->login, 'webUser/show?id='.$form->getObject()->web_user_id),
));
?>
<h2>Новое право или запрет для <?php echo $form->getObject()->WebUser->login ?></h2>
<?php include_partial('form', array('form' => $form)) ?>
