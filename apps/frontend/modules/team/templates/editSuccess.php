<?php
  render_breadcombs(array(
      link_to('Команды', 'team/index'),
      link_to($form->getObject()->name,
          'team/show?id='.$form->getObject()->id,
          array('confirm' => 'Вернуться без сохранения?'))
  ))
?>

<h2>Правка свойств команды <?php echo $form->getObject()->name ?></h2>
<?php include_partial('form', array('form' => $form)) ?>