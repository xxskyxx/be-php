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
        'newrepeat' => new sfWidgetFormInputPassword()));

    $this->setValidators(array(
        'current' => new sfValidatorString(array(
            'min_length' => WebUser::MIN_NAME_LENGTH,
            'max_length' => 30)),
        'new' => new sfValidatorString(array(
            'min_length' => WebUser::MIN_PWD_LENGTH,
            'max_length' => 30)),
        'newrepeat' => new sfValidatorString(array(
            'min_length' => WebUser::MIN_PWD_LENGTH,
            'max_length' => 30))));

    $this->getValidatorSchema()->
        setPostValidator(
            new sfValidatorSchemaCompare('new', '==', 'newrepeat'));

    $this->getWidgetSchema()->setNameFormat('changepassword[%s]');

    $this->getWidgetSchema()->setLabels(array(
        'current' => 'Текущий пароль',
        'new' => 'Новый пароль (от '.WebUser::MIN_PWD_LENGTH.' до 30 символов)',
        'newrepeat' => 'Повторите пароль'));
  }

}

?>
