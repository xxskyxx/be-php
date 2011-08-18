<?php

/**
 * TeamCreateRequest form base class.
 *
 * @method TeamCreateRequest getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTeamCreateRequestForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'web_user_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'), 'add_empty' => false)),
      'name'        => new sfWidgetFormInputText(),
      'full_name'   => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormInputText(),
      'tag'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'web_user_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'))),
      'name'        => new sfValidatorString(array('max_length' => 32)),
      'full_name'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description' => new sfValidatorString(array('max_length' => 255)),
      'tag'         => new sfValidatorString(array('max_length' => 32)),
    ));

    $this->widgetSchema->setNameFormat('team_create_request[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TeamCreateRequest';
  }

}
