<?php

/**
 * UsedTip form base class.
 *
 * @method UsedTip getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUsedTipForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'task_state_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TaskState'), 'add_empty' => false)),
      'tip_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Tip'), 'add_empty' => false)),
      'status'        => new sfWidgetFormInputText(),
      'used_since'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'task_state_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TaskState'))),
      'tip_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Tip'))),
      'status'        => new sfValidatorInteger(array('required' => false)),
      'used_since'    => new sfValidatorInteger(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'UsedTip', 'column' => array('task_state_id', 'tip_id')))
    );

    $this->widgetSchema->setNameFormat('used_tip[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsedTip';
  }

}
