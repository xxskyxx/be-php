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
    $this->setWidget('game_id', new sfWidgetFormInputHidden());
    $this->setValidator('game_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Game'))));

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'name' => 'Название',
        'time_per_task_local' => 'Длительность, мин',
        'manual_start' => 'Требует разрешения на старт',
        'try_count_local' => 'Неверных ответов не более',
        'priority_free' => 'Приоритет когда свободно',
        'priority_queued' => 'Приоритет когда выдано кому-либо',
        'priority_busy' => 'Приоритет когда выполняется кем-либо',
        'priority_filled' => 'Дополнительно приоритет когда заполнено',
        'priority_per_team' => 'Дополнительно приоритет на каждую команду',
        'max_teams' => 'Выполняющих команд не более',
        'locked' => 'Заблокировано'
    ));
  }

}
