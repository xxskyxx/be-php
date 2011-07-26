<?php

/**
 * Форма авторизации.
 *
 * @author vozdvin
 */
class AuthLoginForm extends BaseForm
{

  public function setup()
  {
    parent::setup();

    $this->setWidgets(array(
        'login' => new sfWidgetFormInputText(),
        'password' => new sfWidgetFormInputPassword()
    ));
    $this->setValidators(array(
        'login' => new sfValidatorString(array('min_length' => WebUser::MIN_NAME_LENGTH)),
        'password' => new sfValidatorString(array('required' => true))
    ));

    //Configure
    $this->getWidgetSchema()->setNameFormat('auth[%s]');
    $this->getWidgetSchema()->setLabels(array(
        'login' => 'Имя:',
        'password' => 'Пароль:'
    ));
  }

}

?>
