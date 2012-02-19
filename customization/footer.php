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

<div style="width:100%; border: none; padding: 0 0 0 0; margin: 0 0 0 0"> 
  <div style="width:70%; display:inline-block;">

    <!-- Коды информеров и прочих баннеров -->

  </div><div style="width:30%; display:inline-block;">
    <span style="text-align: right; font-style: italic; font-size: smaller">&copy;&nbsp;Илья <?php echo mail_to(SystemSettings::getInstance()->contact_email_addr, 'Ключ')?> Воздвиженский</span>
  </div>
</div>

