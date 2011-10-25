<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('answer/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>  
  <?php
  echo render_form_using_div(
      $form,
      'Сохранить',
      decorate_span(
          'warnAction',
          link_to(
              'Отмена',
              'task/show?id='.$form->getObject()->task_id,
              array('confirm' => 'Вернуться без сохранения?')))
  );
  ?>
</form>
