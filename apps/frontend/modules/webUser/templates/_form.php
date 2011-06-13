<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('webUser/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <?php if (!$form->getObject()->isNew()): ?>
  <input type="hidden" name="sf_method" value="put" />
  <?php endif; ?>
  <table cellspacing="0">
    <tbody>
      <?php echo $form; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="Сохранить" />
          <?php if (!$form->getObject()->isNew()): ?>
          <span class="warnAction"><?php echo link_to('Отмена', 'webUser/show?id='.$form->getObject()->getId())?></span>
          <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
</form>