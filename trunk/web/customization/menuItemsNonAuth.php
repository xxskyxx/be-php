<?php
/* Элементы главного меню,
 * отображаемые в случае, когда пользователь
 * не авторизован, отображаются перед
 * элементами menuItemsCommon.
 * 
 * Каждый элемент должен быть обернут в тэг <li></li>,
 * еще лучше, если они будут генерироваться через PHP,
 * т.к. это предоствратит появление лишних невидимых
 * символов между элементами меню.
 * 
 * Рекомендуется чтобы каждый элемент представлял собой
 * одно слово без пробелов, являющееся ссылкой,
 * но это не обязательно.
 * 
 * Дополнительное CSS-оформление использовать осторожно,
 * может нарушиться автоматическая интеграция.
 */
?>

<li><?php echo link_to('Вход', 'auth/login') ?></li>
<li><?php echo link_to('Регистрация', 'auth/register') ?></li>

