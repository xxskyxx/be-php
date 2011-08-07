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
    <span class="warn">В настоящий момент автоматическая отправка активационного ключа при создании нового аккаунта не работает</span>. Для получения ключа активации запросите его <a href="mailto://vozdvin@mail.ru">письмом у администрации</a>, отправив это письмо с указанного Вами в анкете адреса и написав в теме "BE&nbsp;активация&nbsp;<ваше_имя_пользователя>".
  </p>
  <div class="spaceAfter"></div>
  <p>
    <span class="warn">Ключ активации одноразовый!</span> Если эту учетную запись снова заблокируют, то потребуется другой ключ.
  </p>
</div>

