<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="<?php echo url_for('answer/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
          <span class="warnAction"><?php echo link_to('Отмена', 'answer/show?id='.$form->getObject()->getId()) ?></span>
          <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
</form>

<div class="comment">
  <h3>Комментарии</h3>
  <ul>
    <li><span class="info">"Название"</span> - строка, внутреннее название, исвестно только организаторам.</li>
    <li><span class="warn">"Описание"</span> - строка, которую видит пользователь и которая позволяет ему различать ответы (например - код опасности).</li>
    <li><span class="info">"Значение"</span> - строка, которую должен ввести пользователь для зачета ответа.</li>
  </ul>    
</div>

