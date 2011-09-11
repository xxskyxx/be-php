<?php
/* Подвал сайта, общий для всех страниц, кроме текущего задания.
 * 
 * Рекомендуется, чтобы он содержал данные об авторах и
 * контактную информацию администрации сайта.
 * 
 * Рекомендуется адрес обратной связи брать из настроек сайта
 * следующим образом:
 *   SystemSettings::getInstance()->contact_email_addr
 * Пример ниже.
 */
?>

<div class="hr" style="text-align: right; font-style: italic; font-size: smaller">
  <div>&copy;&nbsp;Илья <?php echo mail_to(SystemSettings::getInstance()->contact_email_addr, 'Ключ')?> Воздвиженский</div>
</div>