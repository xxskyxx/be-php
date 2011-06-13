<?php

/**
 * Task form base class.
 *
 * @method Task getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTaskForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'name'                => new sfWidgetFormInputText(),
      'time_per_task_local' => new sfWidgetFormInputText(),
      'manual_start'        => new sfWidgetFormInputCheckbox(),
      'try_count_local'     => new sfWidgetFormInputText(),
      'priority_free'       => new sfWidgetFormInputText(),
      'priority_busy'       => new sfWidgetFormInputText(),
      'priority_per_team'   => new sfWidgetFormInputText(),
      'max_teams'           => new sfWidgetFormInputText(),
      'locked'              => new sfWidgetFormInputCheckbox(),
      'game_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Game'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                => new sfValidatorString(array('max_length' => 32)),
      'time_per_task_local' => new sfValidatorInteger(array('required' => false)),
      'manual_start'        => new sfValidatorBoolean(array('required' => false)),
      'try_count_local'     => new sfValidatorInteger(array('required' => false)),
      'priority_free'       => new sfValidatorInteger(array('required' => false)),
      'priority_busy'       => new sfValidatorInteger(array('required' => false)),
      'priority_per_team'   => new sfValidatorInteger(array('required' => false)),
      'max_teams'           => new sfValidatorInteger(array('required' => false)),
      'locked'              => new sfValidatorBoolean(array('required' => false)),
      'game_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Game'))),
    ));

    $this->widgetSchema->setNameFormat('task[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Task';
  }

}
