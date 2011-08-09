<?php

/**
 * SystemSettings form base class.
 *
 * @method SystemSettings getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSystemSettingsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'notify_email_addr'  => new sfWidgetFormInputText(),
      'contact_email_addr' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'notify_email_addr'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'contact_email_addr' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('system_settings[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SystemSettings';
  }

}
