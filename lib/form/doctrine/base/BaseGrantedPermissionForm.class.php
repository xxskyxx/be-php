<?php

/**
 * GrantedPermission form base class.
 *
 * @method GrantedPermission getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGrantedPermissionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'web_user_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'), 'add_empty' => false)),
      'permission_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Permission'), 'add_empty' => false)),
      'filter_id'     => new sfWidgetFormInputText(),
      'deny'          => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'web_user_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'))),
      'permission_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Permission'), 'required' => false)),
      'filter_id'     => new sfValidatorInteger(array('required' => false)),
      'deny'          => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'GrantedPermission', 'column' => array('web_user_id', 'permission_id', 'filter_id')))
    );

    $this->widgetSchema->setNameFormat('granted_permission[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GrantedPermission';
  }

}
