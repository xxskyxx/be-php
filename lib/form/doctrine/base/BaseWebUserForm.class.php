<?php

/**
 * WebUser form base class.
 *
 * @method WebUser getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseWebUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'login'      => new sfWidgetFormInputText(),
      'pwd_hash'   => new sfWidgetFormInputText(),
      'full_name'  => new sfWidgetFormInputText(),
      'email'      => new sfWidgetFormInputText(),
      'tag'        => new sfWidgetFormInputText(),
      'is_enabled' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'login'      => new sfValidatorString(array('max_length' => 32)),
      'pwd_hash'   => new sfValidatorString(array('max_length' => 32)),
      'full_name'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'email'      => new sfValidatorString(array('max_length' => 255)),
      'tag'        => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'is_enabled' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('web_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'WebUser';
  }

}
