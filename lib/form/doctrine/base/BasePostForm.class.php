<?php

/**
 * Post form base class.
 *
 * @method Post getObject() Returns the current form's model object
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePostForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'blog_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Blog'), 'add_empty' => false)),
      'web_user_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'), 'add_empty' => true)),
      'text'        => new sfWidgetFormTextarea(),
      'create_time' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'blog_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Blog'))),
      'web_user_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'), 'required' => false)),
      'text'        => new sfValidatorString(),
      'create_time' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('post[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Post';
  }

}
