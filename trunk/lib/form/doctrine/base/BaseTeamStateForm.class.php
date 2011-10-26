<?php

/**
 * TeamState form base class.
 *
 * @method TeamState getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTeamStateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'team_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Team'), 'add_empty' => false)),
      'game_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Game'), 'add_empty' => false)),
      'start_delay'      => new sfWidgetFormInputText(),
      'ai_enabled'       => new sfWidgetFormInputCheckbox(),
      'started_at'       => new sfWidgetFormInputText(),
      'finished_at'      => new sfWidgetFormInputText(),
      'status'           => new sfWidgetFormInputText(),
      'task_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Task'), 'add_empty' => true)),
      'team_last_update' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'team_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Team'))),
      'game_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Game'))),
      'start_delay'      => new sfValidatorInteger(array('required' => false)),
      'ai_enabled'       => new sfValidatorBoolean(array('required' => false)),
      'started_at'       => new sfValidatorInteger(array('required' => false)),
      'finished_at'      => new sfValidatorInteger(array('required' => false)),
      'status'           => new sfValidatorInteger(array('required' => false)),
      'task_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'), 'required' => false)),
      'team_last_update' => new sfValidatorInteger(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TeamState', 'column' => array('team_id', 'game_id')))
    );

    $this->widgetSchema->setNameFormat('team_state[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TeamState';
  }

}
