<?php

/**
 * TaskState form base class.
 *
 * @method TaskState getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTaskStateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'team_state_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TeamState'), 'add_empty' => false)),
      'task_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Task'), 'add_empty' => false)),
      'given_at'         => new sfWidgetFormInputText(),
      'started_at'       => new sfWidgetFormInputText(),
      'accepted_at'      => new sfWidgetFormInputText(),
      'task_idle_time'   => new sfWidgetFormInputText(),
      'done_at'          => new sfWidgetFormInputText(),
      'task_time_spent'  => new sfWidgetFormInputText(),
      'closed'           => new sfWidgetFormInputCheckbox(),
      'status'           => new sfWidgetFormInputText(),
      'task_last_update' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'team_state_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TeamState'))),
      'task_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))),
      'given_at'         => new sfValidatorInteger(),
      'started_at'       => new sfValidatorInteger(array('required' => false)),
      'accepted_at'      => new sfValidatorInteger(array('required' => false)),
      'task_idle_time'   => new sfValidatorInteger(array('required' => false)),
      'done_at'          => new sfValidatorInteger(array('required' => false)),
      'task_time_spent'  => new sfValidatorInteger(array('required' => false)),
      'closed'           => new sfValidatorBoolean(array('required' => false)),
      'status'           => new sfValidatorInteger(array('required' => false)),
      'task_last_update' => new sfValidatorInteger(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TaskState', 'column' => array('team_state_id', 'task_id')))
    );

    $this->widgetSchema->setNameFormat('task_state[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TaskState';
  }

}
