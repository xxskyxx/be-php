<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('TaskTransition/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '').($form->getObject()->isNew() ? '?taskId='.$task->id : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php
  //Служебные поля
  $width = get_text_block_size_ex('Разрешить выбор вручную:');
  render_form_field_using_div($form['_csrf_token'], $width);
  render_form_field_using_div($form['id'], $width);
  render_form_field_using_div($form['task_id'], $width);
  //Подсказка
  render_named_line($width, 'С задания:', array($task->name));
  //Видимые поля
  render_form_field_using_div($form['target_task_id'], $width);
  render_form_field_using_div($form['allow_on_success'], $width);
  render_form_field_using_div($form['allow_on_fail'], $width);
  render_form_field_using_div($form['manual_selection'], $width);
  ?>
  
  <?php
  //Код отправки
  render_form_commit_using_div(
      $form,
      'Сохранить',
      decorate_span(
          'warnAction',
          link_to(
              'Отмена',
              'task/show?id='.$task->id,
              array('confirm' => 'Вернуться без сохранения?'))),
      $width);
  ?>

</form>
