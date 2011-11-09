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
render_named_line_if($_sessionIsModerator,
                     $width, 'Id:', $_game->id);
render_named_line   ($width, 'Организаторы:', ($_game->team_id <= 0) ? $_game->getTeamBackupName() : link_to($_game->Team->name, 'team/show?id='.$_game->Team->id, array ('target' => 'new')));
render_named_line   ($width, 'Название:', $_game->name);
render_named_line   ($width, 'Описание:', 'см.&nbsp;'.link_to('афишу', 'game/info?id='.$_game->id, array ('target' => 'new')));
?>
<h4>Регламент</h4>
<?php
render_named_line   ($width, 'Брифинг:', $_game->start_briefing_datetime);
render_named_line   ($width, 'Начало игры:', $_game->start_datetime);
render_named_line   ($width, 'Длительность игры:', $_game->time_per_game.'&nbsp;мин');
render_named_line   ($width, 'Окончание игры:', $_game->stop_datetime);
render_named_line   ($width, 'Награждение:', $_game->finish_briefing_datetime);
?>
<h4>Параметры новых заданий</h4>
<?php
render_named_line   ($width, 'Длительность:', $_game->time_per_task.'&nbsp;мин');
render_named_line   ($width, 'Интервал подсказок:', $_game->time_per_task.'&nbsp;мин');
render_named_line   ($width, 'Неверных ответов:', 'не&nbsp;более&nbsp;'.$_game->try_count);
render_named_line   ($width, 'Название формулировки:', $_game->task_define_default_name);
render_named_line   ($width, 'Префикс подсказки:', $_game->task_tip_prefix);
?>
<h4>Параметры расчета состояния</h4>
<?php
render_named_line   ($width, 'Автоматический пересчет:', 'раз&nbsp;в&nbsp;'.$_game->update_interval.'&nbsp;c');
render_named_line   ($width, 'Максимальный интервал:', $_game->update_interval_max.'&nbsp;c');
render_named_line   ($width, 'Пересчет командами:', ($_game->teams_can_update) ? decorate_span('warn', 'Разрешен') : 'Не разрешен');
?>