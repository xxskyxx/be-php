<?php

/**
 * TeamState form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TeamStateForm extends BaseTeamStateForm
{
  public function configure()
  {
    //Игра будет устанавливаться принудительно.
    unset($this['game_id']);
    $this->setWidget('game_id', new sfWidgetFormInputHidden());
    $this->setValidator('game_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Game'))));
    //Команда будет устанавливаться принудительно.
    unset($this['team_id']);
    $this->setWidget('team_id', new sfWidgetFormInputHidden());
    $this->setValidator('team_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Team'))));

    //Все что далее - не нужно на форме, так как управляется по-другому.
    unset($this['started_at']);
    unset($this['finished_at']);
    unset($this['status']);
    unset($this['task_state_id']);
    unset($this['task_id']);
    unset($this['team_last_update']);

    //Задержка только положительная
    $this->setValidator('start_delay', new sfValidatorInteger(array('min' => '0', 'required' => false)));
    
    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'start_delay' => 'Задержка старта:',
        'ai_enabled' => 'Автоматический выбор заданий:'
    ));
    $this->getWidgetSchema()->setHelps(array(
        'start_delay' => '&gt;=&nbsp;0, мин',
        'ai_enabled' => ''
    ));
  }
}
