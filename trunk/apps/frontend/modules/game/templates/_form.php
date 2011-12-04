<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('game/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  
  <?php
  //Служебные поля
  $width = get_text_block_size_ex('Пересчет самими командами:');
  render_form_field_using_div($form['_csrf_token'], $width);
  render_form_field_using_div($form['id'], $width);
  ?>
  
  <h4>Общее</h4>
  <?php
  render_form_field_using_div($form['team_id'], $width);
  render_form_field_using_div($form['name'], $width);
  render_form_field_using_div($form['region_id'], $width);
  render_form_field_using_div($form['short_info'], $width);
  render_form_field_using_div($form['short_info_enabled'], $width);
  render_form_field_using_div($form['description'], $width);
  ?>
  <h4>Регламент</h4>
  <?php
  render_form_field_using_div($form['start_briefing_datetime'], $width);
  render_form_field_using_div($form['start_datetime'], $width);
  render_form_field_using_div($form['time_per_game'], $width);
  render_form_field_using_div($form['stop_datetime'], $width);
  render_form_field_using_div($form['finish_briefing_datetime'], $width);
  ?>
  <h4>Параметры новых заданий</h4>
  <?php
  render_form_field_using_div($form['time_per_task'], $width);
  render_form_field_using_div($form['time_per_tip'], $width);
  render_form_field_using_div($form['try_count'], $width);
  render_form_field_using_div($form['task_define_default_name'], $width);
  render_form_field_using_div($form['task_tip_prefix'], $width);
  ?>
  <h4>Параметры расчета состояния</h4>
  <?php
  render_form_field_using_div($form['update_interval'], $width);
  render_form_field_using_div($form['update_interval_max'], $width);
  render_form_field_using_div($form['teams_can_update'], $width);
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
              'game/show?id='.$form->getObject()->getId(),
              array('confirm' => 'Вернуться без сохранения?'))),
      $width);
  ?>
  
</form>
