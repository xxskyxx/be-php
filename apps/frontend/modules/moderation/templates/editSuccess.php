<?php render_breadcombs(array(
    link_to('Модерирование', 'moderation/show')
)) ?>

<h2>Редактирование системных настроек</h2>
<?php include_partial('form', array('form' => $form)) ?>
