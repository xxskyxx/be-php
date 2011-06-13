<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('task/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
          <span class="warnAction"><?php echo link_to('Отмена', 'task/show?id='.$form->getObject()->getId()) ?></span>
          <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
</form>

<div class="comment">
  <h3>Комментарии</h3>
  <ul>
    <?php if (!$form->getObject()->isNew()): ?>
    <li><span class="info">Если "Длительность" не заполнено или равно нулю</span>, то будет установлено значение "Длительность задания" из свойств игры.</li>
    <?php endif ?>
    <li><span class="info">Если "Неверных ответов не более" не заполнено или равно нулю</span>, то будет установлено значение "Неверных ответов не более" из свойств игры.</li>
  </ul>
</div>
