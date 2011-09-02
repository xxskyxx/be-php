<h2>Вход</h2>

<?php echo $form->renderFormTag(url_for('auth/login')); ?>
<?php echo render_form_using_div($form, 'Войти',  ''); ?>
<?php echo '</form>'; ?>
<p>
  Если Вы здесь впервые, то сначала <?php echo link_to('зарегистрируйтесь', 'auth/register')?>.
</p>