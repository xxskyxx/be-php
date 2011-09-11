<?php
/* Заголовок сайта, общий для всех страниц, кроме текущего задания.
 * 
 * Рекомендуется, чтобы он содержал логотип и название сайта.
 * 
 * Не рекомендуется использовать элементы стиля "float: right",
 * так как это может сделать непредсказуемым расположение
 * ссылок авторизации (вход/выход, регистрация),
 * находящихся в правом верхнем углу.
 * 
 */
?>

<div style="min-height: 52px">
  <div style="float: left; margin: 0 1ex 3px 0">
    <img src="/customization/images/logo.png" alt="[BE]" onClick="document.location='/home/index'" />
  </div>

  <div>
    <span style="font-weight: bold"><?php echo SystemSettings::getInstance()->site_name ?></span>
  </div>
  <div>
    <span style="font-size: smaller"><?php echo link_to('Проект', 'http://code.google.com/p/be-php') ?> использует <?php echo link_to('symfony', 'http://symfony-project.org') ?> и <?php echo link_to('PHP', 'http://www.php.net') ?>.</span>
  </div>
</div>