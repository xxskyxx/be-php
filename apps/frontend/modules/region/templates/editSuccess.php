<?php
render_breadcombs(array(
    link_to('Модерирование', 'moderation/show'),
    link_to('Регионы', 'region/index', array('confirm' => 'Вернуться без сохранения?'))
    ))
?>

<h2>Правка региона</h2>
<?php include_partial('form', array('form' => $form)) ?>
