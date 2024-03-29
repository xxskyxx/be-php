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
        'full_name' => new sfWidgetFormInputText(),
        'password' => new sfWidgetFormInputPassword(),
        'passwordRepeat' => new sfWidgetFormInputPassword(),
        'email' => new sfWidgetFormInputText()
    ));
    $this->setValidators(array(
        'login' => new sfValidatorString(array('min_length' => WebUser::MIN_NAME_LENGTH, 'max_length' => 30)),
        'full_name' => new sfValidatorString(array('required' => true)),
        'password' => new sfValidatorString(array('required' => true)),
        'passwordRepeat' => new sfValidatorString(array('required' => true)),
        'email' => new sfValidatorEmail()
    ));

    $this->getValidatorSchema()->setPostValidator(new sfValidatorSchemaCompare('password', '==', 'passwordRepeat'));

    //Configure
    $this->getWidgetSchema()->setNameFormat('register[%s]');
    $this->getWidgetSchema()->setLabels(array(
        'login' => 'Имя:',
        'full_name' => 'Ф.И.(О.)',
        'password' => 'Пароль:',
        'passwordRepeat' => 'Повторите пароль:',
        'email' => 'Адрес e-mail:'));
    $this->getWidgetSchema()->setHelps(array(
        'login' => 'Одно слово, от '.WebUser::MIN_NAME_LENGTH.' до 32 букв.',
        'full_name' => '2 или 3 слова, всего до 255 букв.',
        'password' => 'От '.WebUser::MIN_PWD_LENGTH.' до 32 символов.',
        'passwordRepeat' => 'Лучше снова набрать вручную, а не копировать.',
        'email' => 'Желательно действующий.'
    ));
  }

}

?>
