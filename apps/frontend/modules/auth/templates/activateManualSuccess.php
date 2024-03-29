<h2>Активация учетной записи</h2>

<div class="danger">
  <p>
    Ваша учетная запись отключена.
  </p>
</div>
<p>
  Вы можете разблокировать ее, введя ключ активации.
</p>

<?php echo $form->renderFormTag(url_for('auth/activateManual')); ?>
<?php echo render_form_using_div($form, 'Активировать',  ''); ?>
<?php echo '</form>'; ?>

<p>
  <span class="info">Если Вы только что зарегистрировались здесь, то ключ активации был выслан Вам на электронную почту, которую Вы указали при регистрации</span>. Через несколько минут Вы должны его получить. Если через длительное время Вы так и не получите это письмо, то возможно, что оно было расценено сервером как спам, и искать его надо в соответствующей папке Вашего почтового ящика.
</p>
<p>
  Вы можете <?php echo mail_to(SystemSettings::getInstance()->contact_email_addr, 'обратиться к администрации сайта') ?> за помощью или разъяснениями, но убедительно просим делать это только в крайнем случае.
</p>
<p>
  <span class="warn">Ключ активации одноразовый!</span> Если эту учетную запись снова заблокируют, то потребуется другой ключ.
</p>
