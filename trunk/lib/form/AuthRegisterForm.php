<?php

/**
 * Форма авторизации.
 *
 * @author vozdvin
 */
class AuthRegisterForm extends BaseForm
{

  public function setup()
  {
    parent::setup();

    $this->setWidgets(array(
        'login' => new sfWidgetFormInputText(),
        'password' => new sfWidgetFormInputPassword(),
        'passwordRepeat' => new sfWidgetFormInputPassword(),
        'email' => new sfWidgetFormInputText()));

    $this->setValidators(array(
        'login' => new sfValidatorString(array(
            'min_length' => WebUser::MIN_NAME_LENGTH,
            'max_length' => 30)),
        'password' => new sfValidatorString(array(
            'min_length' => WebUser::MIN_PWD_LENGTH,
            'max_length' => 30)),
        'passwordRepeat' => new sfValidatorString(array(
            'min_length' => WebUser::MIN_PWD_LENGTH,
            'max_length' => 30)),
        'email' => new sfValidatorEmail()));

    $this->getValidatorSchema()->
        setPostValidator(
            new sfValidatorSchemaCompare('password', '==', 'passwordRepeat'));

    $this->getWidgetSchema()->setNameFormat('register[%s]');

    $this->getWidgetSchema()->setLabels(array(
        'login' => 'Имя (от '.WebUser::MIN_NAME_LENGTH.' до 30 символов)',
        'password' => 'Пароль (от '.WebUser::MIN_PWD_LENGTH.' до 30 символов)',
        'passwordRepeat' => 'Повторите пароль',
        'email' => 'Адрес e-mail'));
  }

}

?>
