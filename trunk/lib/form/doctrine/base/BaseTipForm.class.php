<?php

/**
 * Tip form base class.
 *
 * @method Tip getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTipForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'task_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Task'), 'add_empty' => false)),
      'name'      => new sfWidgetFormInputText(),
      'define'    => new sfWidgetFormTextarea(),
      'delay'     => new sfWidgetFormInputText(),
      'answer_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Answer'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'task_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))),
      'name'      => new sfValidatorString(array('max_length' => 64)),
      'define'    => new sfValidatorString(),
      'delay'     => new sfValidatorInteger(array('required' => false)),
      'answer_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Answer'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tip[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Tip';
  }

}
