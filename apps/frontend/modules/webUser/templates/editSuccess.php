<?php
  render_breadcombs(array(
      link_to('Люди', 'webUser/index'),
      link_to($form->getObject()->login,
          'webUser/show?id='.$form->getObject()->id,
          array('confirm' => 'Вернуться без сохранения?'))
  ))
?>

<h2>Правка анкеты  <?php echo $form->getObject()->login ?></h2>
<?php include_partial('form', array('form' => $form)) ?>