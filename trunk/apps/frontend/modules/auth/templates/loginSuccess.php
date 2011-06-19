<h2>Вход</h2>

<?php echo $form->renderFormTag(url_for('auth/login')); ?>
<table cellspacing="0">
  <tbody>
    <?php echo $form; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2"><input type="submit" value="Войти" /></td>
    </tr>
  </tfoot>
</table>
<?php echo '</form>'; ?>
<div class="spaceBefore">
  Если Вы здесь впервые, то сначала <?php echo link_to('зарегистрируйтесь', 'auth/register')?>
</div>