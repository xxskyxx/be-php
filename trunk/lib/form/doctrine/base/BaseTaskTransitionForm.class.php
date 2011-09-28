<?php

/**
 * TaskTransition form base class.
 *
 * @method TaskTransition getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTaskTransitionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'task_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Task'), 'add_empty' => false)),
      'target_task_id'   => new sfWidgetFormInputText(),
      'allow_on_success' => new sfWidgetFormInputCheckbox(),
      'allow_on_fail'    => new sfWidgetFormInputCheckbox(),
      'manual_selection' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'task_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))),
      'target_task_id'   => new sfValidatorInteger(array('required' => false)),
      'allow_on_success' => new sfValidatorBoolean(array('required' => false)),
      'allow_on_fail'    => new sfValidatorBoolean(array('required' => false)),
      'manual_selection' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TaskTransition', 'column' => array('task_id', 'target_task_id')))
    );

    $this->widgetSchema->setNameFormat('task_transition[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TaskTransition';
  }

}
