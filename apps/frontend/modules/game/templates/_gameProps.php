<?php
/* Входные данные:
 * - $_game - игра
 * - $_retUrlRaw - ссылка обратного перехода
 * - $_sessionCanManage - руководитель игры
 * - $_sessionIsModerator - модератор игры
 */
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
?>

<?php
render_h3_inline_begin('Настройки');
if ($_sessionCanManage || $_sessionIsModerator) echo ' '.decorate_span('safeAction', link_to('Редактировать', 'game/edit?id='.$_game->id));
if ($_sessionIsModerator) echo '&nbsp'.decorate_span('dangerAction', link_to('Удалить игру', 'game/delete?id='.$_game->id, array('method' => 'delete', 'confirm' => 'Вы точно хотите удалить игру '.$_game->name.'?')));
render_h3_inline_end();
?>

<?php $width = get_text_block_size_ex('Автоматический пересчет:'); ?>

<h4>Общее</h4>
<?php
render_property_if($_sessionIsModerator,
                   'Id:', $_game->id, $width);
render_property   ('Организаторы:', ($_game->team_id <= 0) ? $_game->getTeamBackupName() : link_to($_game->Team->name, 'team/show?id='.$_game->Team->id, array ('target' => 'new')), $width);
render_property   ('Название:', $_game->name, $width);
render_property   ('Описание:', 'см.&nbsp;'.link_to('афишу', 'game/info?id='.$_game->id, array ('target' => 'new')), $width);
?>
<h4>Регламент</h4>
<?php
render_property   ('Брифинг:', $_game->start_briefing_datetime, $width);
render_property   ('Начало игры:', $_game->start_datetime, $width);
render_property   ('Длительность игры:', $_game->time_per_game.'&nbsp;мин', $width);
render_property   ('Окончание игры:', $_game->stop_datetime, $width);
render_property   ('Награждение:', $_game->finish_briefing_datetime, $width);
?>
<h4>Параметры новых заданий</h4>
<?php
render_property   ('Длительность:', $_game->time_per_task.'&nbsp;мин', $width);
render_property   ('Интервал подсказок:', $_game->time_per_task.'&nbsp;мин', $width);
render_property   ('Неверных ответов:', 'не&nbsp;более&nbsp;'.$_game->try_count, $width);
render_property   ('Название формулировки:', $_game->task_define_default_name, $width);
render_property   ('Префикс подсказки:', $_game->task_tip_prefix, $width);
?>
<h4>Параметры расчета состояния</h4>
<?php
render_property   ('Автоматический пересчет:', 'раз&nbsp;в&nbsp;'.$_game->update_interval.'&nbsp;c', $width);
render_property   ('Максимальный интервал:', $_game->update_interval_max.'&nbsp;c', $width);
render_property   ('Пересчет командами:', ($_game->teams_can_update) ? 'Разрешен' : 'Не разрешен', $width);
?>