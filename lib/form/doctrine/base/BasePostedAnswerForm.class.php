<?php

/**
 * PostedAnswer form base class.
 *
 * @method PostedAnswer getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePostedAnswerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'task_state_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TaskState'), 'add_empty' => false)),
      'value'         => new sfWidgetFormInputText(),
      'post_time'     => new sfWidgetFormInputText(),
      'web_user_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'), 'add_empty' => true)),
      'answer_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Answer'), 'add_empty' => true)),
      'status'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'task_state_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TaskState'))),
      'value'         => new sfValidatorString(array('max_length' => 64)),
      'post_time'     => new sfValidatorInteger(),
      'web_user_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'), 'required' => false)),
      'answer_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Answer'), 'required' => false)),
      'status'        => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PostedAnswer', 'column' => array('task_state_id', 'value')))
    );

    $this->widgetSchema->setNameFormat('posted_answer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PostedAnswer';
  }

}
