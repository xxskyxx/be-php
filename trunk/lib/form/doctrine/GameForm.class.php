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
        //Общее
        'name' => 'Название:',
        'team_id' => 'Организаторы:',
        'description' => 'Описание:',
        //Регламент
        'start_briefing_datetime' => 'Брифинг:',
        'start_datetime' => 'Начало игры:',
        'time_per_game' => 'Длительность игры:',
        'stop_datetime' => 'Окончание игры:',
        'finish_briefing_datetime' => 'Награждение:',
        //Параметры новых задания
        'time_per_task' => 'Длительность:',
        'time_per_tip' => 'Интервал подсказок:',
        'try_count' => 'Неверных ответов:',
        'task_define_default_name' => 'Название формулировки:',
        'task_tip_prefix' => 'Префикс подсказки:',
        //Параметры расчета состояния
        'update_interval' => 'Автоматический пересчет:',
        'update_interval_max' => 'Максимальный интервал:',
        'teams_can_update' => 'Пересчет командами:'
    ));
    //Указываем подсказки:
    $this->getWidgetSchema()->setHelps(array(
        //Общее
        'name' => '',
        'team_id' => 'Команда организаторов.|<span class="warn">Если Вы измените это поле, то можете потерять возможность редактирования игры!</span>',
        'description' => 'Разрешен BBCode.',
        //Регламент
        'start_briefing_datetime' => 'ГГГГ-ММ-ДД ЧЧ:ММ:СС.',
        'start_datetime' => 'ГГГГ-ММ-ДД ЧЧ:ММ:СС.',
        'time_per_game' => 'мин.|Для каждой команды отсчитывается с момента ее старта, причем простой между заданиями в это время не входит.',
        'stop_datetime' => 'ГГГГ-ММ-ДД ЧЧ:ММ:СС.|В этот момент игра останавливается принудительно вне зависимости от оставшегося у команд игрового времени и/или реального момента начала игры.|<span class="warn">Дата должна отстоять от начала игры на интервал не меньший, чем сумма длительности игры и самой большой задержки старта команд.</span>|<span class="info">Рекомендуется зарезервировать время на ожидание между заданиями и/или предполагаемое пребывание команд в очередях.</span>',
        'finish_briefing_datetime' => 'ГГГГ-ММ-ДД ЧЧ:ММ:СС.',
        //Параметры новых задания
        'time_per_task' => 'мин.',
        'time_per_tip' => 'мин.',
        'try_count' => 'не&nbsp;более&nbsp;...&nbsp;.',
        'task_define_default_name' => '',
        'task_tip_prefix' => '',
        //Параметры расчета состояния
        'update_interval' => 'раз&nbsp;в&nbsp;...&nbsp;с.|<span class="warn">Будет работать только если его принудительно запустить со страницы управления игрой.</span>',
        'update_interval_max' => 'с.|Если пересчет состояния будет выполнен с интервалом больше указанного здесь, то игровое время команды сдвинется только на этот интервал, а остальное время будет списано в простой.|<span class="warn">Не учитывается, если пересчет состояния разрешен командам.</span>',
        'teams_can_update' => 'Пересчет состояния будет выполнятся индивидуально для каждой команды в момент обновления ею страницы задания.'
    ));
  }

}
