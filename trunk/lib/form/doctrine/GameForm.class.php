<?php

/**
 * Game form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GameForm extends BaseGameForm
{

  public function configure()
  {
    unset($this['team_name_backup']); //Имя команды поддерживается автоматически.
    unset($this['status']); //Состояние меняется автоматически.
    unset($this['started_at']); //Дата старта не нужна.
    unset($this['finished_at']); //Дата финиша не нужна.
    unset($this['game_last_update']); //Дата пересчета не нужна.

    //Добавим проверку дат.
    $this->validatorSchema['start_briefing_datetime'] = new sfValidatorAnd(array(
            $this->validatorSchema['start_briefing_datetime'],
            new sfValidatorDateTime()
        ));
    $this->validatorSchema['start_datetime'] = new sfValidatorAnd(array(
            $this->validatorSchema['start_datetime'],
            new sfValidatorDateTime()
        ));
    $this->validatorSchema['stop_datetime'] = new sfValidatorAnd(array(
            $this->validatorSchema['stop_datetime'],
            new sfValidatorDateTime()
        ));
    $this->validatorSchema['finish_briefing_datetime'] = new sfValidatorAnd(array(
            $this->validatorSchema['finish_briefing_datetime'],
            new sfValidatorDateTime()
        ));

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'name' => 'Название:',
        'description' => 'Описание:',
        'team_id' => 'Организаторы:',
        'start_briefing_datetime' => 'Брифинг (ГГГГ-ММ-ДД ЧЧ:ММ:СС):',
        'start_datetime' => 'Начало игры (ГГГГ-ММ-ДД ЧЧ:ММ:СС):',
        'stop_datetime' => 'Окончание игры (ГГГГ-ММ-ДД ЧЧ:ММ:СС):',
        'finish_briefing_datetime' => 'Награждение (ГГГГ-ММ-ДД ЧЧ:ММ:СС):',
        'time_per_game' => 'Длительность игры, мин:',
        'time_per_task' => 'Длительность задания, мин:',
        'time_per_tip' => 'Интервал между подсказками, мин:',
        'try_count' => 'Неверных ответов не более:',
        'update_interval' => 'Интервал пересчета, сек:',
        'teams_can_update' => 'Разрешить командам пересчет состояния:',
        'update_interval_max' => 'Максимальный интервал пересчета, сек:',
        'task_define_default_name' => 'Название формулировки по умолчанию:',
        'task_tip_prefix' => 'Название подсказки по умолчанию:'
    ));
  }

}
