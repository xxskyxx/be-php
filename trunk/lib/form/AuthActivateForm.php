<?php

/**
 * Форма авторизации.
 *
 * @author vozdvin
 */
class AuthActivateForm extends BaseForm
{

  public function setup()
  {
    parent::setup();

    $this->setWidgets(array(
        'login' => new sfWidgetFormInputText(),
        'key' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidators(array(
        'login' => new sfValidatorString(array('min_length' => WebUser::MIN_NAME_LENGTH)),
        'key' => new sfValidatorString(array(
            'min_length' => WebUser::ACTIVATION_KEY_LENGTH,
            'max_length' => WebUser::ACTIVATION_KEY_LENGTH
        ))
    ));

    //Configure
    $this->getWidgetSchema()->setNameFormat('authactivate[%s]');
    $this->getWidgetSchema()->setLabels(array(
        'login' => 'Имя',
        'key' => 'Ключ активации'
    ));
  }

}

?>
