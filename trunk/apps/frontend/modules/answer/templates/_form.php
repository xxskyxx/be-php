<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('answer/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>  
  <?php
  //Служебные поля
  $width = get_text_block_size_ex('Только для:');
  render_form_field_using_div($form['_csrf_token'], $width);
  render_form_field_using_div($form['id'], $width);
  render_form_field_using_div($form['task_id'], $width);
  ?>
  <div class="comment"><span class="info">Внутреннее название, на игре известно только организаторам</span></div>
  <?php render_form_field_using_div($form['name'], $width) ?>
  <div class="comment"><span class="info">Значение, которое вводится для зачета ответа:</span></div>
  <div class="comment"><span class="info">- для латинских букв регистр не учитывается.</span></div>
  <div class="comment"><span class="warn">- русские буквы нужно будет вводить с учетом регистра!</span></div>
  <?php render_form_field_using_div($form['value'], $width) ?>
  <div class="comment"><span class="warn">Текст, который виден игрокам и позволяет им различать ответы (например - код опасности)</span></div>
  <?php render_form_field_using_div($form['info'], $width) ?>
  <div class="comment"><span class="info">Если указана команда, то ответ считается правильным только при вводе именно ей</span></div>
  <?php render_form_field_using_div($form['team_id'], $width) ?>
  
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
