<?php
/* Содержимое, которое в любом случае отображается на главной странице:
 * ниже homeNonAuth или homeAuth.
 * 
 * Рекомендуется, чтобы оно вкратце описывало сайт.
 */
?>
<div class="hr">
  <h1><img src="/customization/images/favicon.png" alt="[BE]" />&nbsp;<?php echo SystemSettings::getInstance()->site_name ?></h1>
  <h2>Бесплатный движок для интерактивных игр</h2>
  <h4>типа Дозор (Dozor, Dzzzr), Схватка (Encounter), Квест (Quest) и похожих</h4>
</div>
<div class="hr">
  <?php
  if ($homeArticle = Article::byName('Шаблонные-Главная'))
  {
    echo Utils::decodeBB($homeArticle->text);
  }
  ?>
</div>