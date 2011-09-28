<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_task->Game->name, 'game/show?id='.$_task->game_id),
    link_to('Задания', 'game/show?id='.$_task->game_id.'&tab=tasks')
));
?>
<h2>Создание фильтра перехода</h2>
<?php include_partial('form', array('form' => $form, 'task' => $_task)) ?>