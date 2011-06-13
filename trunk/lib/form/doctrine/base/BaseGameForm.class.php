<?php

/**
 * Game form base class.
 *
 * @method Game getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGameForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'name'                     => new sfWidgetFormInputText(),
      'description'              => new sfWidgetFormTextarea(),
      'team_id'                  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Team'), 'add_empty' => true)),
      'team_name_backup'         => new sfWidgetFormInputText(),
      'start_briefing_datetime'  => new sfWidgetFormInputText(),
      'start_datetime'           => new sfWidgetFormInputText(),
      'stop_datetime'            => new sfWidgetFormInputText(),
      'finish_briefing_datetime' => new sfWidgetFormInputText(),
      'time_per_game'            => new sfWidgetFormInputText(),
      'time_per_task'            => new sfWidgetFormInputText(),
      'time_per_tip'             => new sfWidgetFormInputText(),
      'try_count'                => new sfWidgetFormInputText(),
      'update_interval'          => new sfWidgetFormInputText(),
      'teams_can_update'         => new sfWidgetFormInputCheckbox(),
      'task_define_default_name' => new sfWidgetFormInputText(),
      'task_tip_prefix'          => new sfWidgetFormInputText(),
      'status'                   => new sfWidgetFormInputText(),
      'started_at'               => new sfWidgetFormInputText(),
      'finished_at'              => new sfWidgetFormInputText(),
      'game_last_update'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                     => new sfValidatorString(array('max_length' => 16)),
      'description'              => new sfValidatorString(),
      'team_id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Team'), 'required' => false)),
      'team_name_backup'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'start_briefing_datetime'  => new sfValidatorPass(),
      'start_datetime'           => new sfValidatorPass(),
      'stop_datetime'            => new sfValidatorPass(),
      'finish_briefing_datetime' => new sfValidatorPass(),
      'time_per_game'            => new sfValidatorInteger(array('required' => false)),
      'time_per_task'            => new sfValidatorInteger(array('required' => false)),
      'time_per_tip'             => new sfValidatorInteger(array('required' => false)),
      'try_count'                => new sfValidatorInteger(array('required' => false)),
      'update_interval'          => new sfValidatorInteger(array('required' => false)),
      'teams_can_update'         => new sfValidatorBoolean(array('required' => false)),
      'task_define_default_name' => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'task_tip_prefix'          => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'status'                   => new sfValidatorInteger(array('required' => false)),
      'started_at'               => new sfValidatorInteger(),
      'finished_at'              => new sfValidatorInteger(),
      'game_last_update'         => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('game[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Game';
  }

}
