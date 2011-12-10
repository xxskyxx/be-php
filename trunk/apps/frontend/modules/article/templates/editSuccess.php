<?php
  render_breadcombs(array(
      link_to('Статьи', 'article/index'),
      link_to($form->getObject()->name,
          'article/show?id='.$form->getObject()->id,
          array('confirm' => 'Вернуться без сохранения?'))
  ))
?>

<h2>Редактирование статьи</h2>

<?php include_partial('form', array('form' => $form)) ?>
