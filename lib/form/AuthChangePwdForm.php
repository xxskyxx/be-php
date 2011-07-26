<?php

/**
 * Форма авторизации.
 *
 * @author vozdvin
 */
class AuthChangePwdForm extends BaseForm
{

  public function setup()
  {
    parent::setup();

    $this->setWidgets(array(
        'current' => new sfWidgetFormInputPassword(),
        'new' => new sfWidgetFormInputPassword(),
        'newrepeat' => new sfWidgetFormInputPassword()
    ));
    $this->setValidators(array(
        'current' => new sfValidatorString(array('required' => true)),
        'new' => new sfValidatorString(array('required' => true)),
        'newrepeat' => new sfValidatorString(array('required' => true))
    ));
    $this->getValidatorSchema()->setPostValidator(new sfValidatorSchemaCompare('new', '==', 'newrepeat'));
    
    //Configure
    $this->getWidgetSchema()->setNameFormat('changepassword[%s]');
    $this->getWidgetSchema()->setLabels(array(
        'current' => 'Текущий пароль:',
        'new' => 'Новый пароль (от '.WebUser::MIN_PWD_LENGTH.' до 30 символов):',
        'newrepeat' => 'Повторите пароль:'
    ));
  }

}

?>
