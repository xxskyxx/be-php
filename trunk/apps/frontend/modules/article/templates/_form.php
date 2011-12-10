<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('article/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <?php
  $width = get_text_block_size_ex('Название:');
  render_form_field_using_div($form['_csrf_token'], $width);
  render_form_field_using_div($form['id'], $width);
  
  render_form_field_using_div($form['name'], $width);
  render_form_field_using_div($form['path'], $width);
  render_form_field_using_div($form['text'], $width);
  
  render_form_commit_using_div(
      $form,
      'Сохранить',
      decorate_span(
          'warnAction',
          link_to(
              'Отмена',
              'article/index',
              array('confirm' => 'Вернуться без сохранения?'))),
      $width);
  ?>  
</form>