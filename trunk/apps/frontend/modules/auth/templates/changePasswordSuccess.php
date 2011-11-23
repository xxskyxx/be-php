<?php
  render_breadcombs(array(
      link_to('Люди', 'webUser/index'),
      link_to($sf_user->getAttribute('login'),
            'webUser/show?id='.$sf_user->getAttribute('id'))
  ))
?>

<h2>Смена пароля <?php echo $sf_user->getAttribute('login') ?></h2>

<?php echo $form->renderFormTag(url_for('auth/changePassword')); ?>
<?php
echo render_form_using_div(
        $form,
        'Сменить', 
        link_to($sf_user->getAttribute('login'),
            'webUser/show?id='.$sf_user->getAttribute('id'))) ?>
<?php echo '</form>'; ?>