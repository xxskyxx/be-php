<?php
/* Содержимое, которое отображается на главной странице
 * выше homeCommon.
 * 
 * Показывается только авторизованым пользователям.
 * 
 * Следует учитывать, что содержимое homeCommon будет
 * показано ниже этого. 
 */
?>
<div>
  <p>
    Добро пожаловать, <?php echo $sf_user->getAttribute('login') ?>!
  </p>
</div>