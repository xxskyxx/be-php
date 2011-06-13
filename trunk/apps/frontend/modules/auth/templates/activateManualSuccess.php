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
<table cellspacing="0">
  <tbody>
    <?php echo $form; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2"><input type="submit" value="Активировать" /></td>
    </tr>
  </tfoot>
</table>
<?php echo '</form>'; ?>

<div class="spaceBefore">
  <p>
    <span class="info">Если Вы только что зарегистрировались здесь, то ключ активации был выслан Вам на адрес электронной почты</span>, который Вы указали при регистрации. В этом же письме есть ссылка для автоматической активации учетной записи.
  </p>
  <p>
    Если Вы не получили это письмо, а также в иных случаях <span class="info">Вы можете получить ключ активации <?php echo mail_to('vozdvin@mail.ru', 'связавшись с администрацией сайта') ?> и </span><span class="warn">аргументированно</span> объяснив необходимость доступа к учетной записи.
  </p>
  <p>
    <span class="warn">Ключ активации одноразовый!</span> Если эту учетную запись снова заблокируют, то потребуется другой ключ.
  </p>
</div>

