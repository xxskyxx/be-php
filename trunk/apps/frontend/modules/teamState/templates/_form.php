<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('teamState/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table cellspacing="0">
    <tbody>
      <?php echo $form ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="Сохранить" />
          <?php if (!$form->getObject()->isNew()): ?>
          <span class="warnAction"><?php echo link_to('Отмена', 'game/show?id='.$form->getObject()->game_id) ?></span>
          <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
</form>
