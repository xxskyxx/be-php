<h2>Активация учетной записи</h2>

<div class="danger">
  <p>
    Ваша учетная запись отключена.
  </p>
</div>
<div class="spaceAfter">
  <p>
    Вы можете разблокировать ее, введя ключ активации.
  </p>
</div>

<?php echo $form->renderFormTag(url_for('auth/activateManual')); ?>
<?php echo render_form_using_div($form, 'Активировать',  ''); ?>
<?php echo '</form>'; ?>

<div class="spaceBefore">
  <p>
    <span class="warn">В настоящий момент автоматическая отправка активационного ключа при создании нового аккаунта не работает</span>.
  </p>
  <p>
    Для получения ключа активации запросите его <?php echo mail_to(SystemSettings::getInstance()->contact_email_addr, 'письмом у администрации') ?>, отправив это письмо с указанного Вами в анкете адреса и написав в теме <span class="info">&quot;BE&nbsp;активация&nbsp;<ваше_имя_пользователя>&quot;</span>.
  </p>
  <div class="spaceAfter"></div>
  <p>
    <span class="warn">Ключ активации одноразовый!</span> Если эту учетную запись снова заблокируют, то потребуется другой ключ.
  </p>
</div>

