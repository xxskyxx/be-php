<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('tip/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <?php if (!$form->getObject()->isNew()): ?>
  <input type="hidden" name="sf_method" value="put" />
  <?php endif; ?>
  <table cellspacing="0">
    <tbody>
      <?php echo $form ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="Сохранить" />
          <?php if (!$form->getObject()->isNew()): ?>
          <span class="warnAction"><?php echo link_to('Отмена', 'task/show?id='.$form->getObject()->task_id) ?></span>
          <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
</form>

<div class="comment">
  <h3>Комментарии</h3>
  <ul>
    <li><span class="info">"Название"</span> - внутреннее название, исвестно только организаторам.</li>
    <?php if ($form->getObject()->isNew()): ?>
    <li><span class="warn">Если поле "Задержка выдачи" не заполнено или равно нулю</span>, то в него будет поставлено значение "Интервал между подсказками" из общих свойств игры, умноженное на число уже добавленных в задание подсказок.</li>
    <?php endif ?>
    <li><span class="info">"Выдавать после ответа"</span> - <span class="warn">если заполнено, то подсказка не будет выдаваться автоматически</span>, но <span class="info">будет выдана сразу, как только будет введен указанный ответ</span>.</li>
  </ul>
</div>