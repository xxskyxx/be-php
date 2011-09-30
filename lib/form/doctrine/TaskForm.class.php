<?php

/**
 * Task form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TaskForm extends BaseTaskForm
{

  public function configure()
  {
    //Игра будет задаваться принудительно.
    unset($this['game_id']);
    unset($this['priority_queued']);
    $this->setWidget('game_id', new sfWidgetFormInputHidden());
    $this->setValidator('game_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Game'))));

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'name' => 'Внутреннее название:',
        'public_name' => 'Открытое название:',
        'time_per_task_local' => 'Длительность:',
        'try_count_local' => 'Неверных ответов:',
        'manual_start' => 'Ручной старт:',
        'max_teams' => 'Выполняющих команд:',
        'locked' => 'Заблокировано:',
        'priority_free' => 'Когда свободно:',
        'priority_busy' => 'Когда кому-то выдано:',
        'priority_filled' => 'Когда заполнено:',
        'priority_per_team' => 'На каждую команду:'
    ));
    
    $this->getWidgetSchema()->setHelps(array(
        'name' => '',
        'time_per_task_local' => 'мин',
        'try_count_local' => 'не более ...',
        'manual_start' => '',
        'max_teams' => 'не более ...',
        'locked' => '',
        'priority_free' => '',
        'priority_busy' => '',
        'priority_filled' => '',
        'priority_per_team' => ''
    ));    
  }

}
