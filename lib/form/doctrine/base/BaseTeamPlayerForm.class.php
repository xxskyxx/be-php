<?php

/**
 * TeamPlayer form base class.
 *
 * @method TeamPlayer getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTeamPlayerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'web_user_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'), 'add_empty' => false)),
      'team_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Team'), 'add_empty' => false)),
      'is_leader'   => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'web_user_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'))),
      'team_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Team'), 'required' => false)),
      'is_leader'   => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'TeamPlayer', 'column' => array('web_user_id', 'team_id')))
    );

    $this->widgetSchema->setNameFormat('team_player[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TeamPlayer';
  }

}
