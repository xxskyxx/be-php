<?php
/* Содержимое, которое отображается на главной странице
 * выше homeCommon.
 * 
 * Показывается только неавторизованым пользователям.
 * 
 * Рекомендуется не делать слишком большим, чтобы посетители
 * сразу же видели содержимое homeCommon.
 */
?>
<div>
  <p>
    Для доступа ко всем функциям Вам нужно <?php echo link_to('войти', 'auth/login')?>.
  </p>
  <p>
    Если Вы здесь впервые, то сначала <?php echo link_to('зарегистрируйтесь', 'auth/register')?>.
  </p>
</div>