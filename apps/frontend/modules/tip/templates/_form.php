<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('tip/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>  <?php
  //Служебные поля
  $width = get_text_block_size_ex('Выдавать после ответа:');
  render_form_field_using_div($form['_csrf_token'], $width);
  render_form_field_using_div($form['id'], $width);
  render_form_field_using_div($form['task_id'], $width);
  ?>
  <div class="comment"><span class="info">Внутреннее название, на игре известно только организаторам</span></div>
  <?php render_form_field_using_div($form['name'], $width) ?>
  <?php render_form_field_using_div($form['define'], $width) ?>
  <?php if ($form->getObject()->isNew()): ?>
  <div class="comment"><span class="info">Если пусто или равно нулю, то будет взято значение из свойств игры</span></div>
  <?php endif ?>
  <?php render_form_field_using_div($form['delay'], $width) ?>
  <div class="comment"><span class="info">Если указан ответ, по подсказка выдается сразу после ввода этого ответа, а не по задержке</span></div>
  <?php render_form_field_using_div($form['answer_id'], $width) ?>
  
  <?php
  //Код отправки
  render_form_commit_using_div(
      $form,
      'Сохранить',
      decorate_span(
          'warnAction',
          link_to(
              'Отмена',
              'task/show?id='.$form->getObject()->task_id,
              array('confirm' => 'Вернуться без сохранения?'))),
      $width);
  ?>
  
</form>