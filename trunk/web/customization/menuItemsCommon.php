<?php
/* Элементы главного меню и карты сайта,
 * отображаемые в любом случае.
 * 
 * Каждый элемент должен быть обернут в тэг <li></li>.
 * 
 * Рекомендуется чтобы каждый элемент представлял собой
 * одно слово без пробелов, являющееся ссылкой,
 * но это не обязательно.
 * 
 * Дополнительное CSS-оформление использовать осторожно,
 * может нарушиться автоматическая интеграция.
 */
?>
<li><?php echo link_to_static('Инструкции', 'help/index.php') ?></li>
