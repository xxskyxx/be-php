<?php
/* Заголовок сайта, общий для всех страниц, кроме текущего задания.
 * 
 * Рекомендуется, чтобы он содержал логотип и название сайта.
 * 
 * Не рекомендуется использовать элементы стиля "float: right",
 * так как это может сделать непредсказуемым расположение
 * ссылок авторизации (вход/выход, регистрация),
 * находящихся в правом верхнем углу.
 */
?>

<div class="hidden">
  <!-- Код счетчиков размещать здесь -->
</div>

<div>
  <!-- Коды информеров и прочих баннеров -->
</div>

<div style="min-height: 60px">
  <div style="float: left; margin: 0.2em 1ex 3px 0">
    <a href="/home/index" class="banner"><img src="/customization/images/logo.png" alt="[BE]" /></a>
  </div>
  <div>
    <span style="font-weight: bold"><?php echo SystemSettings::getInstance()->site_name ?></span>
  </div>
  <div>
    <span style="font-size: smaller; font-weight: bold"><?php echo link_to('Бесплатный движок', 'http://code.google.com/p/be-php') ?> для интерактивных игр</span>
  </div>
  <div>
    <span style="font-size: smaller">типа Дозор (Dozor), Схватка (Encounter), Квест (Quest) и похожих</span>
  </div>
</div>