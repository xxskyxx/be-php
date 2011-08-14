<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('task/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  
  <?php
  //Служебные поля
  $width = get_text_block_size_ex('Когда кем-то выполняется:');
  render_form_field_using_div($form['_csrf_token'], $width);
  render_form_field_using_div($form['id'], $width);
  render_form_field_using_div($form['game_id'], $width);
  ?>
  <h4>Основные</h4>
  <div class="comment"><span class="info">Если "Длительность" пусто или равно нулю</span>, то будет установлено значение из свойств игры.</div>
  <div class="comment"><span class="info">Если "Неверных ответов" пусто или равно нулю</span>, то будет установлено значение из свойств игры.</div>
  <?php
  render_form_field_using_div($form['name'], $width);
  render_form_field_using_div($form['time_per_task_local'], $width);
  render_form_field_using_div($form['try_count_local'], $width);
  ?>
  <h4>Управление</h4>
  <?php
  render_form_field_using_div($form['max_teams'], $width);
  render_form_field_using_div($form['manual_start'], $width);
  render_form_field_using_div($form['locked'], $width);
  ?>
  <h4>Приоритеты опорные</h4>
  <div class="comment"><span class="warn">Приоритеты из трех следующих полей являются взаимоисключающими.</span></div>
  <?php
  render_form_field_using_div($form['priority_free'], $width);
  render_form_field_using_div($form['priority_queued'], $width);
  render_form_field_using_div($form['priority_busy'], $width);
  ?>
  <h4>Приоритеты дополнительные</h4>
  <div class="comment"><span class="info">Эти приоритеты суммируются с опорными.</span></div>
  <?php
  render_form_field_using_div($form['priority_filled'], $width);
  render_form_field_using_div($form['priority_per_team'], $width);
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
              'task/show?id='.$form->getObject()->getId(),
              array('confirm' => 'Вернуться без сохранения?'))),
      $width);
  ?>
  
</form>
