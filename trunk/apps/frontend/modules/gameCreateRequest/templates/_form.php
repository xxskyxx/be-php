<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('gameCreateRequest/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php
  //Служебные поля
  $width = get_text_block_size_ex('Сообщение модератору:');
  render_form_field_using_div($form['_csrf_token'], $width);
  render_form_field_using_div($form['id'], $width);
  render_form_field_using_div($form['team_id'], $width);
  //Подсказка
  render_property('Организаторы:', Team::byId($form['team_id']->getValue()), $width);
  //Видимые поля
  render_form_field_using_div($form['name'], $width);
  render_form_field_using_div($form['description'], $width);

  //Код отправки
  render_form_commit_using_div(
      $form,
      'Подать заявку',
      decorate_span(
          'warnAction',
          link_to(
              'Отмена',
              'game/index',
              array('confirm' => 'Вернуться без сохранения?'))),
      $width);
?>
</form>