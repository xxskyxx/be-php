<?php
render_breadcombs(array(
    link_to('Игры', 'game/index'),
    link_to($_task->Game->name, 'game/show?id='.$_task->game_id),
    link_to('Задания', 'game/show?id='.$_task->game_id.'&tab=tasks'),
    link_to($_task->name, 'task/show?id='.$_task->id)
));
?>
<h2>Редактирование правила перехода</h2>
<?php include_partial('form', array('form' => $form, 'task' => $_task)) ?>