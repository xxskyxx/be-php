<?php

/**
 * TaskConstraint form base class.
 *
 * @method TaskConstraint getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTaskConstraintForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'task_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Task'), 'add_empty' => false)),
      'target_task_id' => new sfWidgetFormInputText(),
      'priority_shift' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'task_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))),
      'target_task_id' => new sfValidatorInteger(array('required' => false)),
      'priority_shift' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TaskConstraint', 'column' => array('task_id', 'target_task_id')))
    );

    $this->widgetSchema->setNameFormat('task_constraint[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TaskConstraint';
  }

}
