<h2>Смена пароля пользователя <?php echo $sf_user->getAttribute('login') ?></h2>

<?php echo $form->renderFormTag(url_for('auth/changePassword')); ?>
<table cellspacing="0">
  <tbody>
    <?php echo $form; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2"><input type="submit" value="Сменить" /></td>
    </tr>
  </tfoot>
</table>
<?php echo '</form>'; ?>